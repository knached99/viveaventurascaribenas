<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\StripeClient;
use Stripe\Invoice;
use Stripe\InvoiceItem;
use Stripe\Exception\ApiErrorException;
use App\Models\TripsModel;
use App\Models\Reservations; 
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingReservedCustomer;
use App\Notifications\BookingReservedAdmin;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingForm extends Component
{
    protected $stripe;

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
    public string $num_trips = '';
    public string $payment_option = '';
    public string $tripAvailability = '';
    public array $states = [];
    public $customers;
    public string $error = '';


    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|string|email',
        'phone_number' => ['required', 'regex:/^\d{3}-\d{3}-\d{4}$/'],
        'address_line_1' => ['required', 'regex:/^\d+\s[A-z]+\s[A-z]+/'],
        'address_line_1'=> ['sometimes', 'regex:/^[\w\s,.-]*$/'],
        'city' => ['required'],
        'state' => ['required'],
        'zipcode' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
        'payment_option'=>['required'],
    ];

    protected $messages = [
        'name.required'=>'Please provide your first and last name',
        'email.required'=>'Please provide your email',
        'email.email'=>'You\'ve entered an invalid email',
        'phone_number.required'=>'Please provide your phone number',
        'phone_number.regex'=>'Your number must be a valid US phone number',
        'address_line_1.required'=>'Your street address is required',
        'address_line_1.regex'=>'You must provide a valid street address',
        'address_line_2.regex'=>'You must provide a valid PO box or suite number',
        'city.required'=>'Your city is required',
        'state.required'=>'Your state is required',
        'payment_option'=>'Please choose if you\'d like to pay in full or make partial payments',
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
        'payment_option' => 'Payment Option',
    ];

    public function mount($tripID)
    {
        

        \Log::info('Initializing Stripe client...');
        \Log::info('Stripe client initialized.');
        
        $this->tripID = $tripID;
        $trip = TripsModel::findOrFail($this->tripID);
        $this->tripAvailability = $trip->tripAvailability;
        $this->num_trips = $trip->num_trips;
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
                'payment_option'=>$this->rules['payment_option'],
                
            ]);
        }
    }


private function createSplitInvoices($customerID, $amount, $tripName)
{
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

    
    $initialPayment = $amount * 0.60;
    $finalPayment = $amount * 0.40;

    // Create initial invoice item
    $stripe->invoiceItems->create([
        'customer' => $customerID,
        'amount' => $initialPayment,
        'currency' => 'usd',
        'description' => 'Initial payment for ' . $tripName,
    ]);

    // First Invoice
    $firstInvoice = $stripe->invoices->create([
        'customer' => $customerID,
        'collection_method' => 'send_invoice',
        'auto_advance' => true,
        'days_until_due' => 0,
    ]);

    // Finalize first invoice and send to customer
    $stripe->invoices->finalizeInvoice($firstInvoice->id); // Fix the typo here

    // Second Invoice (for final payment)
    $secondInvoice = $stripe->invoices->create([
        'customer' => $customerID,
        'collection_method' => 'send_invoice',
        'auto_advance' => true,
        'days_until_due' => 7, // Due in 7 days
    ]);

    $stripe->invoices->finalizeInvoice($secondInvoice->id);
}




private function createStripeCheckoutSession($customerId, $trip, $tripName, $amount)
{
    $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    // Handle partial payments or full payments
    $finalAmount = $this->payment_option === 'partial_payments'
        ? $amount * 0.60  // 60% for partial payments
        : $amount;         // Full amount for 'pay_in_full'

    return $stripe->checkout->sessions->create([
        'payment_method_types' => ['card', 'cashapp', 'affirm'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $tripName,
                    'metadata' => [
                        'stripe_product_id' => $trip->stripe_product_id
                    ],
                ],
                'unit_amount' => $finalAmount * 100, // Stripe requires amount in cents
            ],
            'quantity' => 1,
        ]],

        'automatic_tax' => ['enabled' => true],
        'shipping_address_collection' => [
            'allowed_countries' => ['US'],
        ],

        'customer' => $customerId,
        'customer_update' => [
            'address' => 'auto',
            'shipping' => 'auto'
        ],

        'mode' => 'payment',
        'discounts'=> [[
            'coupon'=>$trip->stripe_coupon_id,
        ]],

        'success_url' => url('/success') . '?session_id={CHECKOUT_SESSION_ID}&tripID=' . $this->tripID,
        'cancel_url' => route('booking.cancel', [
            'tripID' => $this->tripID,
            'name' => $this->name,
            'email' => $this->email,
        ]),
        'metadata' => [
            'tripID' => $this->tripID,
            'stripe_product_id' => $trip->stripe_product_id,
            'name'=>$this->name,
            'email'=>$this->email,
            'phone_number'=>$this->phone_number,
            'address_line_1'=>$this->address_line_1,
            'address_line_2'=>$this->address_line_2,
            'city'=>$this->city,
            'state'=>$this->state,
            'zipcode'=>$this->zipcode,
        ],
    ]);
}


