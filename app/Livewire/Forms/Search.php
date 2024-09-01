<?php

namespace App\Livewire\Forms;

use App\Models\Testimonials;
use App\Models\TripsModel;
use App\Models\BookingModel;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Exception;

class Search extends Component
{
    public array $searchResults = [];
    public string $searchError = '';

    #[Validate('required|string')]
    public string $searchQuery = '';

    public function search()
    {
        $this->validate();

        try {
            $tripsResults = TripsModel::where('tripLocation', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripDescription', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripLandscape', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripAvailability', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripStartDate', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripEndDate', 'LIKE', "%{$this->searchQuery}%")
                ->select('tripID', 'tripLocation', 'tripDescription', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPhoto')
                ->get()
                ->toArray();

            $testimonialsResults = Testimonials::where('name', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('email', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('testimonial', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('testimonial_approval_status', 'LIKE', "%{$this->searchQuery}%")
                ->select('testimonialID', 'name', 'testimonial')
                ->get()
                ->toArray();

        $bookingResults = BookingModel::where('name', 'LIKE', "%{$this->searchQuery}%")
        ->orWhere('email', 'LIKE', "%{$this->searchQuery}%")
        ->orWhere('phone_number', 'LIKE', "%{$this->searchQuery}%")
        ->orWhere('address_line_1', 'LIKE', "%{$this->searchQuery}%")
        ->orWhere('address_line_2', 'LIKE', "%{$this->searchQuery}%")
        ->orWhere('city', 'LIKE', "%{$this->searchQuery}%")
        ->orWhere('state', 'LIKE', "%{$this->searchQuery}%")
        ->orWhere('zip_code', 'LIKE', "%{$this->searchQuery}%")
        ->select('bookingID', 'name', 'email', 'phone_number', 'stripe_product_id')
        ->get()
        ->toArray();

            $this->searchResults = array_merge($tripsResults, $testimonialsResults, $bookingResults);
        } catch (Exception $e) {
            $this->searchError = 'Unable to perform search';
            \Log::error('Search Error Occurred on line: ' . __LINE__ . ' in file: ' . __FILE__ . ' in function: ' . __FUNCTION__ . ' Error Message: ' . $e->getMessage());
        }
    }

    public function clearSearchResults()
    {
        $this->searchResults = [];
        $this->searchQuery = '';
    }

    public function render()
    {
        return view('livewire.forms.search', [
            'results' => $this->searchResults,
            'error' => $this->searchError
        ]);
    }
}
