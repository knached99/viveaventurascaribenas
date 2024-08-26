<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Validator;

class BookingForm extends Component
{


    public function mount($tripID){
        $this->tripID = $tripID;

        $this->stripe = Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    #[Validate('required|string')]
    public string $name = '';

    #[Validate('required|string|email')]

    public string $email = '';

    #[Validate('required|regex:/(?:(?:\\+|0{0,2})91(\\s*[\\- ]\\s*)?|[0 ]?)?[789]\\d{9}|(\\d[ -]?){10}\\d/g')]

    public string $phone_number = '';

    
    public function bookTrip(){
        $this->validate();

        if(!$this->stripe){
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            
            $trip = TripsModel::findOrFail($this->tripID);

            $stripe_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product'=>$trip->stripe_product_id,
                        'unit_amount'=>$trip->tripPrice
                    ],
                    'quantity'=>1,
                ]],
                'customer_email'=>$this->email,
                'mode'=>'payment',
                'success_url'=>route('checkout.success'),
                'cancel_url'=>route('checkout.cancel'),

                'metadata'=> [
                    'tripID'=>$this->tripID,
                    'name'=>$this->name,
                    'phone_number'=>$this->phone_number
                ],

                ]);

                return redirect($stripe_session->url);
        }
    }

    public function render()
    {
        return view('livewire.forms.booking-form');
    }
}