private function getOrCreateStripeCustomer(string $email, string $name){

    $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

    // Retrieve customers with the given email
    $customers = $stripe->customers->all(['email'=>$email]);

    // If a customer exists, return the first one
    if(count($customers->data) > 0){
        return $customers->data[0];
    }

    // If no customer exists, create a new one
    return $stripe->customers->create([
        'email' => $email,
        'name' => $name,
        'metadata' => [
            'name' => $name,
            'email' => $email,
        ],
    ]);
}


    private function handleComingSoonReservation($trip, $reservationID){
        $reservationExists = Reservations::where('email', $this->email)->orWhere('
        phone_number', $this->phone_number)->first();

        if(!empty($reservationExists)){
            $this->error = 'A reservation for this trip has already been confirmed for you. Please check your email for further information and next steps.';
            return;
        }

        Reservations::create([
            'reservationID'=>$reservationID,
            'stripe_product_id'=>$trip->stripe_product_id,
            'name'=>$this->name,
            'email'=>$this->email,
            'phone_number'=>$this->phone_number,
            'address_line_1'=>$this->address_line_1,
            'address_line_2'=>$this->address_line_2,
            'city'=>$this->city,
            'state'=>$this->state,
            'zip_code'=>$this->zipCode,
        ]);

        if($trip->num_trips !== 0){
            $trip->num_trips -= 1;
            $trip->save();
        }

        $data = [
            'reservationID'=>$reservationID,
            'name'=>$this->name,
            'tripLocation'=>$trip->tripLocation
        ];

        Notification::route('mail',  $this->email)->notify(new BookingReservedCustomer($data));
        Notification::route('mail', config('mail.mailers.smtp.to_email'))->notify(new BookingReservedAdmin($data));

        return redirect()->route('reservation-confirmed', ['reservationID'=>$reservationID]);
    }

    public function bookTrip()
    {
        $this->validate();
    
        $trip = TripsModel::findOrFail($this->tripID);
        $reservationID = Str::uuid();
    
        $tripName = $this->tripName ?? 'Trip Reservation';
    
        if ($trip->num_trips === 0 || $this->tripAvailability === 'unavailable') {
            return redirect()->route('landing.destination', ['tripID' => $this->tripID]);
        }
    
        if ($this->tripAvailability === 'coming soon') {
            return $this->handleComingSoonReservation($trip, $reservationID);
        }
    
        $existingCustomer = $this->getOrCreateStripeCustomer($this->email, $this->name);
    
        // Correct amount calculation (in dollars)
        $tripPrice = $trip->tripPrice; // This should be the price in dollars, not cents.
    
        // Now calculate the correct amount in dollars and later convert to cents where needed
        $amount = $tripPrice; // Amount is still in dollars here, do not multiply by 100 yet
    
        if ($this->payment_option === 'partial_payments') {
            // Pass amount in dollars to the method, it will handle the split invoices
            $this->createSplitInvoices($existingCustomer->id, $amount, $tripName);
        }
    
        // Create Stripe checkout session (amount will be multiplied by 100 in this method)
        $stripe_session = $this->createStripeCheckoutSession($existingCustomer->id, $trip, $tripName, $amount);
    
        return redirect()->away($stripe_session->url);
    }  




    public function render()
    {
        return view('livewire.forms.booking-form', [
            'states' => $this->states
        ]);
    }
}