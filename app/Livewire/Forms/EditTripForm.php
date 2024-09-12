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
    public array $tripCosts = ['name'=> '', 'amount'=>''];
    
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
        $this->tripCosts = json_decode($trip->tripCosts, true) ?? []; // Initialize as empty array if null
    
        // Calculate total net cost
        $this->totalNetCost = array_reduce($this->tripCosts, function ($carry, $cost) {
            return $carry + (float) $cost['amount']; // Convert amount to float and sum up
        }, 0);
    
        // Calculate financial summary
        try {
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            $charges = $stripe->charges->all(['limit' => 100]); // Adjust limit as needed
            $tripCharges = array_filter($charges->data, function ($charge) {
                return !$charge->refunded && $charge->description === $this->trip->tripLocation;
            });
    
            $this->grossProfit = array_reduce($tripCharges, function ($carry, $charge) {
                return $carry + (float) $charge->amount / 100;
            }, 0);
    
            $this->netProfit = $this->grossProfit - $this->totalNetCost;
        } catch (Exception $e) {
            $this->error = 'Error retrieving charge data: ' . $e->getMessage();
        }
    }
    
    

 
    public function removeCost($index)
    {
        \Log::info('Attempting to remove cost at index: ' . $index);
    
        if (isset($this->tripCosts[$index])) {
            // Remove the cost from the Livewire component
            unset($this->tripCosts[$index]);
            $this->tripCosts = array_values($this->tripCosts);
            \Log::info('Updated trip costs after removal: ' . json_encode($this->tripCosts));
    
            // Remove the cost from the database
            try {
                $tripModel = TripsModel::findOrFail($this->trip->tripID);
                $tripModel->tripCosts = $this->tripCosts;
                $tripModel->save();
    
                \Log::info('Trip costs updated in the database.');
            } catch (\Exception $e) {
                \Log::error('Error updating trip costs in the database: ' . $e->getMessage());
            }
        } else {
            \Log::error('Invalid index for removal: ' . $index);
        }
    }
    

    
    
    public function addCost()
    {
        \Log::info("Adding a new cost");
        $this->tripCosts[] = [
            'name' => '',
            'amount' => 0,
        ];
    }
    
    
    
    

    // public function editTrip(): void
    // {
    //     \Log::info('Editing trip with costs: '.json_encode($this->tripCosts));
    //     $rules = [
    //         'tripLocation' => 'required|string|max:255',
    //         'tripLandscape' => 'required|string',
    //         'tripAvailability' => 'required|string',
    //         'tripDescription' => 'required|string',
    //         'tripActivities' => 'required|string',
    //         'tripStartDate' => 'required|date|before_or_equal:tripEndDate',
    //         'tripEndDate' => 'required|date|after_or_equal:tripStartDate',
    //         'tripPrice'=>'required|numeric|min:1'
    //     ];

    //     // if (!empty($this->tripPhotos)) {
    //     //     $rules['tripPhotos.*'] = 'image|mimes:jpeg,jpg,png';
    //     // }

    //     $this->validate($rules);

    //     try {
    //         $stripe = new StripeClient(env('STRIPE_SECRET_KEY')); // Initialize Stripe client here

    //         // Retrieve Stripe product
    //         $product = $stripe->products->retrieve($this->trip->stripe_product_id);
    //         $tripModel = TripsModel::findOrFail($this->trip->tripID);

    //         if ($product) {
    //             $product->name = $this->tripLocation;
    //             $product->description = $this->tripDescription;

    //             // Retrieve existing images
    //             $existingImages = json_decode($tripModel->tripPhoto, true) ?? [];
    //             $newImageURLs = [];

    //             // Handle new image uploads
    //             if ($this->tripPhotos) {
    //                 if(count($this->tripPhotos) > 3){
    //                     $this->error = 'You cannot upload more than 3 pictures';
    //                     return;
    //                 }

    //                 foreach ($this->tripPhotos as $photo) {
    //                     if ($photo instanceof  \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
    //                         $imagePath = 'booking_photos/' . time() . '-' . $photo->hashName() .'.'.$photo->extension();
    //                         $photo->storeAs('public', $imagePath); // Save the new image
    //                         $newImageURLs[] = asset('storage/' . $imagePath); // Store new image URL
    //                     }
    //                     else{
    //                         \Log::error('File is not a valid instance of Livewire TemporaryUploadedFile');
    //                         $this->addError('tripPhotos.' . $index, 'Uploaded file is not valid.');
    //                     }
    //                 }
    //             }

    //             // Merge existing and new images
    //             $mergedImages = array_merge($existingImages, $newImageURLs);

    //             // Update Stripe product images
    //             $stripe->products->update($product->id, [
    //                 'name' => $product->name,
    //                 'description' => $product->description,
    //                 'images' => $mergedImages, // Send updated list of images
    //             ]);

    //             // Update trip in the database
    //             $tripModel->tripPhoto = json_encode($mergedImages);
    //             $tripModel->tripLocation = $this->tripLocation;
    //             $tripModel->tripLandscape = $this->tripLandscape;
    //             $tripModel->tripAvailability = $this->tripAvailability;
    //             $tripModel->tripDescription = $this->tripDescription;
    //             $tripModel->tripActivities = $this->tripActivities;
    //             $tripModel->tripStartDate = $this->tripStartDate;
    //             $tripModel->tripEndDate = $this->tripEndDate;
    //             $tripModel->tripPrice = $this->tripPrice;
    //             $tripModel->tripCosts = json_encode($this->tripCosts);
    //             $tripModel->save();

    //             $this->success = 'Trip updated successfully!';
    //         }
    //     } catch (Exception $e) {
    //         $this->error = 'Error updating trip: ' . $e->getMessage();
    //     }
    // }

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
        'tripPrice' => 'required|numeric|min:1'
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

            // Handle new image uploads
            if ($this->tripPhotos) {
                if (count($this->tripPhotos) > 3) {
                    $this->error = 'You cannot upload more than 3 pictures';
                    return;
                }

                $newImageURLs = [];
                foreach ($this->tripPhotos as $photo) {
                    if ($photo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                        $imagePath = 'booking_photos/' . time() . '-' . $photo->hashName() . '.' . $photo->extension();
                        $photo->storeAs('public', $imagePath);
                        $newImageURLs[] = asset('storage/' . $imagePath);
                    } else {
                        \Log::error('File is not a valid instance of Livewire TemporaryUploadedFile');
                        $this->addError('tripPhotos.' . $index, 'Uploaded file is not valid.');
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

                // Update trip in the database
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
            $tripModel->tripCosts = $this->tripCosts;
            $tripModel->save();

            $this->success = 'Trip updated successfully!';
        }
    } catch (Exception $e) {
        $this->error = 'Error updating trip: ' . $e->getMessage();
    }
}


