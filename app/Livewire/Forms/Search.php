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
            $tripsResults = TripsModel::search($this->searchQuery)->get()->map(function ($trip) {
                return array_merge($trip->toArray(), ['type' => 'trip']);
            })->toArray();
    
            $testimonialsResults = Testimonials::with(['trip'])->search($this->searchQuery)->get()->map(function ($testimonial) {
                return array_merge($testimonial->toArray(), ['type' => 'testimonial']);
            })->toArray();
    
            $bookingResults = BookingModel::with(['trip'])->search($this->searchQuery)->get()->map(function ($booking) {
                return array_merge($booking->toArray(), ['type' => 'booking']);
            })->toArray();
    
            $reservationResults = Reservations::with(['trip'])->search($this->searchQuery)->get()->map(function ($reservation) {
                return array_merge($reservation->toArray(), ['type' => 'reservation']);
            })->toArray();
    
            $this->searchResults = array_merge($tripsResults, $testimonialsResults, $bookingResults, $reservationResults);
    
            \Log::info('Search Results', ['results' => $this->searchResults]);
    
            if (empty($this->searchResults)) {
                $this->suggestion = $this->findSimilarTerm($this->searchQuery);
            }
        } catch (\Exception $e) {
            $this->searchResults = [];
            $this->searchError = 'Unable to perform search';
            \Log::error("Search Error: {$e->getMessage()}");
        }
    }
    
    // public function search()
    // {
    //     $this->validate();
    
    //     try {
    //         // Trips Search
    //         $tripsResults = TripsModel::where('tripID', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripLocation', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripDescription', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripLandscape', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripAvailability', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripStartDate', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('tripEndDate', 'LIKE', "%{$this->searchQuery}%")
    //             ->select('tripID', 'tripLocation', 'tripPhoto', 'tripDescription', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPhoto')
    //             ->get()
    //             ->map(function ($trip) {
    //                 $trip['type'] = 'trip';
    //                 return $trip;
    //             })
    //             ->toArray();
    
    //         // Testimonials Search
    //         $testimonialsResults = Testimonials::with(['trip'])->where('name', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('email', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('testimonial', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('testimonial_approval_status', 'LIKE', "%{$this->searchQuery}%")
    //             ->select('testimonialID', 'tripID', 'name', 'email', 'testimonial')
    //             ->get()
    //             ->map(function ($testimonial) {
    //                 $testimonial['type'] = 'testimonial';
    //                 return $testimonial;
    //             })
    //             ->toArray();
    
    //         // Bookings Search
    //         $bookingResults = BookingModel::with(['trip'])->where('bookingID', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('name', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('email', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('phone_number', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('address_line_1', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('address_line_2', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('city', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('state', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('zip_code', 'LIKE', "%{$this->searchQuery}%")
    //             ->select('bookingID', 'tripID', 'name', 'email', 'phone_number', 'stripe_product_id')
    //             ->get()
    //             ->map(function ($booking) {
    //                 $booking['type'] = 'booking';
    //                 return $booking;
    //             })
    //             ->toArray();
    
    //         // Reservations Search
    //         $reservationsResults = Reservations::with(['trip'])->where('reservationID', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('name', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('email', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('phone_number', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('address_line_1', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('address_line_2', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('city', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('state', 'LIKE', "%{$this->searchQuery}%")
    //             ->orWhere('zip_code', 'LIKE', "%{$this->searchQuery}%")
    //             ->select('reservationID', 'tripID', 'name', 'email', 'phone_number', 'stripe_product_id')
    //             ->get()
    //             ->map(function ($reservation) {
    //                 $reservation['type'] = 'reservation';
    //                 return $reservation;
    //             })
    //             ->toArray();
    
    //         $this->searchResults = array_merge($tripsResults, $testimonialsResults, $bookingResults, $reservationsResults);
    
    //         // Suggest similar term if no results found
    //         if (empty($this->searchResults)) {
    //             $this->suggestion = $this->findSimilarTerm($this->searchQuery);
    //         }
    
    //     } catch (\Exception $e) {
    //         $this->searchResults = [];
    //         $this->searchError = 'Unable to perform search';
    //         \Log::error("Search Error: {$e->getMessage()}");
    //     }
    // }
    
    
    
    // This method leverages the levenshtein distance algorithm which 
    // dynamically retrieves the terms that closely match the user's search query
    // We also enable caching to reduce number of database queries 
    // Replacing soundex with metaphone as it is more accurate phonetically 


    private function findSimilarTerm($query){

        // caching terms for an hour to avoid DB calls for repeated queries 
        if(!Cache::get('search_terms')){
        $terms = Cache::remember('search_terms', 60, function(){

            $terms = [];

            $tripTerms = TripsModel::select('tripLocation', 'tripDescription', 'tripLandscape', 'tripAvailability')->distinct()->get();
            
            foreach($tripTerms as $trip){

                $terms[] = strtolower(trim($trip->tripLocation)); 
                $terms[] = strtolower(trim($trip->tripDescription));
                $terms[] = strtolower(trim($trip->tripLandscape));
                $terms[] = strtolower(trim($trip->tripAvailability));
            }

            $testimonialTerms = Testimonials::select('name', 'email', 'testimonial')->distinct()->get();
            foreach ($testimonialTerms as $testimonial) {
                $terms[] = strtolower(trim($testimonial->name));
                $terms[] = strtolower(trim($testimonial->email));
                $terms[] = strtolower(trim($testimonial->testimonial));
            }
            $bookingTerms = BookingModel::select('name', 'email', 'phone_number', 'address_line_1', 'address_line_2', 'city', 'state', 'zip_code')->distinct()->get();
            foreach ($bookingTerms as $booking) {
                $terms[] = strtolower(trim($booking->name));
                $terms[] = strtolower(trim($booking->email));
                $terms[] = strtolower(trim($booking->phone_number));
                $terms[] = strtolower(trim($booking->address_line_1));
                $terms[] = strtolower(trim($booking->address_line_2));
                $terms[] = strtolower(trim($booking->city));
                $terms[] = strtolower(trim($booking->state));
                $terms[] = strtolower(trim($booking->zip_code));
            }
            $reservationTerms = Reservations::select('name', 'email', 'phone_number', 'address_line_1', 'address_line_2', 'city', 'state', 'zip_code')->distinct()->get();
            foreach ($reservationTerms as $reservation) {
                $terms[] = strtolower(trim($reservation->name));
                $terms[] = strtolower(trim($reservation->email));
                $terms[] = strtolower(trim($reservation->phone_number));
                $terms[] = strtolower(trim($reservation->address_line_1));
                $terms[] = strtolower(trim($reservation->address_line_2));
                $terms[] = strtolower(trim($reservation->city));
                $terms[] = strtolower(trim($reservation->state));
                $terms[] = strtolower(trim($reservation->zip_code));
            }

            // returning unique search suggestions
            return array_unique(array_filter($terms));


            // First we normalize the search query 
            $query = trim(strtolower($query)); 

            $closest = null;

            //  initializing the shortest distance variable 
            //with the largest possible integer value in PHP.
            $shortestDistance = PHP_INT_MAX;

            foreach($terms as $term){

                $lev = levenshtein($query, $term);

                // If exact match is found, immediately return the suggested term 

                if($lev === 0){
                    return ucfirst($term);
                }

                // If distance is smaller, then we update closest match 

                if($lev < $shortestDistance){

                    $closet = $term;
                    $shortestDistance = $lev;
                }
            }

            return $closest ? ucfirst($closest) : null;
        });

        }
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
