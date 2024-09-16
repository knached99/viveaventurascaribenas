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

    public string $tripID = '';
    public string $tripLocation = '';
    public string $tripLandscape = '';
    public string $tripAvailability = '';
    public string $tripDescription = '';
    public string $tripActivities = '';
    public string $tripStartDate = '';
    public string $tripEndDate = '';
    public string $tripPrice = '';
    public array $tripCosts = [];
    public string $num_trips = '';
    public bool $active = false;
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
            'tripCosts.*.name' => 'sometimes|string',
            'tripCosts.*.amount'=>'sometimes|numeric|min:1',
            'num_trips'=>'required|min:1',
        ];
    }

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

        
    }

    public function submitTripForm(){

        $this->validate();

        $tripCostsJson = json_encode($this->tripCosts);

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
                    'product' => $product->id,                    
                ]);

                if ($price) {
                    // Reset the temporary file from Livewire
                    $this->tripID = Str::uuid();
                    $data = [
                        'tripID' => $this->tripID,
                        'stripe_product_id' => $product->id,
                        'tripLocation' => $this->tripLocation,
                        'tripDescription' => $this->tripDescription,
                        'tripActivities' => $this->tripActivities,
                        'tripLandscape' => $this->tripLandscape,
                        'tripAvailability' => $this->tripAvailability,
                        'tripPhoto' => json_encode($imageURLs),// Store image URLs as a JSON array
                        'tripStartDate' => $this->tripStartDate,
                        'tripEndDate' => $this->tripEndDate,
                        'tripPrice' => $this->tripPrice,
                        'tripCosts'=>$tripCostsJson,
                        'num_trips'=>intval($this->num_trips),
                        'active' => $this->active ? true : false,
                    ];

                    // Save trip data
                    TripsModel::create($data);
                    

                    $this->resetForm();

                    // Set success message
                    $this->status = 'Trip Successfully Created!';
                    return redirect('/admin/trip/'.$this->tripID)->with('status',$this->status);
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
        $this->tripLandscape = '';
        $this->tripAvailability = '';
        $this->tripPhoto = []; // Reset file array
        $this->tripStartDate = '';
        $this->tripEndDate = '';
        $this->tripPrice = '';
        $this->tripCosts = [];
        $this->num_trips = '';
        $this->active = false;

        $this->status = ''; // Reset status
        $this->error = '';  // Reset error
    }

    public function render()
    {
        return view('livewire.forms.create-trip');
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
                $quality = 100; // Adjust the quality level (90 is high quality, can go up to 100)
                imagejpeg($resizedImage, $destinationPath, $quality);
                break;
            case IMAGETYPE_PNG:
                $compression = 1; // Adjust the compression level (0 for no compression, 9 for max compression)
                imagepng($resizedImage, $destinationPath, $compression);
                break;
           
        }
    
        // Free up memory
        imagedestroy($image);
        imagedestroy($resizedImage);
    }
    
    
        

}