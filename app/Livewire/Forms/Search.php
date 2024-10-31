<?php

namespace App\Livewire\Forms;

use App\Models\Testimonials;
use App\Models\TripsModel;
use App\Models\BookingModel;
use App\Models\Reservations;
use App\Models\PhotoGalleryModel;
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
            // Trips Search
            $tripsResults = TripsModel::where('tripID', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripLocation', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripDescription', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripLandscape', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripAvailability', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripStartDate', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('tripEndDate', 'LIKE', "%{$this->searchQuery}%")
                ->select('tripID', 'tripLocation', 'tripPhoto', 'tripDescription', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPhoto')
                ->get()
                ->map(function ($trip) {
                    $trip['type'] = 'trip';
                    return $trip;
                })
                ->toArray();
    
            // Testimonials Search
            $testimonialsResults = Testimonials::with(['trip'])->where('name', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('email', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('testimonial', 'LIKE', "%{$this->searchQuery}%")
                ->orWhere('testimonial_approval_status', 'LIKE', "%{$this->searchQuery}%")
                ->select('testimonialID', 'tripID', 'name', 'testimonial')
                ->get()
                ->map(function ($testimonial) {
                    $testimonial['type'] = 'testimonial';
                    return $testimonial;
                })
                ->toArray();
    
            // Bookings Search
            $bookingResults = BookingModel::with(['trip'])->where('bookingID', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('name', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('email', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('phone_number', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('address_line_1', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('address_line_2', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('city', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('state', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('zip_code', 'LIKE', "%{$this->searchQuery}%")
            ->select('bookingID', 'tripID', 'name', 'email', 'phone_number', 'stripe_product_id')
            ->get()
                ->map(function ($booking) {
                    $booking['type'] = 'booking';
                    return $booking;
                })
                
                ->toArray();
    
            // Reservations Search
            $reservationsResults = Reservations::with(['trip'])->where('reservationID', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('name', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('email', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('phone_number', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('address_line_1', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('address_line_2', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('city', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('state', 'LIKE', "%{$this->searchQuery}%")
            ->orWhere('zip_code', 'LIKE', "%{$this->searchQuery}%")
            ->select('reservationID', 'tripID', 'name', 'email', 'phone_number', 'stripe_product_id')
            ->get()
                ->map(function ($reservation) {
                    $reservation['type'] = 'reservation';
                    return $reservation;
                })
                ->toArray();
            
            $this->searchResults = array_merge($tripsResults, $testimonialsResults, $bookingResults, $reservationsResults);


        } catch (Exception $e) {
            $this->searchError = 'Unable to perform search';
            \Log::error("Search Error: {$e->getMessage()}");
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
