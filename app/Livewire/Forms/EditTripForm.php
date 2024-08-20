<?PHP 

namespace App\Http\Livewire\Forms;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\TripsModel;

class EditTripForm extends Component
{
    use WithFileUploads;

    public $trip;
    public $tripLocation;
    public $tripDescription;
    public $tripStartDate;
    public $tripEndDate;
    public $tripPrice;
    public $tripPhoto;
    public $newTripPhoto;

    public function mount($tripID)
    {
        $this->trip = TripsModel::findOrFail($tripId);

        $this->tripLocation = $trip->tripLocation;
        $this->tripDescription = $trip->tripDescription;
        $this->tripStartDate = $trip->tripStartDate;
        $this->tripEndDate = $trip->tripEndDate;
        $this->tripPrice = $trip->tripPrice;
        $this->tripPhoto = $trip->tripPhoto;
    }

    public function updatedNewTripPhoto()
    {
        $this->validate([
            'newTripPhoto' => 'image|max:1024', // 1MB Max
        ]);
    }

    public function save()
    {
        $this->validate([
            'tripLocation' => 'required|string|max:255',
            'tripDescription' => 'required|string',
            'tripStartDate' => 'required|date',
            'tripEndDate' => 'required|date',
            'tripPrice' => 'required|numeric|min:0',
        ]);

        if ($this->newTripPhoto) {
            $this->tripPhoto = $this->newTripPhoto->store('booking_photos', 'public');
        }

        $this->trip->update([
            'tripLocation' => $this->tripLocation,
            'tripDescription' => $this->tripDescription,
            'tripStartDate' => $this->tripStartDate,
            'tripEndDate' => $this->tripEndDate,
            'tripPrice' => $this->tripPrice,
            'tripPhoto' => $this->tripPhoto,
        ]);

        session()->flash('message', 'Trip information updated successfully.');
    }

    public function render()
    {
        return view('livewire.forms.edit-trip-form');
    }
}
