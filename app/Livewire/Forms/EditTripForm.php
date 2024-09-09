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
    public ?array $tripPhotos = []; // Array to handle multiple image uploads
    public string $tripLandscape = ''; 
    public string $tripAvailability = ''; 
    public string $tripDescription = ''; 
    public string $tripActivities = ''; 
    public string $tripStartDate = ''; 
    public string $tripEndDate = ''; 
    public string $success = '';
    public string $error = '';
    public ?int $replaceIndex = null;

    protected function rules()
    {
        return [
            'tripLocation' => 'required|string|max:255',
            'tripPhotos.*' => 'sometimes|image|mimes:jpeg,jpg,png|max:2048', // Multiple image validation
            'tripLandscape' => 'required|string',
            'tripAvailability' => 'required|string',
            'tripDescription' => 'required|string',
            'tripActivities' => 'required|string',
            'tripStartDate' => 'required|date|before_or_equal:tripEndDate',
            'tripEndDate' => 'required|date|after_or_equal:tripStartDate',
        ];
    }

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
    }

    public function editTrip(): void
    {
        $this->validate();

        try {
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY')); // Initialize Stripe client here

            // Retrieve Stripe product
            $product = $stripe->products->retrieve($this->trip->stripe_product_id);
            $tripModel = TripsModel::findOrFail($this->trip->tripID);

            if ($product) {
                $product->name = $this->tripLocation;
                $product->description = $this->tripDescription;

                // Retrieve existing images
                $existingImages = json_decode($tripModel->tripPhoto, true) ?? [];
                $newImageURLs = [];

                // Handle new image uploads
                if ($this->tripPhotos) {
                    foreach ($this->tripPhotos as $photo) {
                        if ($photo instanceof UploadedFile) {
                            $imagePath = 'booking_photos/' . time() . '-' . $photo->hashName();
                            $photo->storeAs('public', $imagePath); // Save the new image
                            $newImageURLs[] = asset('storage/' . $imagePath); // Store new image URL
                        }
                    }
                }

                // Merge existing and new images
                $mergedImages = array_merge($existingImages, $newImageURLs);

                // Update Stripe product images
                $stripe->products->update($product->id, [
                    'name' => $product->name,
                    'description' => $product->description,
                    'images' => $mergedImages, // Send updated list of images
                ]);

                // Update trip in the database
                $tripModel->tripPhoto = json_encode($mergedImages);
                $tripModel->tripLocation = $this->tripLocation;
                $tripModel->tripLandscape = $this->tripLandscape;
                $tripModel->tripAvailability = $this->tripAvailability;
                $tripModel->tripDescription = $this->tripDescription;
                $tripModel->tripStartDate = $this->tripStartDate;
                $tripModel->tripEndDate = $this->tripEndDate;
                $tripModel->save();

                $this->success = 'Trip updated successfully!';
            }
        } catch (Exception $e) {
            $this->error = 'Error updating trip: ' . $e->getMessage();
        }
    }

    public function replaceImage($index)
    {
        // Ensure the index is valid
        if ($index === null || !isset($this->tripPhotos[$index])) {
            $this->error = 'Invalid image index.';
            return;
        }
    
        // Retrieve existing photos from trip
        $tripPhotos = json_decode($this->trip->tripPhoto, true);
    
        // Check if the image at the given index is an instance of UploadedFile
        if ($this->tripPhotos[$index] instanceof UploadedFile) {
            // Remove the old image file
            $oldImage = $tripPhotos[$index];
            $oldImagePath = basename($oldImage);
            
            // Check if old image exists before deleting
            if (Storage::exists('public/' . $oldImagePath)) {
                Storage::delete('public/' . $oldImagePath);
            }
    
            // Upload new image
            $newImagePath = 'booking_photos/' . time() . '-' . $this->tripPhotos[$index]->hashName();
            $this->tripPhotos[$index]->storeAs('public', $newImagePath);
    
            // Replace image in the array
            $tripPhotos[$index] = asset('storage/' . $newImagePath);
    
            // Save updated images
            $this->trip->tripPhoto = json_encode($tripPhotos);
            $this->trip->save();
            $this->tripPhotos = $tripPhotos;
    
            $this->success = 'Image replaced successfully!';
        } else {
            $this->error = 'Selected image is not valid.';
        }
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

    public function render()
    {
        return view('livewire.forms.edit-trip-form');
    }
}
