<?php 

namespace App\Livewire\Forms;

use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Form;
use App\Models\TripsModel;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Storage;
use Stripe\Exception\InvalidRequestException;
use Exception;

class TripForm extends Form {

    use WithFileUploads;

    protected $stripe;

    
    
    #[Validate('required|string')]
    public string $tripLocation = '';

    #[Validate('required|image|mimes:jpeg,png,jpg|max:2048')]
    public \Illuminate\Http\UploadedFile | null $tripPhoto = null;

    #[Validate('required|string')]
    public string $tripLandscape = '';

    #[Validate('required')]
    public string $tripAvailability = '';

    #[Validate('required')]
    public string $tripDescription = '';

    #[Validate('required')]
    public string $tripActivities = '';

    #[Validate('sometimes|date|before_or_equal:tripEndDate')]
    public string $tripStartDate = '';

    #[Validate('sometimes|date|after_or_equal:tripStartDate')]
    public string $tripEndDate = '';

    #[Validate('required|numeric|min:1')]
    public string $tripPrice = '';

    // Define the status and error properties
    public string $status = '';
    public string $error = '';

    public function mount(){
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    
    }

    public function submitTripForm(): void {
        $this->validate();

        if(!$this->stripe){
            \Log::info('Stripe not initialized');
            \Log::info('Initializing Stripe...');
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            \Log::info('Stripe Initialized!');
        }
    
        try {
            // Store the uploaded file in the 'booking_photos' directory under 'storage/app/public'
            $filePath = $this->tripPhoto->store('booking_photos', 'public');
    
            $imageURL = asset(Storage::url($filePath));


            // Create as a product in Stripe 
            $product = $this->stripe->products->create([
                'name'=>$this->tripLocation,
                'description'=>$this->tripDescription,
                'images' => [$imageURL]
            ]);

            // Create price in Stripe after successful product creation

            if($product){
                
                $price = $this->stripe->prices->create([
                    'unit_amount'=> $this->tripPrice * 100, // unit amount in stripe is stored in cents 
                    'currency' => 'usd',
                    'product'=>$product->id
                ]);

                if($price){
                    // Reset the temporary file from Livewire

                    $data = [
                        'tripID' => Str::uuid(),
                        'stripe_product_id'=>$product->id,
                        'tripLocation' => $this->tripLocation,
                        'tripDescription' => $this->tripDescription,
                        'tripActivities'=>$this->tripActivities,
                        'tripLandscape' => $this->tripLandscape,
                        'tripAvailability' => $this->tripAvailability,
                        'tripPhoto' => $filePath, // relative file path
                        'tripStartDate' => $this->tripStartDate,
                        'tripEndDate' => $this->tripEndDate,
                        'tripPrice' => $this->tripPrice
                    ];
        
        
                    // Save trip data
                    TripsModel::create($data);

                    
                    $this->resetForm();
            
                    // Set success message
                    $this->status = 'Trip Successfully Created!';
                }
            }

          

    
            
        } catch (Exception $e) {
            $this->error = 'Something went wrong while creating this trip';
            \Log::error('Uncaught PHP exception on line: ' . __LINE__ . ' in function: ' . __FUNCTION__ . ' in class: ' . __CLASS__ . ' In file: ' . __FILE__ . ' Error: ' . $e->getMessage());
        }
    }
    
    public function resetForm(): void {
        $this->tripLocation = '';
        $this->tripDescription = '';
        $this->tripLandscape = '';
        $this->tripAvailability = '';
        $this->tripPhoto = null; // Reset file
        $this->tripStartDate = '';
        $this->tripEndDate = '';
        $this->tripPrice = '';

        $this->status = ''; // Reset status
        $this->error = '';  // Reset error
    }

    public function render()
    {
        return view('livewire.forms.create-trip');
    }
}
