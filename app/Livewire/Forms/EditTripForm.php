<?php 
namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;
use App\Models\TripsModel;
use App\Models\Reservations;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Stripe\StripeClient;
use Stripe\Coupon;
use Stripe\PromotionCode;
use Exception;
use Stripe\Exception\ApiErrorException;
use Carbon\Carbon;

// Dispatch events and notifications to users for trip availability status updates
use App\Events\TripBecameAvailable;
use App\Listeners\SendNotificationOfTripAvailability;
use App\Notifications\TripAvailabilityNotification;

// Helper class for image resizing 
Use App\Helpers\Helper; 

class EditTripForm extends Component
{
    use WithFileUploads;

    public $trip;

    public ?string $couponID = '';
    public ?string $promoID = '';

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
    private string $stripe_product_id = '';
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
        $this->tripPhotos = isset($cachedTrip['tripPhotos']) ? json_decode($cachedTrip['tripPhotos'], true) : []; // This should be an array of URLs or paths
        $this->tripLandscape = $cachedTrip['tripLandscape'];
        $this->tripAvailability = $cachedTrip['tripAvailability'];
        $this->tripDescription = $cachedTrip['tripDescription'];
        $this->tripActivities = $cachedTrip['tripActivities'];
        $this->tripStartDate = $cachedTrip['tripStartDate'];
        $this->tripEndDate = $cachedTrip['tripEndDate'];
        $this->tripPrice = $cachedTrip['tripPrice'];
        $this->stripe_product_id = $cachedTrip['stripe_product_id'];
        $this->stripe_coupon_id = $cachedTrip['stripe_coupon_id'];
        $this->stripe_promo_id = $cachedTrip['stripe_promo_id'];
        $this->tripCosts = $cachedTrip['tripCosts'];
        $this->num_trips = $cachedTrip['num_trips'];
        $this->active = $cachedTrip['active'];
        $this->slug = $cachedTrip['slug'] ?? '';
        $this->stripe_coupon_id = $cachedTrip['stripe_coupon_id'];
        $this->stripe_promo_id = $cachedTrip['stripe_promo_id'];
    

    }
    
    private function loadFromDatabase(): void
    {
        $trip = TripsModel::findOrFail($this->trip->tripID);
        
        $this->tripLocation = $trip->tripLocation;
        $this->tripPhotos = json_decode($trip->tripPhoto, true); // true converts JSON to array

        $this->tripLandscape = $trip->tripLandscape ? json_decode($trip->tripLandscape, true) : [];
        $this->tripAvailability = $trip->tripAvailability;
        $this->tripDescription = $trip->tripDescription;
        $this->tripActivities = $trip->tripActivities;
        $this->tripStartDate = Carbon::parse($trip->tripStartDate)->format('Y-m-d');
        $this->tripEndDate = Carbon::parse($trip->tripEndDate)->format('Y-m-d');
        $this->tripPrice = $trip->tripPrice;
        $this->stripe_product_id = $trip->stripe_product_id;
        $this->stripe_coupon_id = $trip->stripe_coupon_id;
        $this->stripe_promo_id = $trip->stripe_promo_id;
        $this->tripCosts = json_decode($trip->tripCosts, true);
        $this->num_trips = $trip->num_trips;
        $this->active = (bool) $trip->active;
        $this->slug = $trip->slug;
        $this->stripe_promo_id = $trip->stripe_promo_id;
        $this->stripe_coupon_id = $trip->stripe_coupon_id;
    
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
            'stripe_product_id' => $this->stripe_product_id,
            'stripe_coupon_id'=> $this->stripe_coupon_id ?? '',
            'stripe_promo_id' => $this->stripe_promo_id ?? '',
            'tripCosts' => $this->tripCosts,
            'num_trips' => $this->num_trips,
            'active' => $this->active,
            'stripe_coupon_id' => $this->stripe_coupon_id,
            'stripe_promo_id' => $this->stripe_promo_id,    
            
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
        \Log::info('Validating file...');
    
        if (!$file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            \Log::error('File is not a valid instance of Livewire TemporaryUploadedFile');
            $this->addError('tripPhotos.' . $index, 'Uploaded file is not valid.');
            return;
        }
    
        $this->validate([
            'tripPhotos.' . $index => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $this->purgeCache((string) $this->trip->tripID);

    
        \Log::info('File validated successfully!');
        \Log::info('File type after validation: ' . get_class($file));
    
        // Generate file path and process
        //$filePath = 'booking_photos/' . time() . '-' . $file->hashName() . '.'.$file->extension();
     //   $storagePath = Storage::disk('public')->path($filePath);
    
   //     \Log::info('File Path: ' . $filePath);
     //   \Log::info('Stored file path: ' . Storage::disk('public')->path($filePath));

       // $photo->storeAs('booking_photos', $fileName, 'public');
        // $filePath = 'booking_photos/' . $fileName;
        // $fullPath = storage_path('app/public/' . $filePath);
        $filePath = 'booking_photos/'.$photo->hashName() . '.' . $photo->extension();

        $storagePath = Storage::disk('public')->path($filePath);
      //  \Log::info('Resizing Image...');
      //  Helper::resizeImage($image, $fullPath, 525, 351);

        // Save the image to the file system
        // $photo->storeAs('booking_photos', $fileName, 'public');
  
        // // $imageURLs[] = asset(Storage::url($filePath)); 
        // // $imagesArray[] = $fileName; 
        // \Log::info('Current image URLs array: ' . json_encode($imageURLs));
        // \Log::info('Current images in the array: ' . json_encode($imagesArray));
    

    //   // Resize the image and save it using the public disk
    //     Helper::resizeImage(
    //         $file->getRealPath(),
    //         $storagePath, // Use the public disk path for Hostinger
    //         525,
    //         351
    //     );
    
        //\Log::info('Image resized!');
    
        // Store the new image URL
        $imageURLs[] = asset(Storage::url($filePath));
        $imagesArray[] = $fileName;
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
            
            $tripPhotos = json_decode($this->trip->tripPhoto, true);
    
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


 
    


    public function editTrip(): void
    {
        $reservationsCount = Reservations::count(); 
        
        \Log::info('Editing trip with costs: ' . json_encode($this->tripCosts));
        
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

        if(!in_array($this->tripAvailability, ['coming soon', 'unavailable'])){
            $rules['tripPrice'] = 'required|numeric|min:1';
            $rules['tripStartDate'] = 'required|date|before_or_equal:tripEndDate';
            $rules['tripEndDate'] = 'required|date|after_or_equal:tripStartDate';
        }
        
        $this->validate($rules);
        
        try {
            $this->purgeCache((string) $this->trip->tripID);

            $imagesArray = [];
            $imageURLs = [];
            
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            $product = $stripe->products->retrieve($this->trip->stripe_product_id);
            $tripModel = TripsModel::findOrFail($this->trip->tripID);
        
            if ($product) {
                $product->name = $this->tripLocation;
                $product->description = $this->tripDescription;
                \Log::info('Images in the array: '.json_encode($imagesArray));
                \Log::info('Image URLs in the array" '.json_encode($imageURLs));


                //$newImageURLs = [];

              //  \Log::info('Current Image URLs array: '.json_encode($newImageURLs));

            
                //$newImageURLs = [];

            
                if (!empty($this->tripPhotos) && is_array($this->tripPhotos)) {
                        \Log::info('User selected new pictures for upload. Iterating over new pictures..');
                        
                        foreach ($this->tripPhotos as $photo) {
                            \Log::info('Checking if user selected pictures');

                            if ($photo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) { 
                                $image = $photo->getRealPath();
                                $fileName = $photo->hashName() . '.' . $photo->extension();
                                // $filePath = 'booking_photos/' . $fileName;
                                // $fullPath = storage_path('app/public/' . $filePath);
                                $filePath = 'booking_photos/'.$fileName; 

                                $storagePath = Storage::disk('public')->path($filePath);
                              //  \Log::info('Resizing Image...');
                              //  Helper::resizeImage($image, $fullPath, 525, 351);
                    
                                // Save the image to the file system
                                $photo->storeAs('booking_photos', $fileName, 'public');
                          
                                $imageURLs[] = asset(Storage::url($filePath)); 
                                $imagesArray[] = $fileName; 
                                \Log::info('Current image URLs array: ' . json_encode($imageURLs));
                                \Log::info('Current images in the array: ' . json_encode($imagesArray));
                            }
                        }
                        
            
                    }
                    
                
                // \Log::info('Current newImageURLs array: '.json_encode($newImageURLs));
               
                \Log::info('Current trip availability in DB: ' . $tripModel->tripAvailability);
                \Log::info('Current trip availability in Livewire: ' . $this->tripAvailability);
                if ($tripModel->tripAvailability !== $this->tripAvailability) {
                    \Log::info('Trip availability has changed.');
                    if (strtolower($this->tripAvailability) === 'available') {
                        \Log::info('Dispatching TripBecameAvailable event for trip ID: ' . $tripModel->tripID);
                        event(new TripBecameAvailable($tripModel));
                    }
                    
                }
        
                $tripModel->tripLocation = $this->tripLocation;
                $tripModel->tripPhoto = json_encode($imagesArray);
               // $tripModel->tripPhoto = !empty($newImageURLs) ? json_encode($newImageURLs) : json_encode($this->tripPhotos);
                $tripModel->tripLandscape = json_encode($this->tripLandscape);
                $tripModel->tripAvailability = $this->tripAvailability;
                $tripModel->tripDescription = $this->tripDescription;
                $tripModel->tripActivities = $this->tripActivities;
                $tripModel->tripStartDate = Carbon::parse($this->tripStartDate)->format('Y-m-d');
                $tripModel->tripEndDate = Carbon::parse($this->tripEndDate)->format('Y-m-d');
                $tripModel->tripPrice = $this->tripPrice ?? 0;
                $tripModel->num_trips = $this->num_trips;
                
                if($reservationsCount > 0 && $tripModel->num_trips == 0 || $tripModel->num_trips < $reservationsCount){
                 $tripModel->num_trips = max($reservationsCount, $tripModel->num_trips);
                }
                else{
                    $tripModel->num_trips = $this->num_trips;
                }
                
                // $tripModel->num_trips = ($tripModel->num_trips == 0 || $tripModel->num_trips < $reservationsCount) 
                // ? max($reservationsCount, $tripModel->num_trips) 
                // : $tripModel->num_trips;

                $tripModel->active = $this->active;
                $tripModel->slug = Str::slug($this->tripLocation);
                $tripModel->tripCosts = json_encode($this->tripCosts);
        
                $tripModel->save();

                Cache::put($this->cacheKey, [
                    'tripLocation' => $this->tripLocation,
                    'tripPhotos' => $imagesArray,
                    'tripLandscape' => $this->tripLandscape,
                    'tripAvailability' => $this->tripAvailability,
                    'tripDescription' => $this->tripDescription,
                    'tripActivities' => $this->tripActivities,
                    'tripStartDate' => $this->tripStartDate,
                    'tripEndDate' => $this->tripEndDate,
                    'tripPrice' => $this->tripPrice,
                    'stripe_product_id' => $this->stripe_product_id,
                    'tripCosts' => $this->tripCosts,
                    'num_trips' => $this->num_trips,
                    'active' => $this->active,
                    'slug'=> Str::slug($this->slug),
                ], 600); // Cache for 10 minutes
        
                $this->success = 'Trip details have been updated successfully.';
            } else {
                $this->error = 'Product not found.';
            }
        } catch (Exception $e) {
            \Log::error('Error updating trip: ' . $e->getMessage());
            $this->error = 'There was an error updating the trip.';
        }
    }
    

    private function purgeCache(string $tripId): void
    {
        \Log::info('Invalidating cache for trip ID: ' . $tripId);
        Cache::forget('trip_' . $tripId);
    }



    // Create a coupon to apply discounts to a specific destination pacakge 
  

    public function createDiscount()
    {
        try {
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    
            $trip = TripsModel::findOrFail($this->trip->tripID);
    
            // Validation rules
            $rules = [
                'discountType' => 'required|in:percentage,amount',
                'discountDuration'=>'required|integer|min:1|max:12',
                'discountValue' => 'required|numeric',
                'promotionCode' => 'nullable|string'
            ];
    
            $validationMessages = [
                'discountType.required' => 'You must choose the discount type',
                'discountType.in' => 'The discount type must be either percentage or amount',
                'discountDuration.required'=>'Please provide the duration of this discount in months. (Example 1 = 1 month)',
                'discountDuration.min'=>'Duration must be at least 1 month',
                'discountDuration.max'=>'Duration must be a max of 12 months',
                'discountValue.required' => 'You must enter the discount amount',
                'discountValue.numeric' => 'Discount value must be numeric',
            ];
    
            $this->validate($rules, $validationMessages);
    
            if ($this->discountType === 'percentage' && ($this->discountValue < 1 || $this->discountValue > 100)) {
                $this->discountCreateError = 'Discount percentage must be between 1% and 100%';
                return;
            } elseif ($this->discountType === 'amount' && $this->discountValue <= 0) {
                $this->discountCreateError = 'Amount discounted must be greater than $0';
                return;
            }
    
            // Prepare coupon data based on discount type
            $couponData = [];
            if ($this->discountType === 'percentage') {
                $couponData = [
                    'percent_off' => round($this->discountValue, 2),
                    'duration' => 'repeating',
                    'duration_in_months' => $this->discountDuration,
                ];
            } elseif ($this->discountType === 'amount') {
                $couponData = [
                    'amount_off' => intval($this->discountValue * 100),
                    'currency' => 'usd',
                    'duration' => 'repeating',
                    'duration_in_months' => $this->discountDuration,
                ];
            }
    
            // Get all coupons and find the one that matches the product and discount type
            $existingCoupons = $stripe->coupons->all(['limit' => 100]); // Fetch all coupons
            $coupon = null;
    
            foreach ($existingCoupons->data as $existingCoupon) {
                // Check if coupon matches the discount type and value 
                if (($this->discountType === 'percentage' && isset($existingCoupon->percent_off) && $existingCoupon->percent_off == $couponData['percent_off']) ||
                    ($this->discountType === 'amount' && isset($existingCoupon->amount_off) && $existingCoupon->amount_off == $couponData['amount_off'])) {
                    $coupon = $existingCoupon;
                    break;
                }
            }
    
            if (!$coupon) {
                // Create a new coupon if none exists
                $coupon = $stripe->coupons->create($couponData);
                \Log::info('Coupon created in Stripe with ID: ' . $coupon->id);
            } else {
                \Log::info('Using existing coupon: ' . $coupon->id);
            }
    
            // Get all promotion codes and check if one exists for the coupon
            $existingPromoCodes = $stripe->promotionCodes->all(['limit' => 100]); // Fetch all promo codes
            $promoCode = null;
    
            foreach ($existingPromoCodes->data as $existingPromoCode) {
                if ($existingPromoCode->coupon === $coupon->id) {
                    $promoCode = $existingPromoCode;
                    break;
                }
            }
    
            if (!$promoCode) {
                // Create a new promotion code if none exists
                $promoCode = $stripe->promotionCodes->create([
                    'coupon' => $coupon->id,
                    'code' => $this->promotionCode ? $this->promotionCode : strtoupper(Str::random(8)),
                ]);
                \Log::info('Promotion code created successfully: ' . $promoCode->id);
            } else {
                \Log::info('Using existing promotion code: ' . $promoCode->id);
            }
    
            // Update trip with Stripe coupon and promo code IDs
            $trip->stripe_coupon_id = $coupon->id;
            $trip->stripe_promo_id = $promoCode->id;
            $trip->save();
    
            $this->discountCreateSuccess = 'Discount created successfully!';
        } catch (Stripe\Exception\ApiErrorException $e) {
            \Log::error('Error creating discount on line ' . __LINE__ . ' in class: ' . __CLASS__ . ' in method: ' . __FUNCTION__ . ' Error: ' . $e->getMessage());
            $this->discountCreateError = 'Failed to create discount. Something went wrong';
        }
    }
    

    public function deleteCoupon()
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $trip = TripsModel::findOrFail($this->trip->tripID);
    
        $couponID = $trip->stripe_coupon_id;
        
        try {
            if (!empty($couponID)) {  // Ensure couponID is not null or empty
                $deleteCouponStripe = $stripe->coupons->delete($couponID, []);
                
                if ($deleteCouponStripe) {
                    $trip->stripe_coupon_id = '';
    
                    $deleteCouponDB = $trip->save();
    
                    $this->couponDeleteSuccess = $deleteCouponDB
                        ? 'The coupon has been deleted and is no longer available'
                        : 'Unable to delete the coupon. Something went wrong in the database.';
                } else {
                    $this->couponDeleteError = 'Unable to delete the coupon from Stripe.';
                }
            } else {
                $this->couponDeleteError = 'Coupon ID is missing.';
                \Log::error('Unable to delete coupon because couponID is missing.');
            }
        } catch (\Exception $e) {
            \Log::error('Unable to delete coupon. This error occurred: '.$e->getMessage());
            $this->couponDeleteError = 'Unable to delete the coupon, something went wrong!';
        }
    }
    
    
    

    public function render()
    {
        return view('livewire.forms.edit-trip-form');
    }
}