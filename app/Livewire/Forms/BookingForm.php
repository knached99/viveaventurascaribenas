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
use Carbon\Carbon;

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
    public string $num_trips = '';
    // public string $payment_option = '';
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
        // 'payment_option'=>['required'],
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
        // 'payment_option' => 'Payment Option',
    ];

    public function mount($tripID)
    {
        $this->tripID = $tripID;
        $trip = TripsModel::findOrFail($this->tripID);
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
                // 'payment_option'=>$this->rules['payment_option'],
                
            ]);
        }
    }

    private function getPriceIDForProduct($productID){
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        $prices = $stripe->prices->all([
            'product'=>$productID,
        ]);

        if(isset($prices->data[0])){
            return $prices->data[0]->id;
        }
        else{
            throw new \Exception('No prices found for product '.$productID);
            \Log::error('Cannot find Stripe Product ID: '.$productID. ' in function: '.__FUNCTION__. ' in class: '.__CLASS__. ' on line: '.__LINE__);
        }
    }

    private function createInstallmentInvoices($stripe, $customer, $priceID, $installments, $dueDates)
{
    foreach ($installments as $index => $amount) {
        // Create an invoice item for each installment
        $stripe->invoiceItems->create([
            'customer' => $customer->id,
            'price' => $priceID,
            'quantity' => 1,
            'description' => 'Installment ' . ($index + 1),
        ]);

        // Create an invoice with 'send_invoice' and set the due date
        $invoice = $stripe->invoices->create([
            'customer' => $customer->id,
            'collection_method' => 'send_invoice', // Customer will pay manually
            'due_date' => $dueDates[$index]->timestamp, // Set due date for installment
            'auto_advance' => true // Automatically finalize the invoice after it's created
        ]);

        // Finalize the invoice
        $stripe->invoices->finalizeInvoice($invoice->id);
        
    }
}

private function createFullPaymentInvoice($stripe, $customer, $priceID, $totalAmount)
{
    // Create an invoice item for the full payment
    $stripe->invoiceItems->create([
        'customer' => $customer->id,
        'price' => $priceID,
        'quantity' => 1,
        'description' => 'Full Payment',
    ]);

    // Create a single invoice for full payment
    $invoice = $stripe->invoices->create([
        'customer' => $customer->id,
        'collection_method' => 'send_invoice', // Customer will pay manually
        'auto_advance' => true // Automatically finalize the invoice after it's created
    ]);

    // Finalize the invoice
    $stripe->invoices->finalizeInvoice($invoice->id);
}


public function createPartialPayments($productID, $totalAmount, $installments, $dueDates, $payment_option)
{
    \Log::info('Initializing Stripe...');
    
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

    \Log::info('Retrieving Price ID...');
    $priceID = $this->getPriceIDForProduct($productID);
    
    \Log::info('Price ID: '.$priceID);

    // Create or retrieve customer
    \Log::info('Creating or retrieving customer in Stripe..');

    $customer = null;
    $customers = $stripe->customers->all(['email' => $this->email]);

    if (count($customers->data) > 0) {
        $customer = $customers->data[0]; // Use the first customer found with this email
    } else {
        // Create new customer if none exists
        $customer = $stripe->customers->create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone_number
        ]);
        \Log::info('Customer created: ' . $customer->id);
    }

    // Handle payment options
    if ($payment_option === 'pay_in_full') {
        \Log::info('Customer is choosing to pay in full.');
        $this->createFullPaymentInvoice($stripe, $customer, $priceID, $totalAmount);
    } elseif ($payment_option === 'installments') {
        \Log::info('Customer opted to pay in installments.');
        $this->createInstallmentInvoices($stripe, $customer, $priceID, $installments, $dueDates);
    }

    \Log::info('Invoices created successfully.');
    return $stripe->invoices->all(['customer' => $customer->id]); // Return all invoices for the customer
}





    public function bookTrip()
{
    $this->validate();

    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    $trip = TripsModel::findOrFail($this->tripID);
    $reservationID = Str::uuid();
    
    // Ensure trip name is not empty and provide a fallback
    $tripName = $trip->tripName ?? 'Trip Reservation'; // Default name if tripName is empty

    if($trip->num_trips === 0){
        return redirect('/');
    }
    // If trip is unavailable, redirect
    if ($trip->tripAvailability === 'unavailable') {
        return redirect('/');
    }


    // If the trip is coming soon, handle reservation without Stripe
    if ($trip->tripAvailability === 'coming soon') {
        $reservationExists = Reservations::where('email', $this->email)
            ->orWhere('phone_number', $this->phone_number)
            ->first();

        if (!empty($reservationExists)) {
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

        if($this->num_trips !==0){
            $this->num_trips -=1;
            $trip->save();
        }

        // Send notifications to customer and admin
        $data = [
            'reservationID' => $reservationID,
            'name' => $this->name,
            'tripLocation' => $trip->tripLocation,
        ];

        Notification::route('mail', $this->email)->notify(new BookingReservedCustomer($data));
        Notification::route('mail', config('mail.mailers.smtp.to_email'))->notify(new BookingReservedAdmin($data));

        return redirect()->route('reservation-confirmed', ['reservationID' => $reservationID]);
    }

    // Calculate subtotal, tax, and total price
    // $salesTaxRate = 0.07; // Example 7% sales tax
    // $subtotal = $trip->tripPrice;
    // $taxAmount = $subtotal * $salesTaxRate;
    // $totalAmount = $subtotal + $taxAmount;

    // Handle Stripe-related logic for available trips 
    // $totalAmount = $trip->tripAmount * 100;
    // $initialDownPaymentpercentage = 0.60; // 60 % initial downpayment 
    // $finalDownpaymentPercentage = 0.40; // Remaining 40 % 

    // $downPaymentAmount = round($totalAmount * $initialDownPaymentpercentage, 2);
    // $finalDownpaymentPercentage = round($totalAmount * $finalDownpaymentPercentage, 2);

    // $installments = [
    //     $downPaymentAmount,
    //     $finalDownpaymentPercentage
    // ];
    // $now = Carbon::now();
    // $nextWeek = $now->addWeek();
    // $dueDates = [
    //     $now,  // Initial payment is due now 
    //     $nextWeek // Final payment is due a week from initial payment 
    // ];

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
                'product_data' => [
                    'name' => $tripName,
                    'metadata' => [
                        'stripe_product_id' => $trip->stripe_product_id
                    ],
                ],
                'unit_amount' => $trip->tripPrice * 100,
            ],
            'quantity' => 1,
        ]],
        'automatic_tax' => ['enabled' => true],
        'shipping_address_collection' => [
            'allowed_countries' => ['US'],
        ],
        'customer' => $existingCustomer->id,
        'customer_update' => [
            'address' => 'auto',
            'shipping' => 'auto'
        ],
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

    // $this->createPartialPayments($trip->stripe_product_id, $totalAmount, $installments, $dueDates, $this->payment_option);
    
    

    return redirect()->away($stripe_session->url);
}


    public function render()
    {
        return view('livewire.forms.booking-form', [
            'states' => $this->states
        ]);
    }
}