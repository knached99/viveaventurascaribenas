<?php 
namespace App\Livewire\Forms;

use Livewire\Component;
use App\Models\TripsModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\StripeClient;
use Exception;
use Carbon\Carbon;

class EditTripForm extends Component
{
    public $trip;

    public string $tripLocation = '';
    public ?UploadedFile $tripPhoto = null;
    public string $tripLandscape = ''; 
    public string $tripAvailability = ''; 
    public string $tripDescription = ''; 
    public string $tripActivities = ''; 
    public string $tripStartDate = ''; 
    public string $tripEndDate = ''; 
    public string $success = '';
    public string $error = '';

    protected function rules()
    {
        return [
            'tripLocation' => 'required|string|max:255',
            'tripPhoto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
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
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        // Convert the string dates to Carbon instances and format them
        $this->tripLocation = $trip->tripLocation;
        $this->tripLandscape = $trip->tripLandscape;
        $this->tripAvailability = $trip->tripAvailability;
        $this->tripDescription = $trip->tripDescription;
        $this->tripActivities = $trip->tripActivities;
        $this->tripStartDate = Carbon::parse($trip->tripStartDate)->format('Y-m-d');
        $this->tripEndDate = Carbon::parse($trip->tripEndDate)->format('Y-m-d');
    }

    public function editTrip() : void
    {
        $this->validate();

    
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        

        try {
            // Retrieve the Stripe product
            $product = $this->stripe->products->retrieve($this->trip->stripe_product_id);
            $tripModel = TripsModel::findOrFail($this->trip->tripID);


            if ($product) {
                $product->name = $this->tripLocation;
                $product->description = $this->tripDescription;

                if ($this->tripPhoto) {
                    $imagePath = $this->tripPhoto->store('booking_photos', 'public');
                    $imageURL = asset(Storage::url($imagePath));

                    // Check and delete the old image
                    if (!empty($product->images[0])) {
                        $existingImageURL = $product->images[0];
                        $existingImageFile = basename($existingImageURL);

                        if (Storage::disk('public')->exists('booking_photos/' . $existingImageFile)) {
                            Storage::disk('public')->delete('booking_photos/' . $existingImageFile);
                        }
                    }

                    // Update the product's image
                    $product->images = [$imageURL];
                }
                

                // Update the product
                $this->stripe->products->update($product->id, [
                    'name' => $product->name,
                    'description' => $product->description,
                    'images' => $product->images,
                ]);


                // Update the Model 

        
                $tripModel->tripLocation = $this->tripLocation;
                $tripModel->tripLandscape = $this->tripLandscape;
                $tripModel->tripAvailability = $this->tripAvailability;
                $tripModel->tripDescription = $this->tripDescription;                
                $tripModel->tripActivities = $this->tripActivities;
                $tripModel->tripStartDate = $this->tripStartDate;
                $tripModel->tripEndDate = $this->tripEndDate;
                
                $tripModel->save();

                $this->success = 'Trip information updated successfully!';

        
            
            }
        } catch (Exception $e) {
            \Log::error('Uncaught exception occurred on line: ' . __LINE__ . ' in class: ' . __CLASS__ . ' Error Message: ' . $e->getMessage());
            $this->error = 'An error occurred while updating the trip.';
        }

        $this->success = 'Trip updated successfully!';
    }

    public function render()
    {
        return view('livewire.forms.edit-trip-form');
    }
}
