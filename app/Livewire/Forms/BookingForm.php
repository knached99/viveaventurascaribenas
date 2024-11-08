<?php

namespace App\Livewire\Forms;

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
    public string $preferred_start_date = '';
    public string $preferred_end_date = '';

    public array $states = [];
    public $customers;
    public string $error = '';

    public $initialPayment = 0;
    public $finalPayment = 0;

    protected $listeners = ['payment_option_selected' => 'displayPartialAmount'];
    
    public $partialAmount = 0;

    public function mount($tripID)
    {
        \Log::info('Initializing Stripe client...');
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
    
    public function rules()
    {
        // Define the minimum start date
        $minStartDate = Carbon::now()->addWeek()->format('Y-m-d');

        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone_number' => ['required', 'regex:/^\d{3}-\d{3}-\d{4}$/'],
            'address_line_1' => ['required', 'regex:/^\d+\s[A-z]+\s[A-z]+/'],
            'address_line_2'=> ['sometimes', 'regex:/^[\w\s,.-]*$/'],
            'city' => ['required'],
            'state' => ['required'],
            'zipcode' => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
            'payment_option'=> ['sometimes'],
        ];

        // Add preferred dates rules if tripAvailability is 'coming soon'
        if ($this->tripAvailability === 'coming soon') {
            $rules['preferred_start_date'] = 'required|date|after_or_equal:' . $minStartDate;
            $rules['preferred_end_date'] = 'required|date|after:preferred_start_date';
        }

        return $rules;
    }

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
        'zipcode.required'=>'Your zip code is required',
        'zipcode.regex'=>'You must enter a valid US zipcode',
        'payment_option.required'=>'Please choose if you\'d like to pay in full or make partial payments',
        'preferred_start_date.required'=>'Please select a start date',
        'preferred_start_date.date'=>'You must select a valid start date',
        'preferred_start_date.after'=>'Start date must be at least 1 week from today',
        'preferred_end_date.required'=>'Please select a valid end date',
        'preferred_end_date.date'=>'You must select a valid end date',
        'preferred_end_date.after'=>'End date must not be equal to or before the start date',
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
        'preferred_start_date'=>'Start Date',
        'preferred_end_date'=>'End Date',
    ];


  
public function display_partial_amount()
{
    if ($this->payment_option == 'partial_payments') {
       
        $paymentData = $this->calculatePartialPayment();

        $this->initialPayment = $paymentData['initialPayment'];
    }
     else {
        // Reset the values when "Full Payment" is selected
        $this->initialPayment = 0;
    }
}


private function calculatePartialPayment()
{
    $trip = TripsModel::findOrFail($this->tripID);
    
    $initialPayment = $trip->tripPrice * 0.60;

    return [
        'initialPayment' => $initialPayment,
    ];
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
                'name' => $this->rules()['name'],
                'email' => $this->rules()['email'],
                'phone_number' => $this->rules()['phone_number'],
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'address_line_1' => $this->rules()['address_line_1'],
                'city' => $this->rules()['city'],
                'state' => $this->rules()['state'],
                'zipcode' => $this->rules()['zipcode'],
                'payment_option' => $this->rules()['payment_option'],
            ]);
        }

        elseif($this->currentStep === 3){
            $this->validate([
                'preferred_start_date'=>$this->rules()['preferred_start_date'],
                'preferred_end_date'=>$this->rules()['preferred_end_date'],
            ]);
        }
    }
    
   

    // This method is called when the user selects "partial payments" 
    private function createSplitInvoices($customerID, $amount, $tripName)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    
        $finalPayment = $amount * 0.40;
    
        /*
        TO
        find a way to pay the final payment invoice and check to see if there's a charge object associated with it
        */
    
        // Now create invoice item for the final payment
        $stripe->invoiceItems->create([
            'customer' => $customerID,
            'amount' => $finalPayment * 100, // Convert to cents for Stripe
            'currency' => 'usd',
            'description' => 'Final payment for ' . $tripName,
        ]);
    
        // Create and finalize the second invoice for the final payment
        $secondInvoice = $stripe->invoices->create([
            'customer' => $customerID,
            'collection_method' => 'send_invoice', // Automatically charge the customer
            'auto_advance' => true, // Automatically finalize and attempt collection
            'days_until_due'=>7,
            
        ]);
    
        $stripe->invoices->finalizeInvoice($secondInvoice->id); // Finalize and attempt to charge
    
    
    }
    

private function createStripeCheckoutSession($customerId, $trip, $tripName, $amount)
{
    
    $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

    $finalAmount = $this->payment_option === 'partial_payments'
        ? $amount * 0.60  // 60% for partial payments
        : $amount;
        
    
    try {

        $discounts = !empty($trip->stripe_coupon_id) ? [['coupon'=>$trip->stripe_coupon_id]]
        : null;

        $sessionData = [
            'payment_method_types' => ['card', 'cashapp', 'affirm'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $tripName,
                        'metadata' => [
                            'stripe_product_id' => $trip->stripe_product_id,
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
                'shipping' => 'auto',
            ],
            'mode' => 'payment',
            'success_url' => url('/success') . '?session_id={CHECKOUT_SESSION_ID}&tripID=' . $this->tripID,
            'cancel_url' => route('booking.cancel', [
                'tripID' => $this->tripID,
                'name' => $this->name,
                'email' => $this->email,
            ]),
            'metadata' => [
                'tripID' => $this->tripID,
                'stripe_product_id' => $trip->stripe_product_id,
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'city' => $this->city,
                'state' => $this->state,
                'zipcode' => $this->zipcode,
            ],
        ];

        if($discounts){
            $sessionData['discounts'] = $discounts;
        }

        return $stripe->checkout->sessions->create($sessionData);

}

catch(\Stripe\Exception\InvalidRequestException $e){

    \Log::critical('Error: '.$e->getMessage(). 'Encountered in method: '.__FUNCTION__. ' in class: '.__CLASS__. ' on line: '.__LINE__);
}

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


        $reservationExists = Reservations::where('email', $this->email)->orWhere('phone_number', $this->phone_number)->first();

        if(!empty($reservationExists)){
            $this->error = 'A reservation for this trip has already been confirmed for you. Please check your email '.$this->email. ' for further information and next steps.';
            return;
        }

        Reservations::create([
            'reservationID'=>$reservationID,
            'stripe_product_id'=>$trip->stripe_product_id,
            'tripID'=>$trip->tripID,
            'name'=>$this->name,
            'email'=>$this->email,
            'phone_number'=>$this->phone_number,
            'address_line_1'=>$this->address_line_1,
            'address_line_2'=>$this->address_line_2,
            'city'=>$this->city,
            'state'=>$this->state,
            'zip_code'=>$this->zipcode,
            'preferred_start_date'=>$this->preferred_start_date,
            'preferred_end_date'=>$this->preferred_end_date,
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
            return redirect()->route('destination', ['tripID' => $this->tripID]);
        }
    
        if ($this->tripAvailability === 'coming soon') {
            return $this->handleComingSoonReservation($trip, $reservationID);
        }
    
        $existingCustomer = $this->getOrCreateStripeCustomer($this->email, $this->name);
    
        // Correct amount calculation (in dollars)
    
        // Now calculate the correct amount in dollars and later convert to cents where needed
        $amount = $trip->tripPrice; // Amount is still in dollars here, do not multiply by 100 yet
    
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
            'states' => $this->states,
            'tripAvailability' => $this->tripAvailability
        ]);
    }
}