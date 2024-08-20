<?php

use Illuminate\Validation\ValidationException;
use App\Models\TripsModel;

use Livewire\Volt\Component;

new class extends Component {
    public string $searchQuery = '';
    public array $searchResults = [];

    public function search(): void
    {
        try {
            $validate = $this->validate([
                'searchQuery' => ['required', 'string'],
            ]);

            $this->searchResults = TripsModel::where('tripLocation', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripDescription', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripLandscape', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripAvailability', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripStartDate', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripEndDate', 'LIKE', "%{$this->searchQuery}%")
                ->select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability')
                ->get()
                ->toArray();
        } catch (ValidationException $e) {
            $this->reset('searchQuery');
        } catch (\Exception $e) {
            \Log::error('Search Error: ' . $e->getMessage());
            $this->reset('searchQuery');
        }
    }

    public function clearSearchResults()
    {
        $this->searchResults = [];
        $this->searchQuery = '';
    }
};

?>



<div class="position-relative">
    <form wire:submit.prevent="search">
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center position-relative">
                <i class="bx bx-search bx-md"></i>
                <input id="searchQuery" name="searchQuery" wire:model="searchQuery" type="text"
                    class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
                    aria-label="Search..." />

                <!-- Autocomplete Results Container -->
                <div class="autocomplete-results position-absolute top-100 start-0 w-100 bg-white rounded shadow-lg mt-1 max-height-200 overflow-auto"
                    style="width: 100%;">
                    @if (!empty($searchResults))
                        <ul class="list-group m-0 p-0">
                            @foreach ($searchResults as $result)
                                <a href="{{route('admin.trip', ['tripID'=>$result['tripID']])}}">
                                <li class="list-group-item p-2 border-bottom hover:bg-light cursor-pointer">
                                    <h5 class="mb-1">{{ $result['tripLocation'] }}</h5>
                                    <img src="{{asset('storage/'.$result['tripPhoto'])}}" class="img-thumbnail rounded" style="width: 50px; height: 50px;" />
                                </li>
                                </a>
                            @endforeach
                        </ul>
                        <button type="button" wire:click="clearSearchResults">Clear</button>
                    @elseif(isset($searchQuery) && $searchQuery !== '')
                        <p class="p-2 text-center">No results found for "{{ $searchQuery }}"</p>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