//     public function replaceImage($index)
// {
//     \Log::info('Ensuring that index ' . $index . ' is valid..');

//     // Ensure the index is valid
//     if ($index === null || !isset($this->tripPhotos[$index])) {
//         $this->addError('tripPhotos.' . $index, 'Invalid image index.');
//         \Log::info('Index ' . $index . ' is not valid');
//         return;
//     }

//     \Log::info('Index ' . $index . ' is valid!');

//     // Validate the uploaded file
//     $file = $this->tripPhotos[$index];
//     \Log::info('Validating file...');



//     $this->validate([
//         'tripPhotos.' . $index => 'required|image|mimes:jpeg,png,jpg|max:5120',
//     ]);

//     \Log::info('File validated successfully!');
//     \Log::info('File type after validation: ' . (is_object($file) ? get_class($file) : gettype($file)));


//     // Check if image exists before processing
//     if (isset($this->tripPhotos[$index])) {
//         $file = $this->tripPhotos[$index];
//     }

//     \Log::info('Generating file...');

//     if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {

//         // Generate file path
//         $filePath = 'booking_photos/' . time() . '-' . $file->hashName();
//         $fullPath = storage_path('app/public/' . $filePath);

//         \Log::info('File Path: ' . $filePath);
//         \Log::info('Full Path: ' . $fullPath);

//         // Resize the image using GD or another method
//         $this->resizeImage($file->getRealPath(), $fullPath, 350, 219);

//         \Log::info('Image resized!');

//         // Store the new image URL
//         $imageURLs[] = asset(Storage::url($filePath));
//         \Log::info('Appended image to the imageURLs array: ' . json_encode($imageURLs));

//         // Remove the old image if it exists
//         $tripPhotos = json_decode($this->trip->tripPhoto, true);
//         $oldImage = basename($tripPhotos[$index]);

//         if (\Storage::exists('public/booking_photos/' . $oldImage)) {
//             \Log::info('Found old image! Deleting old image...');
//             \Storage::delete('public/booking_photos/' . $oldImage);
//             \Log::info('Image deleted from the server!');
//         }

//         // Update image path in the database
//         $tripPhotos[$index] = \Storage::url($filePath);
//         $this->trip->tripPhoto = json_encode($tripPhotos);
//         $this->trip->save();

//         \Log::info('Image updated!');

//         // Update the component property with the new image
//         $this->tripPhotos = $tripPhotos;

//         // Emit an event to notify that the image was replaced successfully
//         $this->imageReplaceSuccess = 'Image replaced!';

//         // Clear the input after successful replacement (optional)
//         unset($this->tripPhotos[$index]);
//     } else {
//         \Log::error('File is not a valid instance of Livewire TemporaryUploadedFile');
//         $this->addError('tripPhotos.' . $index, 'Uploaded file is not valid.');
//     }
// }

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
    $this->resizeImage($file->getRealPath(), $fullPath, 350, 219);

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
    
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    
        if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }
    
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($image), imagesy($image));
    
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($resizedImage, $destinationPath);
                break;
            case IMAGETYPE_PNG:
                imagepng($resizedImage, $destinationPath);
                break;
            case IMAGETYPE_GIF:
                imagegif($resizedImage, $destinationPath);
                break;
        }
    
        imagedestroy($image);
        imagedestroy($resizedImage);
    }

    public function render()
    {
        return view('livewire.forms.edit-trip-form');
    }
}