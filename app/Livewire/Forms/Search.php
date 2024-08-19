<?php
namespace App\Http\Livewire\Forms;

use Livewire\Component;
use Exception;
use App\Models\TripsModel;

class Search extends Component
{
    // public string $searchQuery = '';
    // public array $searchResults = [];

    // public function search(): void 
    // {
    //     try {
    //         // Perform the search
    //         $this->searchResults = TripsModel::where('tripLocation', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripDescription', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripLandscape', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripAvailability', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripStartDate', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripEndDate', 'LIKE', "%{$this->searchQuery}%")
    //             ->select('tripID', 'tripLocation', 'tripDescription', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate')
    //             ->get()
    //             ->toArray(); 
    //     } catch(Exception $e) {
    //         $this->addError('searchQuery', 'Unable to search for that query, something went wrong');
    //         \Log::error('Unexpected exception encountered on search on line ' . __LINE__ . ' In class: ' . __CLASS__ . ' In function: ' . __FUNCTION__ . '. Error: ' . $e->getMessage());
    //     }
    // }

    public function render()
    {
        return view('livewire.forms.search', [
            'results' => $this->searchResults,
        ]);
    }
}
