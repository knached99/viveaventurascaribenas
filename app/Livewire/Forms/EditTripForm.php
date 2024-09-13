<?php 
namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\TripsModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Stripe\StripeClient;
use Exception;
use Carbon\Carbon;

class EditTripForm extends Component
{
    use WithFileUploads;

    public $trip;
    public string $tripLocation = '';
    public string $tripLandscape = ''; 
    public ?array $tripPhotos = [];
    public string $tripAvailability = ''; 
    public string $tripDescription = ''; 
    public string $tripActivities = ''; 
    public string $tripStartDate = ''; 
    public string $tripEndDate = ''; 
    public string $tripPrice = '';
    public string $num_trips = '';
    public bool $active = false;
    //public array $tripCosts = ['name'=> '', 'amount'=>''];
    public $tripCosts = [];


    public string $success = '';
    public string $imageReplaceSuccess = '';
    public string $imageReplaceError = '';
    public string $error = '';
    public ?int $replaceIndex = null;
    public string $totalNetCost = '';
    public string $grossProfit = '';
    public string $netProfit = '';
    

 


    public function mount($trip)
    {
        $this->trip = $trip;
    
        // Load trip data
        $this->tripLocation = $trip->tripLocation;
        $this->tripPhotos = $trip->tripPhoto ? json_decode($trip->tripPhoto, true) : [];
        $this->tripLandscape = $trip->tripLandscape;
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

        
        $totalNetCost = array_reduce($this->tripCosts, function($carry, $cost){
            return $carry + (float) $cost['amount'];
        });
        
        try {
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            $stripeProductID = $this->stripe_product_id;
        
            // Use Stripe's search API to retrieve charges with succeeded status
            $charges = $stripe->charges->search([
                'query' => "status:'succeeded'",
                'limit' => 100, // Adjust the limit if needed
            ]);
        
         
        
            // Filter the charges by product ID and ensure they are not refunded
            $filteredCharges = array_filter($charges->data, function ($charge) use ($stripeProductID) {
                // Ensure charge is not refunded and matches the product ID
                return $charge->amount_refunded == 0 && isset($charge->amount_captured) && $charge->amount_captured > 0;
            });
        
        
            // Calculate gross profit based on the captured amount
            $grossProfit = array_reduce($filteredCharges, function ($carry, $charge) {
                return $carry + (float) $charge->amount_captured / 100; // Convert from cents to dollars
            }, 0);
        
            // Calculate net profit
            $netProfit = $grossProfit - $totalNetCost;
        
         
        
            // Pass the calculated values to the frontend
            $this->grossProfit = $grossProfit;
            $this->netProfit = $netProfit;
        
        } catch (\Exception $e) {
        }
     
    }
    
    
        
    
    public function addCost()
    {   \Log::info('Adding Trip Cost');
        $this->tripCosts[] = ['name' => '', 'amount' => 0];
    }

 
    public function removeCost($index)
    {
        \Log::info('Attempting to remove cost at index: ' . $index);
    
        if (isset($this->tripCosts[$index])) {
            // Remove the cost from the Livewire component
            unset($this->tripCosts[$index]);
            $this->tripCosts = array_values($this->tripCosts);
    
            // Remove the cost from the database
            try {
                $tripModel = TripsModel::findOrFail($this->trip->tripID);
                $tripModel->tripCosts = $this->tripCosts;
                $tripModel->save();
    
            } catch (\Exception $e) {
                \Log::error('Error updating trip costs in the database: ' . $e->getMessage());
            }
        } else {
            \Log::error('Invalid index for removal: ' . $index);
        }
    }
    


    
    
    
    


