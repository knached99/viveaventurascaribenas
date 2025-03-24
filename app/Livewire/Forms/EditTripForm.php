<?php 
namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\TripsModel;
use App\Models\Reservations;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
// use Stripe\StripeClient;
// use Stripe\Coupon;
// use Stripe\PromotionCode;
// Importing Square classes
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
use Carbon\Carbon;

// Dispatch events and notifications to users for trip availability status updates
use App\Events\TripBecameAvailable;
use App\Notifications\TripAvailableNotification;

// Helper class for image resizing 
Use App\Helpers\Helper; 

class EditTripForm extends Component
{
    use WithFileUploads;

    public $trip;

    public $client; // Square Access Client 
    public $idempotencyKey;
    public string $tripLocation = '';
    public array $tripLandscape = []; 
    public array $tripPhotos = [];
    public string $tripAvailability = ''; 
    public string $tripDescription = ''; 
    public string $tripActivities = ''; 
    public string $tripStartDate = ''; 
    public string $tripEndDate = ''; 
    public string $tripPrice = '';
    public ?int $num_trips = 0;
    public bool $active = false;
    public string $slug = '';
    public $tripCosts = [];
    public ?array $existingImageURLs = [];

    public ?int $discountDuration = null; // Stripe needs this value to be an integer representing the number of months 
    public string $status = '';
    public string $success = '';
    public string $error = '';

    public string $discountCreateSuccess = '';
    public string $discountCreateError = '';

    public string $couponDeleteSuccess = '';
    public string $couponDeleteError = '';

    public string $imageReplaceSuccess = '';
    public string $imageReplaceError = '';
    public ?int $replaceIndex = null;
    public string $totalNetCost = '';
    public string $grossProfit = '';
    public string $netProfit = '';
    public ?string $averageStartDate = null;
    public ?string $averageEndDate = null; 
    public ?string $averageDateRange = null;
    private string $cacheKey = '';
    public ?int $reservationsCount = null;

    public $discountType = 'percentage';

    public $discountValue = '';

    public string $promotionCode = '';

    public function mount($trip)
    {
        $this->trip = $trip;
        $this->cacheKey = 'trip_' . $this->trip->tripID;
        
        // Load trip data from cache or database
        $cachedTrip = Cache::get($this->cacheKey);
        
        if ($cachedTrip) {

            $this->loadFromCache($cachedTrip);

        } else {
            $this->loadFromDatabase();
        }
        $this->existingImageURLs = json_decode($this->trip->tripPhotos, true) ?? [];
    }
    
    private function loadFromCache(array $cachedTrip): void
    {
        // Assuming tripPhotos contain URLs or paths, not TemporaryUploadedFile objects
        $this->tripLocation = $cachedTrip['tripLocation'];
        // $this->tripPhotos = isset($cachedTrip['tripPhotos']) ? json_decode($cachedTrip['tripPhotos'], true) : []; // This should be an array of URLs or paths
        // $this->tripLandscape = $cachedTrip['tripLandscape'];
        $this->tripPhotos = isset($cachedTrip['tripPhotos']) && is_string($cachedTrip['tripPhotos']) 
        ? json_decode($cachedTrip['tripPhotos'], true) 
        : $cachedTrip['tripPhoto'];

        $this->tripLandscape = isset($cachedTrip['tripLandscape']) && is_string($cachedTrip['tripLandscape'])
        ? json_decode($cachedTrip['tripLandscape'], true)
        : $cachedTrip['tripLandscape'];

        $this->tripAvailability = $cachedTrip['tripAvailability'];
        $this->tripDescription = $cachedTrip['tripDescription'];
        $this->tripActivities = $cachedTrip['tripActivities'];
        $this->tripStartDate = $cachedTrip['tripStartDate'];
        $this->tripEndDate = $cachedTrip['tripEndDate'];
        $this->tripPrice = $cachedTrip['tripPrice'];
        // $this->stripe_product_id = $cachedTrip['stripe_product_id'];
        // $this->stripe_coupon_id = $cachedTrip['stripe_coupon_id'];
        // $this->stripe_promo_id = $cachedTrip['stripe_promo_id'];
        $this->tripCosts = $cachedTrip['tripCosts'];
        $this->num_trips = $cachedTrip['num_trips'];
        $this->active = $cachedTrip['active'];
        $this->slug = $cachedTrip['slug'] ?? '';
        // $this->stripe_coupon_id = $cachedTrip['stripe_coupon_id'];
        // $this->stripe_promo_id = $cachedTrip['stripe_promo_id'];
    

    }
    
