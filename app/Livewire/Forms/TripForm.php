<?php 

namespace App\Livewire\Forms;

use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Form;
use App\Models\TripsModel;
use Illuminate\Support\Facades\Storage;
use Exception;

class TripForm extends Form {

    use WithFileUploads;
    
    #[Validate('required|string')]
    public string $tripLocation = '';

    #[Validate('required|image|mimes:jpeg,png,jpg|max:2048')]
    public \Illuminate\Http\UploadedFile | null $tripPhoto = null;

    #[Validate('required|string')]
    public string $tripLandscape = '';

    #[Validate('required')]
    public string $tripAvailability = '';

    #[Validate('required')]
    public string $tripDescription = '';

    #[Validate('required|date|before_or_equal:tripEndDate')]
    public string $tripStartDate = '';

    #[Validate('required|date|after_or_equal:tripStartDate')]
    public string $tripEndDate = '';

    #[Validate('required|numeric|min:1')]
    public string $tripPrice = '';

    // Define the status and error properties
    public string $status = '';
    public string $error = '';

    public function submitTripForm(): void {
        $this->validate();
    
        try {
            // Store the uploaded file in the 'booking_photos' directory under 'storage/app/public'
            $filePath = $this->tripPhoto->store('booking_photos', 'public');
    
            $data = [
                'tripID' => Str::uuid(),
                'tripLocation' => $this->tripLocation,
                'tripDescription' => $this->tripDescription,
                'tripLandscape' => $this->tripLandscape,
                'tripAvailability' => $this->tripAvailability,
                'tripPhoto' => $filePath, // relative file path
                'tripStartDate' => $this->tripStartDate,
                'tripEndDate' => $this->tripEndDate,
                'tripPrice' => $this->tripPrice
            ];
    
            // Save trip data
            TripsModel::create($data);
    
            // Reset the temporary file from Livewire
            $this->tripPhoto = null;
    
            // Set success message
            $this->status = 'Trip Successfully Created!';
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
        $this->tripPhoto = null; // Reset file
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
}
