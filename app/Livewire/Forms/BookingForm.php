<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\TripsModel;
use App\Models\Reservations; 
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingReservedCustomer;
use App\Notifications\BookingReservedAdmin;
use Illuminate\Support\Str;

class BookingForm extends Component
{
    public $currentStep = 1;
    public string $name = '';
    public string $email = '';
    public string $phone_number = '';
    public string $address_line_1 = '';
    public string $address_line_2 = '';
    public string $city = '';
    public string $state = '';
    public string $zipcode = '';
    public string $tripID;
    public array $states = [];
    public string $error = '';

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|string|email',
        'phone_number' => ['required', 'regex:/^\d{3}-\d{3}-\d{4}$/'],
        'address_line_1' => ['required', 'regex:/^\d+\s[A-z]+\s[A-z]+/'],
        'city' => ['required'],
        'state' => ['required'],
        'zipcode' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
    ];

    protected $validationAttributes = [
        'name' => 'Name',
        'email' => 'Email',
        'phone_number' => 'Phone Number',
        'address_line_1' => 'Street Address',
        'address_line_2' => 'Street Address 2',
        'city' => 'City',
        'state' => 'State',
        'zipcode' => 'Zipcode',
    ];

    public function mount($tripID)
    {
        $this->tripID = $tripID;
        $statesJson = file_get_contents(resource_path('js/states.json'));
        $statesArray = json_decode($statesJson, true);

        $this->states = array_map(function($name, $code) {
            return ['code' => $code, 'name' => $name];
        }, $statesArray, array_keys($statesArray));
       
    }

    public function updatedPhoneNumber($value)
    {
        $numbersOnly = preg_replace('/\D/', '', $value);
        if (strlen($numbersOnly) >= 7) {
            $formatted = substr($numbersOnly, 0, 3) . '-' . substr($numbersOnly, 3, 3) . '-' . substr($numbersOnly, 6, 4);
        } elseif (strlen($numbersOnly) >= 3) {
            $formatted = substr($numbersOnly, 0, 3) . '-' . substr($numbersOnly, 3);
        } else {
            $formatted = $numbersOnly;
        }
        $this->phone_number = $formatted;
    }

    public function nextStep()
    {
        $this->validateCurrentStep();

        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    private function validateCurrentStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'name' => $this->rules['name'],
                'email' => $this->rules['email'],
                'phone_number' => $this->rules['phone_number'],
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'address_line_1' => $this->rules['address_line_1'],
                'city' => $this->rules['city'],
                'state' => $this->rules['state'],
                'zipcode' => $this->rules['zipcode'],
            ]);
        }
    }

    public function bookTrip()
    {
        $this->validate();
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $trip = TripsModel::findOrFail($this->tripID);
        $reservationID = Str::uuid();
    
        // If trip is unavailable, redirect
        if ($trip->tripAvailability === 'unavailable') {
            return redirect('/');
        }
    
        // If the trip is coming soon, handle reservation without Stripe
        if ($trip->tripAvailability === 'coming soon') {

            $reservationExists = Reservations::where('email', $this->email)->orWhere('phone_number', $this->phone_number)->first();

            if(!empty($reservationExists)){
                $this->error = 'A reservation for this trip has already been confirmed for you. Please check your email for further information and next steps';
                return;
            }
            // Insert reservation into the Reservations model

            Reservations::create([
                'reservationID' => $reservationID,
                'stripe_product_id' => $trip->stripe_product_id,
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zipcode,
            ]);
    
            // Send notifications to customer and admin
            $data = [
                'reservationID' => $reservationID,
                'name' => $this->name,
                'tripLocation'=>$trip->tripLocation
            ];
    
            Notification::route('mail', $this->email)->notify(new BookingReservedCustomer($data));
            Notification::route('mail', config('mail.mailers.smtp.to_email'))->notify(new BookingReservedAdmin($data));
    
            return redirect()->route('reservation-confirmed', ['reservationID' => $reservationID]);
        }
    
        // Handle Stripe-related logic for available trips
        $existingCustomer = null;
        $customers = $stripe->customers->all(['email' => $this->email]);
    
        if (count($customers->data) > 0) {
            $existingCustomer = $customers->data[0]; // Use the first customer found with this email
        } else {
            // Create a new customer if not found
            $existingCustomer = $stripe->customers->create([
                'email' => $this->email,
                'name' => $this->name,
                'metadata' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone_number' => $this->phone_number,
                    'address_line_1' => $this->address_line_1,
                    'address_line_2' => $this->address_line_2,
                    'city' => $this->city,
                    'state' => $this->state,
                    'zipcode' => $this->zipcode,
                ],
            ]);
        }
    
        $stripe_session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card', 'cashapp', 'affirm'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product' => $trip->stripe_product_id,
                    'unit_amount' => $trip->tripPrice * 100,
                ],
                'quantity' => 1,
            ]],
            'customer' => $existingCustomer->id, // Use the existing or newly created customer ID
            'mode' => 'payment',
            'success_url' => url('/success') . '?session_id={CHECKOUT_SESSION_ID}&tripID=' . $this->tripID,
            'cancel_url' => route('booking.cancel', [
                'tripID' => $this->tripID,
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'city' => $this->city,
                'state' => $this->state,
                'zipcode' => $this->zipcode
            ]),
            'metadata' => [
                'tripID' => $this->tripID,
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'city' => $this->city,
                'state' => $this->state,
                'zipcode' => $this->zipcode,
                'stripe_product_id' => $trip->stripe_product_id
            ],
        ]);
    
        return redirect()->away($stripe_session->url);
    }
    

    public function render()
    {
        return view('livewire.forms.booking-form', [
            'states' => $this->states
        ]);
    }
}
