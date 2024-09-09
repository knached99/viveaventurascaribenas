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

    public string $tripLocation = '';
    public string $tripLandscape = '';
    public string $tripAvailability = '';
    public string $tripDescription = '';
    public string $tripActivities = '';
    public string $tripStartDate = '';
    public string $tripEndDate = '';
    public string $tripPrice = '';
    // Validate that 'tripPhoto' is an array of images with specific rules
    #[Validate('required|array|max:3')]
    public ?array $tripPhoto = [];

    public string $status = '';
    public string $error = '';

    public function rules()
    {
        return [
            'tripPhoto.*' => 'image|mimes:jpeg,png,jpg', // Validation for each file
            'tripLocation' => 'required|string',
            'tripLandscape' => 'required|string',
            'tripAvailability' => 'required|string',
            'tripDescription' => 'required|string',
            'tripActivities' => 'required|string',
            'tripStartDate' => 'sometimes|date|before_or_equal:tripEndDate',
            'tripEndDate' => 'sometimes|date|after_or_equal:tripStartDate',
            'tripPrice' => 'required|numeric|min:1',
        ];
    }

    public function mount(){
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    }

    public function submitTripForm(): void {

        $this->validate();

      

        if(!$this->stripe){
            $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        
        }

        try {
            $imageURLs = [];

            // Create booking_photos folder if it does not exist

            $dirPath = storage_path('app/public/booking_photos');

            if(!file_exists($dirPath)){
                mkdir($dirPath, 0755, true);
            }
            
            foreach ($this->tripPhoto as $photo) {
                
                // Resize and store the uploaded file
                $image = $photo->getRealPath();
                $filePath = 'booking_photos/' . time() . '-' . $photo->hashName() . '.'.$photo->extension();
                $fullPath = storage_path('app/public/' . $filePath);
    
                // Use GD to resize the image
                $this->resizeImage($image, $fullPath, 350, 219);
    
                $imageURLs[] = asset(Storage::url($filePath));
            }

         

            $product = $this->stripe->products->create([
                'name' => $this->tripLocation,
                'description' => $this->tripDescription,
                'images' => $imageURLs
            ]);

            // Create price in Stripe after successful product creation
            if ($product) {

                $price = $this->stripe->prices->create([
                    'unit_amount' => $this->tripPrice * 100, // unit amount in stripe is stored in cents
                    'currency' => 'usd',
                    'product' => $product->id
                ]);

                if ($price) {
                    // Reset the temporary file from Livewire
                    $data = [
                        'tripID' => Str::uuid(),
                        'stripe_product_id' => $product->id,
                        'tripLocation' => $this->tripLocation,
                        'tripDescription' => $this->tripDescription,
                        'tripActivities' => $this->tripActivities,
                        'tripLandscape' => $this->tripLandscape,
                        'tripAvailability' => $this->tripAvailability,
                        'tripPhoto' => json_encode($imageURLs),// Store image URLs as a JSON array
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
        $this->tripPhoto = []; // Reset file array
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

    private function resizeImage($sourcePath, $destinationPath, $newWidth, $newHeight) {
        list($width, $height) = getimagesize($sourcePath);
        $image = imagecreatefromjpeg($sourcePath); // Change to imagecreatefrompng or imagecreatefromgif as needed
    
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    
        // Preserve transparency if PNG
        if (imageistruecolor($image)) {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }
    
        // Resize
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
        // Save the image
        imagejpeg($resizedImage, $destinationPath); // Change to imagepng or imagegif as needed
    
        // Cleanup
        imagedestroy($image);
        imagedestroy($resizedImage);
    }
        

}
