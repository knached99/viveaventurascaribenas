<?php 
namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;
use App\Models\TripsModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Stripe\StripeClient;
use Exception;
use Carbon\Carbon;

class EditTripForm extends Component
{
    use WithFileUploads;

    public $trip;
    public string $tripLocation = '';
    public array $tripLandscape = []; 
    public array $tripPhotos = [];
    public string $tripAvailability = ''; 
    public string $tripDescription = ''; 
    public string $tripActivities = ''; 
    public string $tripStartDate = ''; 
    public string $tripEndDate = ''; 
    public string $tripPrice = '';
    public string $num_trips = '';
    public bool $active = false;
    public string $slug = '';
    public $tripCosts = [];
    public ?array $existingImageURLs = [];

    public string $status = ''; // Trip Creation Flash Message
    public string $success = '';
    public string $imageReplaceSuccess = '';
    public string $imageReplaceError = '';
    public string $error = '';
    public ?int $replaceIndex = null;
    public string $totalNetCost = '';
    public string $grossProfit = '';
    public string $netProfit = '';
    private string $stripe_product_id = '';
    private string $cacheKey = '';

    public function mount($trip)
    {
        $this->trip = $trip;
        $this->cacheKey = 'trip_' . $this->trip->tripID;
        
        // Load trip data from cache or database
        $cachedTrip = Cache::get($this->cacheKey);
        
        \Log::info('Is this data cached?');
        if ($cachedTrip) {
            \Log::info('Yes, loading data from cache...');
            $this->loadFromCache($cachedTrip);
            \Log::info('Data loaded!');

            \Log::info(json_encode($cachedTrip));
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
        $this->tripCosts = $cachedTrip['tripCosts'];
        $this->num_trips = $cachedTrip['num_trips'];
        $this->active = $cachedTrip['active'];
        $this->slug = $cachedTrip['slug'] ?? '';
    
        $this->calculateFinancials();
    }
    
    private function loadFromDatabase(): void
    {
        $trip = TripsModel::findOrFail($this->trip->tripID);
        
        $this->tripLocation = $trip->tripLocation;
        $this->tripPhotos = json_decode($trip->tripPhoto, true); // true converts JSON to array

        // $this->tripPhotos = isset($trip->tripPhoto) ? json_decode($trip->tripPhoto, true) : [];
       // $this->tripPhotos = $trip->tripPhoto ? json_decode($trip->tripPhoto, true) : []; // This should be an array of URLs or paths
        $this->tripLandscape = $trip->tripLandscape ? json_decode($trip->tripLandscape, true) : [];
        $this->tripAvailability = $trip->tripAvailability;
        $this->tripDescription = $trip->tripDescription;
        $this->tripActivities = $trip->tripActivities;
        $this->tripStartDate = Carbon::parse($trip->tripStartDate)->format('Y-m-d');
        $this->tripEndDate = Carbon::parse($trip->tripEndDate)->format('Y-m-d');
        $this->tripPrice = $trip->tripPrice;
        $this->stripe_product_id = $trip->stripe_product_id;
        $this->tripCosts = json_decode($trip->tripCosts, true);
        $this->num_trips = $trip->num_trips;
        $this->active = (bool) $trip->active;
        $this->slug = $trip->slug;
    
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
            'tripCosts' => $this->tripCosts,
            'num_trips' => $this->num_trips,
            'active' => $this->active,
        ], 600); // Cache for 10 minutes
    
