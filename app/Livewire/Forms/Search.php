<?php

namespace App\Livewire\Forms;

use App\Models\Testimonials;
use App\Models\TripsModel;
use App\Models\BookingModel;
use App\Models\Reservations;
use App\Models\PhotoGalleryModel;
use App\Helpers\Helper;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Exception;
use Illuminate\Support\Facades\Cache;

class Search extends Component
{
    public array $searchResults = [];
    public string $searchError = '';
    public string $suggestion = '';  // Search suggestion 

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
                ->select('testimonialID', 'tripID', 'name', 'email', 'testimonial')
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
    
            // Suggest similar term (add the missing `findSimilarTerm` call)
                if(empty($this->searchResults)){
                
                 $this->suggestion = $this->findSimilarTerm($this->searchQuery);
                
                }       
                
           
        } catch (\Exception $e) {
           
        $this->searchResults = [];
        
        $this->searchError = 'Unable to perform search';
        \Log::error("Search Error: {$e->getMessage()}");
      
    }
    }
    
    
    
    // This method leverages the levenshtein distance algorithm which 
    // dynamically retrieves the terms that closely match the user's search query
    // We also enable caching to reduce number of database queries 

  
    private function findSimilarTerm($query)
{
    // Cache the terms for 1 hour to reduce database queries
    $terms = Cache::remember('search_terms', 60, function () {
        $terms = [];

        // Retrieve terms from `TripsModel`
        $tripTerms = TripsModel::select('tripLocation', 'tripDescription', 'tripLandscape', 'tripAvailability')->distinct()->get();
        foreach ($tripTerms as $trip) {
            $terms[] = $trip->tripLocation;
            $terms[] = $trip->tripDescription;
            $terms[] = $trip->tripLandscape;
            $terms[] = $trip->tripAvailability;
        }

        // Retrieve terms from `Testimonials`
        $testimonialTerms = Testimonials::select('name', 'email', 'testimonial')->distinct()->get();
        foreach ($testimonialTerms as $testimonial) {
            $terms[] = $testimonial->name;
            $terms[] = $testimonial->email;
            $terms[] = $testimonial->testimonial;
        }

        // Retrieve terms from `BookingModel`
        $bookingTerms = BookingModel::select('name', 'email', 'phone_number', 'address_line_1', 'address_line_2', 'city', 'state', 'zip_code')->distinct()->get();
        foreach ($bookingTerms as $booking) {
            $terms[] = $booking->name;
            $terms[] = $booking->email;
            $terms[] = $booking->phone_number;
            $terms[] = $booking->address_line_1;
            $terms[] = $booking->address_line_2;
            $terms[] = $booking->city;
            $terms[] = $booking->state;
            $terms[] = $booking->zip_code;
        }

        // Retrieve terms from `Reservations`
        $reservationTerms = Reservations::select('name', 'email', 'phone_number', 'address_line_1', 'address_line_2', 'city', 'state', 'zip_code')->distinct()->get();
        foreach ($reservationTerms as $reservation) {
            $terms[] = $reservation->name;
            $terms[] = $reservation->email;
            $terms[] = $reservation->phone_number;
            $terms[] = $reservation->address_line_1;
            $terms[] = $reservation->address_line_2;
            $terms[] = $reservation->city;
            $terms[] = $reservation->state;
            $terms[] = $reservation->zip_code;
        }

        // Clean up terms list by removing duplicates and empty values
        return array_unique(array_filter($terms));
    });

    // Find the closest term to the user's search query
    $closest = null;
    $shortestPath = -1;

    foreach ($terms as $term) {
        $lev = levenshtein(strtolower($query), strtolower($term));

        // If exact match is found, no need to continue
        if ($lev == 0) {
            $closest = $term;
            $shortestPath = 0;
            break;
        }

        // Track the closest term with the shortest distance so far
        if ($lev < $shortestPath || $shortestPath < 0) {
            $closest = $term;
            $shortestPath = $lev;
        }
    }

    return $closest;
}
    

    public function clearSearchResults()
    {
        $this->searchResults = [];
        $this->searchQuery = '';
        $this->suggestion = '';
    }

    public function render()
    {
        return view('livewire.forms.search', [
            'results' => $this->searchResults,
            'error' => $this->searchError
        ]);
    }
}
