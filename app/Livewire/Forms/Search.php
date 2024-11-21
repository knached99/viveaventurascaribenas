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
    public ?string $suggestion = null;  // Search suggestion 

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
    
            // Suggest similar term if no results found
            if (empty($this->searchResults)) {
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
        // Cache terms for 1 hour
        $terms = Cache::remember('search_terms', 60, function () {
            $terms = [];
    
            // Collect terms from different models
            $tripTerms = TripsModel::select('tripLocation', 'tripDescription', 'tripLandscape', 'tripAvailability')->distinct()->get();
            foreach ($tripTerms as $trip) {
                $terms[] = $trip->tripLocation;
                $terms[] = $trip->tripDescription;
                $terms[] = $trip->tripLandscape;
                $terms[] = $trip->tripAvailability;
            }
    
            $testimonialTerms = Testimonials::select('name', 'email', 'testimonial')->distinct()->get();
            foreach ($testimonialTerms as $testimonial) {
                $terms[] = $testimonial->name;
                $terms[] = $testimonial->email;
                $terms[] = $testimonial->testimonial;
            }
    
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
    
            // Clean up terms list by removing duplicates, empty values, and normalizing
            return array_unique(array_filter(array_map('trim', $terms)));
        });
        
        $query = strtolower(trim($query));
        $closest = null;
        $shortestDistance = PHP_INT_MAX;
    
        foreach ($terms as $term) {
            $term = strtolower(trim($term));
    
            // Calculate Levenshtein distance
            $lev = levenshtein($query, $term);
    
            if ($lev === 0) {
                // Exact match found
                return $term;
            }
    
            // Update closest match if distance is smaller
            if ($lev < $shortestDistance) {
                $closest = $term;
                $shortestDistance = $lev;
            }
        }
    
        // Fallback: check Soundex matches if no close Levenshtein matches found
        $querySoundex = soundex($query);
        foreach ($terms as $term) {
            if (soundex($term) === $querySoundex) {
                $closest = $term;
                break; // Prioritize first Soundex match
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