        $this->calculateFinancials();
    }
    
    private function calculateFinancials(): void
    {
        $totalNetCost = array_reduce($this->tripCosts, function($carry, $cost) {
            return $carry + (float) $cost['amount'];
        });

        try {
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            $charges = Cache::remember('charges_' . $this->stripe_product_id, 600, function() use ($stripe) {
                return $stripe->charges->search([
                    'query' => "status:'succeeded'",
                    'limit' => 100,
                ]);
            });

            $filteredCharges = array_filter($charges->data, function ($charge) {
                return $charge->amount_refunded == 0 && isset($charge->amount_captured) && $charge->amount_captured > 0;
            });

            $grossProfit = array_reduce($filteredCharges, function ($carry, $charge) {
                return $carry + (float) $charge->amount_captured / 100;
            }, 0);

            $netProfit = $grossProfit - $totalNetCost;

            $this->grossProfit = $grossProfit;
            $this->netProfit = $netProfit;

        } catch (Exception $e) {
            \Log::error('Error encountered: '.$e->getMessage());
        }
    }

    public function addCost()
    {
        \Log::info('Adding Trip Cost');
        $this->tripCosts[] = ['name' => '', 'amount' => 0];
    }

    public function removeCost($index)
    {
        \Log::info('Attempting to remove cost at index: ' . $index);

        if (isset($this->tripCosts[$index])) {
            unset($this->tripCosts[$index]);
            $this->tripCosts = array_values($this->tripCosts);

            try {
                $tripModel = TripsModel::findOrFail($this->trip->tripID);
                $tripModel->tripCosts = $this->tripCosts;
                $tripModel->save();
                $this->invalidateCache((string) $this->trip->tripID);
            } catch (\Exception $e) {
                \Log::error('Error updating trip costs in the database: ' . $e->getMessage());
            }
        } else {
            \Log::error('Invalid index for removal: ' . $index);
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

        $this->invalidateCache((string) $this->trip->tripID);

    
        \Log::info('File validated successfully!');
        \Log::info('File type after validation: ' . get_class($file));
    
        // Generate file path and process
        $filePath = 'booking_photos/' . time() . '-' . $file->hashName(). '.'.$file->extension();
        $fullPath = storage_path('app/public/' . $filePath);
    
        \Log::info('File Path: ' . $filePath);
        \Log::info('Full Path: ' . $fullPath);
    
        // Resize the image using GD or another method
        $this->resizeImage($file->getRealPath(), $fullPath,  525, 351);
    
        \Log::info('Image resized!');
    
        // Store the new image URL
        $imageURLs[] = asset(Storage::url($filePath));
        \Log::info('Appended image to the imageURLs array: ' . json_encode($imageURLs));
    
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

                $this->invalidateCache((string) $this->trip->tripID);
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
        \Log::info('Editing trip with costs: ' . json_encode($this->tripCosts));
        
        $rules = [
            'tripLocation' => 'required|string|max:255',
            // 'tripPhotos.*'=>'sometimes|array|max:3',
            // 'tripPhotos' => 'nullable|array|max:3', // Ensure tripPhotos is an array with a max of 3 items
            // 'tripPhotos.*' => 'image|mimes:jpg,jpeg,png|max:5120', // Validate image types and max size (2MB
            'tripLandscape' => 'required|array',
            'tripAvailability' => 'required|string',
            'tripDescription' => 'required|string',
            'tripActivities' => 'required|string',
            'tripStartDate' => 'required|date|before_or_equal:tripEndDate',
            'tripEndDate' => 'required|date|after_or_equal:tripStartDate',
            'tripPrice' => 'required|numeric|min:1',
            'tripCosts' => 'nullable|array',
            'tripCosts.*.name' => 'required|string|max:255',
            'tripCosts.*.amount' => 'required|numeric|min:0',
            'num_trips' => 'required|min:1',
        ];
        
        $this->validate($rules);
        
        try {
            $this->invalidateCache((string) $this->trip->tripID);
            
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            $product = $stripe->products->retrieve($this->trip->stripe_product_id);
            $tripModel = TripsModel::findOrFail($this->trip->tripID);
        
            if ($product) {
                $product->name = $this->tripLocation;
                $product->description = $this->tripDescription;
        
                $newImageURLs = [];

                \Log::info('Current Image URLs array: '.json_encode($newImageURLs));

                \Log::info('');

            
                $newImageURLs = []; 

            
                if (!empty($this->tripPhotos) && is_array($this->tripPhotos)) {
                        \Log::info('User selected new pictures for upload. Iterating over new pictures..');
                        
                        foreach ($this->tripPhotos as $photo) {
                            \Log::info('Checking if user selected pictures');

                            if ($photo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) { 
                                $image = $photo->getRealPath();
                                $fileName = $photo->hashName() . '.' . $photo->extension();
                                $filePath = 'booking_photos/' . $fileName;
                                $fullPath = storage_path('app/public/' . $filePath);
                    
                                \Log::info('Resizing Image...');
                                $this->resizeImage($image, $fullPath, 525, 351);
                    
                                // Save the image to the file system
                                $photo->storeAs('public/booking_photos', $fileName);
                          
                                $newImageURLs[] = asset(Storage::url($filePath)); 
                                \Log::info('Current image URLs array: ' . json_encode($newImageURLs));
                            }
                        }
                        
                        // Merge existing image URLs with new ones, if necessary
                    //    $this->tripPhotosURLs = array_merge($this->existingImageURLs, $newImageURLs);
                        
                      //  \Log::info('All images after upload: ' . json_encode($this->tripPhotosURLs));
                    }
                    
            
                
                \Log::info('Current newImageURLs array: '.json_encode($newImageURLs));
               
        
        
                $tripModel->tripLocation = $this->tripLocation;
                $tripModel->tripPhoto = !empty($newImageURLs) ? json_encode($newImageURLs) : json_encode($this->tripPhotos);
                $tripModel->tripLandscape = json_encode($this->tripLandscape);
                $tripModel->tripAvailability = $this->tripAvailability;
                $tripModel->tripDescription = $this->tripDescription;
                $tripModel->tripActivities = $this->tripActivities;
                $tripModel->tripStartDate = Carbon::parse($this->tripStartDate)->format('Y-m-d');
                $tripModel->tripEndDate = Carbon::parse($this->tripEndDate)->format('Y-m-d');
                $tripModel->tripPrice = $this->tripPrice;
                $tripModel->num_trips = $this->num_trips;
                $tripModel->active = $this->active;
                $tripModel->slug = Str::slug($this->tripLocation);
                $tripModel->tripCosts = json_encode($this->tripCosts);
        
                $tripModel->save();
        
                Cache::put($this->cacheKey, [
                    'tripLocation' => $this->tripLocation,
                    'tripPhotos' => $newImageURLs,
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
    

    private function invalidateCache(string $tripId): void
    {
        \Log::info('Invalidating cache for trip ID: ' . $tripId);
        Cache::forget('trip_' . $tripId);
    }

    private function resizeImage($sourcePath, $destinationPath, $newWidth, $newHeight) {
        $imageType = exif_imagetype($sourcePath);
    
        switch ($imageType) {
            case IMAGETYPE_JPEG: 
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($sourcePath);
                break;
            default:
                throw new Exception('The image you selected is not supported. Please select a JPEG or PNG image');
        }
    
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);
    
        $aspectRatio = $originalWidth / $originalHeight;
    
        if ($newWidth / $newHeight > $aspectRatio) {
            $newWidth = $newHeight * $aspectRatio;
        } else {
            $newHeight = $newWidth / $aspectRatio;
        }
    
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    
        if ($imageType == IMAGETYPE_PNG) {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }
    
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
    
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $quality = 90; 
                imagejpeg($resizedImage, $destinationPath, $quality);
                break;
            case IMAGETYPE_PNG:
                $compression = 1; // Lowest compression setting
                imagepng($resizedImage, $destinationPath, $compression);
                break;
        }
    
        // Free up memory
        imagedestroy($image);
        imagedestroy($resizedImage);
    }
    
    
    

    public function render()
    {
        return view('livewire.forms.edit-trip-form');
    }
}