    public function editTrip(): void
    {
        \Log::info('Editing trip with costs: ' . json_encode($this->tripCosts));
    
        $rules = [
            'tripLocation' => 'required|string|max:255',
            'tripLandscape' => 'required|string',
            'tripAvailability' => 'required|string',
            'tripDescription' => 'required|string',
            'tripActivities' => 'required|string',
            'tripStartDate' => 'required|date|before_or_equal:tripEndDate',
            'tripEndDate' => 'required|date|after_or_equal:tripStartDate',
            'tripPrice' => 'required|numeric|min:1',
            'tripCosts' => 'nullable|array',
            'tripCosts.*.name' => 'required|string|max:255',
            'tripCosts.*.amount' => 'required|numeric|min:0',
            'num_trips'=>'required|min:1',
        ];
    
        $this->validate($rules);
    
        try {
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    
            // Retrieve Stripe product
            $product = $stripe->products->retrieve($this->trip->stripe_product_id);
            $tripModel = TripsModel::findOrFail($this->trip->tripID);
    
            if ($product) {
                $product->name = $this->tripLocation;
                $product->description = $this->tripDescription;
    
                // Handle new image uploads (if any)
                if ($this->tripPhotos) {
                    if (count($this->tripPhotos) > 3) {
                        $this->error = 'You cannot upload more than 3 pictures';
                        return;
                    }
    
                    $newImageURLs = [];
                    foreach ($this->tripPhotos as $photo) {
                        if ($photo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                            $imagePath = 'booking_photos/' . time() . '-' . $photo->hashName();
                            $photo->storeAs('public', $imagePath);
                            $newImageURLs[] = asset('storage/' . $imagePath);
                        } else {
                            \Log::error('File is not a valid instance of Livewire TemporaryUploadedFile');
                            $this->addError('tripPhotos', 'Uploaded file is not valid.');
                        }
                    }
    
                    $existingImages = json_decode($tripModel->tripPhoto, true) ?? [];
                    $mergedImages = array_merge($existingImages, $newImageURLs);
    
                    // Update Stripe product images
                    $stripe->products->update($product->id, [
                        'name' => $product->name,
                        'description' => $product->description,
                        'images' => $mergedImages,
                    ]);
    
                    $tripModel->tripPhoto = json_encode($mergedImages);
                }
    
                // Update other trip details
                $tripModel->tripLocation = $this->tripLocation;
                $tripModel->tripLandscape = $this->tripLandscape;
                $tripModel->tripAvailability = $this->tripAvailability;
                $tripModel->tripDescription = $this->tripDescription;
                $tripModel->tripActivities = $this->tripActivities;
                $tripModel->tripStartDate = $this->tripStartDate;
                $tripModel->tripEndDate = $this->tripEndDate;
                $tripModel->tripPrice = $this->tripPrice;
    
                // Ensure tripCosts is an array and properly converted to JSON
                $tripModel->tripCosts = json_encode($this->tripCosts);
                $tripModel->num_trips = $this->num_trips;

                $tripModel->active = $this->active;
    
                $tripModel->save();
    
                $this->success = 'Trip updated successfully!';
            }
        } catch (Exception $e) {
            $this->error = 'Error updating trip: ' . $e->getMessage();
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

    \Log::info('File validated successfully!');
    \Log::info('File type after validation: ' . get_class($file));

    // Generate file path and process
    $filePath = 'booking_photos/' . time() . '-' . $file->hashName();
    $fullPath = storage_path('app/public/' . $filePath);

    \Log::info('File Path: ' . $filePath);
    \Log::info('Full Path: ' . $fullPath);

    // Resize the image using GD or another method
    //$this->resizeImage($file->getRealPath(), $fullPath, 350, 219);

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

            // Update trip photos and save
            $this->trip->tripPhoto = json_encode($tripPhotos);
            $this->trip->save();
            $this->tripPhotos = $tripPhotos;

            $this->success = 'Image removed successfully!';
        }
    }

    private function resizeImage($sourcePath, $destinationPath, $newWidth, $newHeight) {
        $imageType = exif_imagetype($sourcePath);
    
        // Create the original image based on its type
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($sourcePath);
                break;
            default:
                throw new Exception('Unsupported image type');
        }
    
        // Get the original image dimensions
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);
    
        // Create the resized image canvas with transparency support for PNG and GIF
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    
        if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }
    
        // Resample the image to the new dimensions
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
    
        // Save the resized image with the appropriate quality/compression settings
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $quality = 90; // Adjust the quality level (90 is high quality, can go up to 100)
                imagejpeg($resizedImage, $destinationPath, $quality);
                break;
            case IMAGETYPE_PNG:
                $compression = 2; // Adjust the compression level (0 for no compression, 9 for max compression)
                imagepng($resizedImage, $destinationPath, $compression);
                break;
            case IMAGETYPE_GIF:
                imagegif($resizedImage, $destinationPath);
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