    private function loadFromDatabase(): void
    {
        $trip = TripsModel::findOrFail($this->trip->tripID);
        
        $this->tripLocation = $trip->tripLocation;
        $this->tripPhotos = is_string($trip->tripPhoto) ? json_decode($trip->tripPhoto, true) : $trip->tripPhoto;
        $this->tripLandscape = is_string($trip->tripLandscape) ? json_decode($trip->tripLandscape, true) : $trip->tripLandscape;
        $this->tripAvailability = $trip->tripAvailability;
        $this->tripDescription = $trip->tripDescription;
        $this->tripActivities = $trip->tripActivities;
        $this->tripStartDate = Carbon::parse($trip->tripStartDate)->format('Y-m-d');
        $this->tripEndDate = Carbon::parse($trip->tripEndDate)->format('Y-m-d');
        $this->tripPrice = $trip->tripPrice;
        // $this->stripe_product_id = $trip->stripe_product_id;
        // $this->stripe_coupon_id = $trip->stripe_coupon_id;
        // $this->stripe_promo_id = $trip->stripe_promo_id;
        $this->tripCosts = json_decode($trip->tripCosts, true);
        $this->num_trips = $trip->num_trips;
        $this->active = (bool) $trip->active;
        $this->slug = $trip->slug;
        // $this->stripe_promo_id = $trip->stripe_promo_id;
        // $this->stripe_coupon_id = $trip->stripe_coupon_id;
    
        // Cache trip data excluding TemporaryUploadedFile objects
        Cache::put($this->cacheKey, [
            'tripLocation' => $this->tripLocation,
            'tripPhotos'=>json_encode($this->tripPhotos),
            'tripLandscape' => $this->tripLandscape,
            'tripAvailability' => $this->tripAvailability,
            'tripDescription' => $this->tripDescription,
            'tripActivities' => $this->tripActivities,
            'tripStartDate' => $this->tripStartDate,
            'tripEndDate' => $this->tripEndDate,
            'tripPrice' => $this->tripPrice,
            // 'stripe_product_id' => $this->stripe_product_id,
            // 'stripe_coupon_id'=> $this->stripe_coupon_id ?? '',
            // 'stripe_promo_id' => $this->stripe_promo_id ?? '',
            'tripCosts' => $this->tripCosts,
            'num_trips' => $this->num_trips,
            'active' => $this->active,
            // 'stripe_coupon_id' => $this->stripe_coupon_id,
            // 'stripe_promo_id' => $this->stripe_promo_id,    
            
        ], 600); // Cached for 10 minutes
    
    
    }

  
    public function addCost()
    {
        \Log::info('Adding Trip Cost');
        $this->tripCosts[] = ['name' => '', 'amount' => 0];
    }

    // Optimized to achieve O(1) time complexity 
    // was previously O(n) due to re-indexing

    public function removeCost($index){

        if(isset($this->tripCosts[$index])){
            unset($this->tripCosts[$index]);
        
            
        try{
            $tripModel = TripsModel::findOrFail($tripID);
            $tripModel->tripCosts = $this->tripCosts;
            $tripModel->save();

            $this->purgeCache((string) $this->tripID);
        }

        catch(\Exception $e){
            \Log::error('An error occurred while removing a trip cost: '.$e->getMessage().' This error occurred in class: '.__CLASS__. ' in method: '.__FUNCTION__. ' on line: '.__LINE__);
        }
    }

    else{
        \Log::error('Index: '.$index. ' is not valid for removal');
    }
}

