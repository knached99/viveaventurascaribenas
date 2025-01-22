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
    
        if (!$this->stripe) {
            $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        }
    
        try {
            $imageURLs = []; // Initialize the correct variable to store image URLs
    
            // Create booking_photos folder if it does not exist
            $dirPath = storage_path('app/public/booking_photos');
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }
    
            // Handle uploaded images
            if (!empty($this->tripPhoto) && is_array($this->tripPhoto)) {
                foreach ($this->tripPhoto as $photo) {
                    // Validate that $photo is an UploadedFile instance
                    if ($photo instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = 'booking_photos/' . $photo->hashName();
                        $photo->storeAs('public', $imagePath); // Save the image
                        $imageURLs[] = asset(Storage::url($imagePath)); // Store the public URL
                        \Log::info('Added new image URL: ' . end($imageURLs));
                    } else {
                        \Log::warning('Invalid photo skipped: ' . json_encode($photo));
                    }
                }
            } else {
                \Log::info('No images were selected for upload.');
            }
    
            // Create a product in Stripe
            $product = $this->stripe->products->create([
                'name' => $this->tripLocation,
                'description' => $this->tripDescription,
                'images' => $imageURLs, // Pass the correct array of image URLs
                'tax_code'=>'txcd_20030000', // General Services 
            ]);
            
            if ($product) {
                $price = $this->stripe->prices->create([
                    'unit_amount' => $this->tripPrice * 100, // Stripe uses cents
                    'currency' => 'usd',
                    'product' => $product->id,
                    'tax_behavior'=> 'exclusive', // Exclusive means tax is not factored into total price  

                ]);
    
                if ($price) {
                    // Reset form and save trip data
                    $this->tripID = Str::uuid(5);
                    $data = [
                        'tripID' => $this->tripID,
                        'stripe_product_id' => $product->id,
                        'tripLocation' => $this->tripLocation,
                        'tripDescription' => $this->tripDescription,
                        'tripActivities' => $this->tripActivities,
                        'tripLandscape' => $tripLandscapeJson,
                        'tripAvailability' => $this->tripAvailability,
                        'tripPhoto' => json_encode($imageURLs), // Save URLs in the database
                        'tripStartDate' => $this->tripStartDate ?: Carbon::now()->format('Y-m-d'),
                        'tripEndDate' => $this->tripEndDate ?: Carbon::now()->format('Y-m-d'),
                        'tripPrice' => $this->tripPrice,
                        'tripCosts' => $tripCostsJson,
                        'num_trips' => intval($this->num_trips) ?? 0,
                        'active' => $this->active ? true : false,
                        'slug' => Str::slug($this->tripLocation),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
    
                    // Save to the database
                    TripsModel::create($data);
    
                    // Clear cache and reset the form
                    Cache::forget(self::CACHE_KEY_TRIPS);
                    $this->resetForm();
    
                    // Set success message
                    $this->status = 'Trip Successfully Created!';
                }
            }
        } catch (Exception $e) {
            $this->error = 'Something went wrong while creating this trip';
            \Log::error('Error on line ' . __LINE__ . ': ' . $e->getMessage());
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
