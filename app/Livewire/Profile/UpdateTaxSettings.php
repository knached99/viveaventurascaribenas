<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Stripe\Stripe;
use Stripe\StripeClient;
use Exception;

class UpdateTaxSettings extends Component
{

   
   // protected string $address = '';
    public string $city = '';
    public string $line1 = '';
    public ?string $line2 = '';
    public string $postal_code = '';
    public string $state = '';
    public array $states = [];
    public array $stripeTaxSettings = [];
    public $success = '';
    public $error = '';

    public function rules(){
        return [
            'city' => 'required|string',
            'line1' => 'required|string|regex:/^\d{1,5}\s([A-Za-z0-9]+(?:\s[A-Za-z0-9]+)*)(?:\s[A-Za-z]+)?$/',
            'line2' => 'sometimes|string|regex:/^[A-Za-z0-9#.,\-\s]*$/',
            'postal_code' => 'required|regex:/^\d{5}(-\d{4})?$/',
            'state' => 'required',
        ];
    }

    public function mount(){
        $statesJSON = file_get_contents(resource_path('js/states.json'));
        $statesARRAY = json_decode($statesJSON, true);
        $this->states = array_map(function($name, $code){
            return ['code'=> $code, 'name'=>$name];
        }, $statesARRAY, array_keys($statesARRAY));
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $this->stripeTaxSettings = $stripe->tax->settings->retrieve([])->toArray(); // serialized to an array as livewire does not support unserialized content being passed to the front-en
        // dd($this->stripeTaxSettings);
        $this->city = $this->stripeTaxSettings['head_office']['address']['city'];
        $this->state = $this->stripeTaxSettings['head_office']['address']['state'];
        $this->line1 = $this->stripeTaxSettings['head_office']['address']['line1'];
        $this->line2 = $this->stripeTaxSettings['head_office']['address']['line2'];
        $this->postal_code = $this->stripeTaxSettings['head_office']['address']['postal_code'];



    }

    public function updateStripTaxSettings() : void {
        $this->validate();
        
        try{
        
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        $tax = $stripe->tax->settings->update([
            'defaults'=>[
                'tax_behavior'=> 'exclusive',
            
            ],
            'head_office'=> [
                'address' => [
                    'city'=>$this->city,
                    'country'=>'US',
                    'line1'=>$this->line1,
                    'line2'=>$this->line2,
                    'postal_code' => $this->postal_code,
                    'state' => $this->state, 
                ],
            ],

         
        ]);

        // If Tax is updated in Stripe 
        if($tax){
            $this->success = 'Tax settings successfully updated in Stripe!';
        }
    }
    catch(\Stripe\Exception\InvalidRequestException $e){
        $this->error = 'An error occurred while communicating with Stripe. Please try again and if this issue persists, please contact the developer';
        \Log::error('Stripe InvalidRequestException occurred on line: '.__LINE__. ' in class: '.__CLASS__. ' in function: '.__FUNCTION__. ' Exception: '.$e->getMessge());
    }

    }
    
    public function render()
    {
        return view('livewire.profile.update-tax-settings', [
            'states'=>$this->states,
            'stripeTaxSettings'=>$this->stripeTaxSettings
        ]);
    }
}
