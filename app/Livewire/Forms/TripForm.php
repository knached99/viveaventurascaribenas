<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Form;
use Illuminate\Support\Facades\Cache;
use App\Models\TripsModel;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Storage;
use Stripe\Exception\InvalidRequestException;
use Carbon\Carbon;
use Exception;

use App\Helpers\Helper;

class TripForm extends Form {

    use WithFileUploads;

    // Define the constant for cache key
    const CACHE_KEY_TRIPS = 'trips_cache_key';

    protected $stripe;

    public string $tripID = '';
    public string $tripLocation = '';
    public ?array $tripLandscape = [];
    public string $tripAvailability = '';
    public string $tripDescription = '';
    public string $tripActivities = '';
    public string $tripStartDate = '';
    public string $tripEndDate = '';
    public ?int $tripPrice = 0;
    public array $tripCosts = [];
    public ?int $num_trips = 0;
    public bool $active = false;
    
    // Validate that 'tripPhoto' is an array of images with specific rules
    #[Validate('required|array|max:3')]
    public ?array $tripPhoto = [];

    public string $status = '';
    public string $error = '';

    public function rules()
    {

        $rules = [
            'tripPhoto.*' => 'image|mimes:jpeg,png,jpg', // Validation for each file
            'tripLocation' => 'required|string',
            'tripLandscape' => 'required|array',
            'tripAvailability' => 'required|string',
            'tripDescription' => 'required|string',
            'tripActivities' => 'required|string',
            'tripCosts.*.name' => 'sometimes|string',
            'tripCosts.*.amount'=>'sometimes|numeric|min:1',
            'num_trips'=>'required|min:1', 
        ];

        if(!in_array($this->tripAvailability, ['coming soon', 'unavailable'])){
            $rules['tripPrice'] ='required|numeric|min:1';
            $rules['tripStartDate'] = 'required|date|before_or_equal:tripEndDate';
            $rules['tripEndDate'] = 'required|date|after_or_equal:tripStartDate';
        }

        return $rules;
    }


    protected $messages =[
        'tripPhoto.image'=>'The image you selected is not valid',
        'tripPhoto.mimes'=>'The image you selected must be a valid jpg, jpeg, or png file',
        'tripLocation.required'=>'Provide the location of this trip',
        'tripLandscape.required'=>'Select the landscapes available in this trip',
        'tripAvailability.required'=>'Select the availability of this trip',
        'tripDescription.required'=>'Provide a description of this trip',
        'tripActivities.required'=>'Provide a list of activities for this trip',
        'tripPrice.required'=>'You must provide a price for this trip',
        'num_trips.required'=>'Please enter a number for the number of slots you want to make available for this trip',
    ];

    protected $validattionAttributes = [
        'tripPhoto'=>'Photos',
        'tripLocation'=> 'Location',
        'tripLandscape'=>'Landscape',
        'tripAvailability'=>'Availability',
        'tripDescription'=>'Description',
        'tripActivities'=>'Activities',
        'tripPrice'=>'Price',
        'num_trips'=>'Available Slots'
    ];

    public function addCost(){
        $this->tripCosts[] = ['name'=>'', 'amount'=>''];
    }

    public function removeCost($index){
        unset($this->tripCosts[$index]);
        // Reindexes array
        $this->tripCosts = array_values($this->tripCosts); 
    }

    public function updateProperty($data)
    {
        $this->{$data['property']} = $data['value'];
    }

    public function mount(){
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        // Log to see if Str::uuid() is being executed
        \Log::info('Generating UUID in mount method.');

        $this->emit('setEditorContent', [
            'property' => 'form.tripDescription',
            'value' => $this->tripDescription,
        ]);
    
        $this->emit('setEditorContent', [
            'property' => 'form.tripActivities',
            'value' => $this->tripActivities,
        ]);

        if (empty($this->tripCosts)) {
            $this->tripCosts = [];
        }

        $this->active = (bool) $this->active ?? false;

        // Initializing to today's date
        $this->tripStartDate = Carbon::now()->format('Y-m-d');
        $this->tripEndDate = Carbon::now()->format('Y-m-d');
    }

    public function submitTripForm()
    {

        $this->validate();

        $tripCostsJson = json_encode($this->tripCosts);
        $tripLandscapeJson = json_encode($this->tripLandscape);

        if(!$this->stripe){
            $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        }

        try {
            $imageURLs = [];
           // $imagesArray = [];

            // Create booking_photos folder if it does not exist
            $dirPath = storage_path('app/public/booking_photos');
            if(!file_exists($dirPath)){
                mkdir($dirPath, 0755, true);
            }

            if (!empty($this->tripPhoto) && is_array($this->tripPhoto)) {
                \Log::info('User selected new pictures for upload. Iterating over new pictures..');
                

                foreach($this->tripPhoto as $photo){
                                                  
                        $imagePath = 'booking_photos/'.$photo->hashName().'.'.$photo->extension();
                        $photo->storeAs('public', $imagePath);
                        $newImageURLs[] = asset(Storage::url($imagePath));
                        \Log::info('Current image URLs array: ' . json_encode($imageURLs));

                }
                
    
            }

            $product = $this->stripe->products->create([
                'name' => $this->tripLocation,
                'description' => $this->tripDescription,
                'images' => $imageURLs
            ]);

            if ($product) {
                $price = $this->stripe->prices->create([
                    'unit_amount' => $this->tripPrice * 100, // unit amount in stripe is stored in cents
                    'currency' => 'usd',
                    'product' => $product->id,
                ]);

                if ($price) {
                    // Reset the temporary file from Livewire
                    $this->tripID = Str::uuid(5);

                    $data = [
                        'tripID' => $this->tripID,
                        'stripe_product_id' => $product->id,
                        'tripLocation' => $this->tripLocation,
                        'tripDescription' => $this->tripDescription,
                        'tripActivities' => $this->tripActivities,
                        'tripLandscape' => $tripLandscapeJson,
                        'tripAvailability' => $this->tripAvailability,
                        'tripPhoto' => json_encode($imageURLs),
                        'tripStartDate' => !empty($this->tripStartDate) ? $this->tripStartDate : Carbon::now()->format('Y-m-d'),
                        'tripEndDate' => !empty($this->tripEndDate) ? $this->tripEndDate : Carbon::now()->format('Y-m-d'),
                        'tripPrice' => $this->tripPrice,
                        'tripCosts' => $tripCostsJson,
                        'num_trips' => intval($this->num_trips) ?? 0,
                        'active' => $this->active ? true : false,
                        'slug'=>Str::slug($this->tripLocation),
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ];

                    // Save trip data
                    TripsModel::create($data);

                    // Invalidate cache
                    Cache::forget(self::CACHE_KEY_TRIPS);

                    $this->resetForm();

                    // Set success message
                    $this->status = 'Trip Successfully Created!';
                  // return redirect('/admin/trip/'.$this->tripID)->with('status', $this->status);
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
        $this->tripActivities = '';
        $this->tripLandscape = [];
        $this->tripAvailability = '';
        $this->tripPhoto = []; // Reset file array
        $this->tripStartDate = '';
        $this->tripEndDate = '';
        $this->tripPrice = 0;
        $this->tripCosts = [];
        $this->num_trips = 0;
        $this->active = false;

        $this->status = ''; // Reset status
        $this->error = '';  // Reset error
    }

    public function render()
    {
        return view('livewire.pages.create-trip');
    }

  

}