    public function replaceImage($index)
    {
        \Log::info('Ensuring that index ' . $index . ' is valid..');
    
        // Ensure the index is valid
        if ($index === null || !isset($this->tripPhotos[$index])) {
            $this->addError('tripPhotos.' . $index, 'Invalid image index.');
            \Log::info('Index ' . $index . ' is not valid');
            return;
        }
    
        \Log::info('Index ' . $index . ' is valid!');
    
        // Validate the uploaded file
        $file = $this->tripPhotos[$index];
        \Log::info('Validating file: '.$file.'...');
    
    
        $this->validate([
            'tripPhotos.' . $index => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $this->purgeCache((string) $this->trip->tripID);

    
        \Log::info('File validated successfully!');
        \Log::info('File type after validation: ' . get_class($file));
    
        $filePath = 'booking_photos/'.$file->hashName() . '.' . $file->extension();

        $storagePath = Storage::disk('public')->path($filePath);
   
        // Store the new image URL
        $imageURLs[] = asset(Storage::url($filePath));
      //  $imagesArray[] = $fileName;
       // \Log::info('Appended image to the imageURLs array: ' . json_encode($imageURLs));
    
        // Remove the old image if it exists
        $tripPhotos = json_decode($this->trip->tripPhoto, true);
        $oldImage = basename($tripPhotos[$index]);
    
        if (\Storage::exists('public/booking_photos/' . $oldImage)) {
            \Log::info('Found old image! Deleting old image...');
            \Storage::delete('public/booking_photos/' . $oldImage);
            \Log::info('Image deleted from the server!');
        }
    
        // Update image path in the database
        $tripPhotos[$index] = \Storage::url($filePath);
        $this->trip->tripPhoto = json_encode($tripPhotos);
        $this->trip->save();
        Cache::put($this->cacheKey, [
            'tripPhotos'=>$tripPhotos 
        ]);
    
        \Log::info('Image updated!');
    
        // Update the component property with the new image
        $this->tripPhotos = $tripPhotos;
    
        // Emit an event to notify that the image was replaced successfully
        $this->imageReplaceSuccess = 'Image replaced!';
    
        // Clear the input after successful replacement (optional)
        unset($this->tripPhotos[$index]);
    }
    
    
        
        public function setReplaceIndex($index)
        {
            $this->replaceIndex = $index;
        }
        
        public function selectImageToReplace($index)
        {
            $this->setReplaceIndex($index);
        }
        
    
        public function removeImage($index)
        {
            
            // $tripPhotos = json_decode($this->trip->tripPhoto, true);
            $tripPhotos = is_string($this->trip->tripPhoto) ? json_decode($this->trip->tripPhoto, true) : $this->trip->tripPhoto;
            // Remove the selected image
            if (isset($tripPhotos[$index])) {
                $oldImage = $tripPhotos[$index];
                if (Storage::exists('public/' . basename($oldImage))) {
                    Storage::delete('public/' . basename($oldImage));
                }
                unset($tripPhotos[$index]);
                $tripPhotos = array_values($tripPhotos); // Re-index the array
    
                \Log::info('Invalidating cache..');

                $this->purgeCache((string) $this->trip->tripID);
                \Log::info('Cache invalidated!');

                // Update trip photos and save
                $this->trip->tripPhoto = json_encode($tripPhotos);
                $this->trip->save();
                $this->tripPhotos = $tripPhotos;

                \Log::info('Adding new photos to cache');
                
                Cache::put($this->cacheKey, [
                    'tripPhotos'=>$tripPhotos 
                ]);

                $this->imageReplaceSuccess = 'Image removed successfully!';
            }
            else{
                $this->imageReplaceError = 'Unable to remove image';
            }
        }


        public function rules(){
        // Validate input data
            $rules = [
                'tripLocation' => 'required|string|max:255',
                'tripLandscape' => 'required|array',
                'tripAvailability' => 'required|string',
                'tripDescription' => 'required|string',
                'tripActivities' => 'required|string',
                'tripCosts' => 'nullable|array',
                'tripCosts.*.name' => 'required|string|max:255',
                'tripCosts.*.amount' => 'required|numeric|min:0',
                'num_trips' => 'required|min:1',
            ];
        
            if (!in_array($this->tripAvailability, ['coming soon', 'unavailable'])) {
                $rules['tripPrice'] = 'required|numeric|min:1';
                $rules['tripStartDate'] = 'required|date|before_or_equal:tripEndDate';
                $rules['tripEndDate'] = 'required|date|after_or_equal:tripStartDate';
            }
        
            if (empty($this->trip->tripPhoto)) {
                $rules['tripPhotos.*'] = 'array|max:6';
                $rules['tripPhotos.*'] = 'image|mimes:jpeg,png,jpg|max:2048';
            }
        }
        
        protected $messages = [
            'tripLocation.required' => 'Trip location is required',
            'tripLocation.max'=> 'Trip location name cannot exceed 255 characters',
            'tripLandscape.required'=>'Trip landscape(s) are required',
            'tripAvailability.required'=>'Trip availability is required',
            'tripDescription.required'=>'Trip description is required',
            'tripActivities.required'=>'Trip activities are required',
            'num_trips.required'=>'Please enter a number for the number of slots you want to make available for this trip',

        ];
       
        public function editTrip(): void
        {
            

            
        
            $this->validate();
        
            try {
                $tripModel = TripsModel::findOrFail($this->trip->tripID);
        
                $newImageURLs = [];
                foreach ($this->tripPhotos as $photo) {
                    if ($photo instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = 'booking_photos/' . $photo->hashName();
                        $photo->storeAs('public', $imagePath);
                        $newImageURLs[] = asset(Storage::url($imagePath));
                    }
                }
        
                // if ($tripModel->tripAvailability !== $this->tripAvailability && strtolower($this->tripAvailability) === 'available') {
                //     $reservations = Reservations::where('tripID', $this->trip->tripID)->get();
                //     foreach ($reservations as $reservation) {
                //         Notification::route('mail', $reservation->email)
                //             ->notify(new TripAvailableNotification($this->trip, $reservation->reservationID, $reservation->customerName));
                //     }
                // }
        
                // $accessToken = env('SQUARE_ACCESS_TOKEN');
                // $client = new \Square\SquareClient([
                //     'accessToken' => $accessToken,
                //     'environment' => \Square\Environment::SANDBOX,
                // ]);
        
                // $catalogApi = $client->getCatalogApi();
                // $retrieveResponse = $catalogApi->retrieveCatalogObject($this->trip->tripID);
        
                // if ($retrieveResponse->isSuccess()) {
                //     $catalogObject = $retrieveResponse->getResult()->getObject();
                //     $version = $catalogObject->getVersion();
        
                //     $itemData = $catalogObject->getItemData();
                //     $itemData->setName($this->tripLocation);
                //     $itemData->setDescription(strip_tags($this->tripDescription));
                //     $itemData->setAbbreviation(substr($this->tripLocation, 0, 2));
        
                //     $variations = $itemData->getVariations();
                //     if (isset($this->tripPrice) && is_numeric($this->tripPrice) && $this->tripPrice > 0) {
                //          // Retrieve the variations from the item data
                //         $variation = $variations[0]->getItemVariationData();
                        
                //         // Ensure that the variation's price is set
                //         $priceMoney = new \Square\Models\Money($this->tripPrice * 100, 'USD');
                //         $variation->setPriceMoney($priceMoney);
                //         \Log::info('Setting price for variation: ' . $priceMoney->getAmount());

                //     }

                //     else {
                //         $this->error = 'Invalid or missing trip price.';
                //         \Log::error('Invalid trip price: ' . $this->tripPrice);
                //         return;
                //     }
        
                //     $idempotencyKey = (string) Str::uuid();
                //     $upsertRequest = new \Square\Models\UpsertCatalogObjectRequest($idempotencyKey, $catalogObject);
                //     $upsertResponse = $catalogApi->upsertCatalogObject($upsertRequest);
        
                    // if ($upsertResponse->isSuccess()) {
                        $tripModel->update([
                            'tripLocation' => $this->tripLocation,
                            'tripPhoto' => !empty($newImageURLs) ? json_encode($newImageURLs) : $tripModel->tripPhoto,
                            'tripLandscape' => json_encode($this->tripLandscape),
                            'tripAvailability' => $this->tripAvailability,
                            'tripDescription' => $this->tripDescription,
                            'tripActivities' => $this->tripActivities,
                            'tripStartDate' => $this->tripStartDate ? Carbon::parse($this->tripStartDate)->format('Y-m-d') : $tripModel->tripStartDate,
                            'tripEndDate' => $this->tripEndDate ? Carbon::parse($this->tripEndDate)->format('Y-m-d') : $tripModel->tripEndDate,
                            'tripPrice' => $this->tripPrice ?? $tripModel->tripPrice,
                            'num_trips' => max($this->num_trips, Reservations::where('tripID', $this->trip->tripID)->count()),
                            'active' => $this->active,
                            'slug' => Str::slug($this->tripLocation),
                            'tripCosts' => json_encode($this->tripCosts),
                        ]);
        
                        Cache::put($this->cacheKey, $tripModel->toArray(), 600);
                        $this->success = 'Trip information updated successfully!';
                    // } else {
                    //     $this->error = 'Failed to update trip in Square.';
                    //     \Log::error('Square Error: ', $upsertResponse->getErrors());
                    // }
                // } else {
                //     $this->error = 'Failed to retrieve trip from Square.';
                //     \Log::error('Square Retrieve Error: ', $retrieveResponse->getErrors());
                // }
            } catch (\Exception $e) {
                $this->error = 'An error occurred while updating the trip.';
                \Log::error($e->getMessage());
            }
        }
        

    private function purgeCache(string $tripID): void
    {
        \Log::info('Purging cache for trip ID: trip_'.$tripID);
       $forget = Cache::forget('trip_' . $tripID);
       if($forget){
        \Log::info('Cache purged for trip ID: trip_'.$tripID);
        \Log::info('Cache info now:'.Cache::get('trip_'.$tripID));

        }
       else{
        \Log::error('error purging cache for trip ID: trip_'.$tripID);
       }
    }


//     public function createDiscount()
// {
//     try {
//         $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
//         $trip = TripsModel::findOrFail($this->trip->tripID);

//         // Validation rules
//         $rules = [
//             'discountType' => 'required|in:percentage,amount',
//             'discountDuration' => 'required|integer|min:1|max:12',
//             'discountValue' => 'required|numeric',
//             'promotionCode' => 'nullable|string'
//         ];

//         $this->validate($rules, [
//             'discountType.required' => 'You must choose the discount type',
//             'discountType.in' => 'The discount type must be either percentage or amount',
//             'discountDuration.required' => 'Please provide the duration of this discount in months.',
//             'discountDuration.min' => 'Duration must be at least 1 month',
//             'discountDuration.max' => 'Duration must be a max of 12 months',
//             'discountValue.required' => 'You must enter the discount amount',
//             'discountValue.numeric' => 'Discount value must be numeric',
//         ]);

//         if ($this->discountType === 'percentage' && ($this->discountValue < 1 || $this->discountValue > 100)) {
//             $this->discountCreateError = 'Discount percentage must be between 1% and 100%';
//             return;
//         } elseif ($this->discountType === 'amount' && $this->discountValue <= 0) {
//             $this->discountCreateError = 'Amount discounted must be greater than $0';
//             return;
//         }

//         // Prepare coupon data
//         $couponData = $this->discountType === 'percentage' 
//             ? ['percent_off' => round($this->discountValue, 2), 'duration' => 'repeating', 'duration_in_months' => $this->discountDuration] 
//             : ['amount_off' => intval($this->discountValue * 100), 'currency' => 'usd', 'duration' => 'repeating', 'duration_in_months' => $this->discountDuration];

//         // Check for existing coupon
//         $existingCoupons = $stripe->coupons->all(['limit' => 100]);

//         // Replaced foreach loop with laravel collect() as it is more memory efficient
//         $coupon = collect($existingCoupons->data)->first(fn($c) => 
//             ($this->discountType === 'percentage' && isset($c->percent_off) && $c->percent_off == $couponData['percent_off']) ||
//             ($this->discountType === 'amount' && isset($c->amount_off) && $c->amount_off == $couponData['amount_off'])
//         );

//         if (!$coupon) {
//             $coupon = $stripe->coupons->create($couponData);
//             \Log::info('Coupon created in Stripe with ID: ' . $coupon->id);
//         } else {
//             \Log::info('Using existing coupon: ' . $coupon->id);
//         }

//         // Check for existing promotion code
//         $existingPromoCodes = $stripe->promotionCodes->all(['limit' => 100]);
//         $promoCode = collect($existingPromoCodes->data)->first(fn($p) => $p->coupon === $coupon->id);

//         if (!$promoCode) {
//             $promoCode = $stripe->promotionCodes->create([
//                 'coupon' => $coupon->id,
//                 'code' => $this->promotionCode ?: strtoupper(Str::random(8)),
//             ]);
//             \Log::info('Promotion code created successfully: ' . $promoCode->id);
//         } else {
//             \Log::info('Using existing promotion code: ' . $promoCode->id);
//         }

//         // Update trip model
//         // $trip->stripe_coupon_id = $coupon->id;
//         // $trip->stripe_promo_id = $promoCode->id;
//         $trip->save();

//         // Purge old cache after successful update
//         $this->purgeCache($this->trip->tripID);

//         $this->discountCreateSuccess = 'Discount created successfully!';
//     } catch (Stripe\Exception\ApiErrorException $e) {
//         \Log::error('Error creating discount: ' . $e->getMessage());
//         $this->discountCreateError = 'Failed to create discount. Something went wrong';
//     }
// }

    

//     public function deleteCoupon()
// {
//     $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
//     $trip = TripsModel::findOrFail($this->trip->tripID);
//     $couponID = $trip->stripe_coupon_id;
    
//     try {
//         if (!empty($couponID)) {  
//             $deleteCouponStripe = $stripe->coupons->delete($couponID, []);
            
//             if ($deleteCouponStripe) {
//                 $trip->stripe_coupon_id = '';
//                 $deleteCouponDB = $trip->save();

//                 if ($deleteCouponDB) {
//                     // Purge cache after successful deletion
//                     $this->purgeCache($this->trip->tripID);
//                     $this->couponDeleteSuccess = 'The coupon has been deleted and is no longer available';
//                 } else {
//                     $this->couponDeleteError = 'Unable to delete the coupon from the database.';
//                 }
//             } else {
//                 $this->couponDeleteError = 'Unable to delete the coupon from Stripe.';
//             }
//         } else {
//             $this->couponDeleteError = 'Coupon ID is missing.';
//             \Log::error('Unable to delete coupon because couponID is missing.');
//         }
//     } catch (\Exception $e) {
//         \Log::error('Unable to delete coupon. Error: ' . $e->getMessage());
//         $this->couponDeleteError = 'Unable to delete the coupon, something went wrong!';
//     }
// }

    
    

    public function render()
    {
        return view('livewire.forms.edit-trip-form');
    }
}