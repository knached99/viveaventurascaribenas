<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Form;
use Illuminate\Support\Facades\Cache;
use App\Models\TripsModel;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Square\SquareClientBuilder;
use Square\Environment;
use Square\Models\Money;
use Square\Models\CatalogItemVariation;
use Square\Models\CatalogObject;
use Square\Models\CatalogItem;
use Square\Models\UpsertCatalogObjectRequest;
use Square\Authentication\BearerAuthCredentialsBuilder;
use Square\Exceptions\ApiException;
use Exception;


class TripForm extends Form {

    use WithFileUploads;

    // Define the constant for cache key
    const CACHE_KEY_TRIPS = 'trips_cache_key';

    public $idempotencyKey;
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
    public $client; // Square Client 
    
    // Validate that 'tripPhoto' is an array of images with specific rules
    #[Validate('required|array|max:6')]
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
        // Validate the form data
        $this->validate();
    
        // Prepare data for saving
        $tripCostsJson = json_encode($this->tripCosts);
        $tripLandscapeJson = json_encode($this->tripLandscape);
    
        try {
            // Initializing Square client
            $accessToken = getenv('SQUARE_ACCESS_TOKEN');
    
            $client = SquareClientBuilder::init()
                ->bearerAuthCredentials(
                    BearerAuthCredentialsBuilder::init($accessToken)
                )
                ->environment(Environment::PRODUCTION) // Change to Environment::PRODUCTION in live mode
                ->build();
    
            // Generate a consistent Item ID
            $item_id = '#trip_id_'.Str::uuid();  // This is the ID format for your trip
            $variation_id = '#variation_' . Str::uuid(); // Unique variation ID
            $this->idempotencyKey = (string) Str::uuid();
            
            // Handle image uploads
            $imageURLs = $this->handleUploadedImages();
    
            // Pricing setup
            $price_money = new Money();
            $price_money->setAmount($this->tripPrice);
            $price_money->setCurrency('USD');
    
            // Create Item Variation (Variation must reference the correct Item ID)
            $item_variation_data = new CatalogItemVariation();
            $item_variation_data->setItemId($item_id); // Must match the Item's ID
            $item_variation_data->setName($this->tripLocation);
            $item_variation_data->setPricingType('FIXED_PRICING');
            $item_variation_data->setPriceMoney($price_money);
    
            // Catalog Object for Item Variation
            $catalog_variation_object = new CatalogObject('ITEM_VARIATION', $variation_id);
            $catalog_variation_object->setItemVariationData($item_variation_data);
    
            // Create Catalog Item
            $item_data = new CatalogItem();
            $item_data->setName($this->tripLocation);
            $item_data->setDescription(strip_tags($this->tripDescription));
            $item_data->setAbbreviation(substr($this->tripLocation, 0, 2));
            $item_data->setVariations([$catalog_variation_object]); // Attach variations
    
            // Catalog Object for Item (Uses the same Item ID)
            $catalog_item_object = new CatalogObject('ITEM', $item_id);
            $catalog_item_object->setItemData($item_data);
    
            // Upserting to Square backend 
            $body = new UpsertCatalogObjectRequest($this->idempotencyKey, $catalog_item_object);
    
            // API Call to upsert catalog object
            $api_response = $client->getCatalogApi()->upsertCatalogObject($body);
    

            if ($api_response->isSuccess()) {
                // Extract the generated Catalog Item ID
                $squareItemId = $api_response->getResult()->getCatalogObject()->getId();
            
                // Save trip to database
                TripsModel::create([
                    'tripID' => $squareItemId, // Store the Square trip ID
                    'idempotencyKey' => $this->idempotencyKey,
                    'tripLocation' => $this->tripLocation,
                    'tripPhoto' => $imageURLs,
                    'tripDescription' => $this->tripDescription,
                    'tripActivities' => $this->tripActivities,
                    'tripLandscape' => $tripLandscapeJson,
                    'tripAvailability' => $this->tripAvailability,
                    'tripStartDate' => $this->tripStartDate,
                    'tripEndDate' => $this->tripEndDate,
                    'tripPrice' => $this->tripPrice,
                    'tripCosts' => $tripCostsJson,
                    'num_trips' => $this->num_trips,
                    'active' => true,
                ]);
            
                $this->status = 'Trip created successfully!';
                \Log::info('API response: ' . json_encode($api_response->getResult()));
            } else {
                $this->error = 'Error communicating with Square SDK';
                \Log::error('API Error: ' . json_encode($api_response->getErrors()));
            }
            

        } catch (ApiException $e) {
            // Handle errors, logging, etc.
            \Log::error('Square API Error: ' . $e->getMessage());
            $this->error = 'Failed to create the trip. Check logs for more details';
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


    /**
 * Handle the image upload and return the URLs of the stored images.
 *
 * @return array
 */

    private function handleUploadedImages(): array {

        $imageURLs = [];

        // ensuring directory exists, if not, we create it 
        $dirPath = storage_path('app/public/booking_photos');
        
        if(!file_exists($dirPath)){
            mkdir($dirPath, 0755, true);
        }

        // if there are any uploaded images, we handle them here 

        if(!empty($this->tripPhoto) && is_array($this->tripPhoto)){

            foreach($this->tripPhoto as $photo){
                if($photo instanceof \Illuminate\Http\UploadedFile){

                    // storing images and retrieving the URLs

                    $imagePath = 'booking_photos/'.$photo->hashName(). '.'.$photo->extension();
                    $photo->storeAs('public', $imagePath);
                    $imageURLs[] = asset(Storage::url($imagePath));
                }
            }
        }

        return $imageURLs;
    }

    public function render()
    {
        return view('livewire.pages.create-trip');
    }

  

}
