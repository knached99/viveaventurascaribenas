<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripsModel;
use App\Models\Testimonials;
use App\Models\PhotoGalleryModel;
use Square\SquareClient;
use App\Models\BookingModel;
use App\Models\Reservations;
use Illuminate\Database\Eloquent\ModelNotFoundException; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingSubmittedAdmin;
use App\Notifications\BookingSubmittedCustomer;
use Illuminate\Support\Facades\URL;

class Home extends Controller
{
    public function __construct(){
         $this->square = new SquareClient([
             'accessToken' => env('SQUARE_ACCESS_TOKEN'),
             'environment' => env('SQUARE_ENVIRONMENT', 'production')
         ]);
         $this->bookingID = Str::uuid();
    }
    
    public function homePage()
    {
        $totalBookings = BookingModel::count();
        $totalTrips = TripsModel::count();
        
        $response = $this->square->getCustomersApi()->listCustomers();

        if ($response->isSuccess()) {
            $customers = $response->getResult()->getCustomers(); // Fetch customers array
            $totalCustomers = is_array($customers) ? count($customers) : 0;
        } else {
            $totalCustomers = 0; // Default to 0 if API fails
        }
        
       
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice', 'slug',  'num_trips')
            ->where('active', true)
            ->get();

        $testimonials = Testimonials::with('trip')->where('testimonial_approval_status', 'Approved')->get();

        $photos = PhotoGalleryModel::with(['trip'])->select('photoID', 'photoLabel', 'photoDescription', 'photos', 'tripID')->get();


        $mostPopularBookings = DB::table('trips')
        ->select('trips.tripID', 'trips.slug', 'trips.tripPhoto', DB::raw('
            (SELECT COUNT(*) FROM bookings WHERE bookings.tripID = trips.tripID) + 
            (SELECT COUNT(*) FROM reservations WHERE reservations.tripID = trips.tripID) 
            AS total_count
        '))
        ->where('trips.active', true)
        ->having('total_count', '>', 2)
        ->orderByDesc('total_count')
        ->take(4)
        ->get();

    
        $popularTrips = [];
        $mostPopularTripId = null;
        $highestBookingCount = 0;
    
        foreach ($mostPopularBookings as $booking) {
            $trip = TripsModel::where('tripID', $booking->tripID)->first();
            
            if ($trip) {
                $popularTrips[] = [
                    'id' => $trip->tripID,
                    'slug'=>$trip->slug,
                    'name' => $trip->tripLocation,
                    'count' => $booking->booking_count,
                    'image' => $trip->tripPhoto,
                    // 'coupon'=>$trip->square_coupon_id,
                ];
    
                if ($booking->booking_count > $highestBookingCount) {
                    $mostPopularTripId = $trip->tripID;
                    $highestBookingCount = $booking->booking_count;
                }
            }
        }
    
        return view('landing.home', [
            'trips' => $trips,
            'popularTrips' => $popularTrips,
            'mostPopularTripId' => $mostPopularTripId,
            'totalBookings' => $totalBookings,
            'totalTrips' => $totalTrips,
            'totalCustomers'=>$totalCustomers, 
            'testimonials' => $testimonials,
            'photos' => $photos,
        ]);
    }


    public function reservationConfirmed($reservationID){
        try{
        $reservation = Reservations::findOrFail($reservationID);
        $customerName = $reservation->name;
        $customerEmail = $reservation->email;
        return view('reservation-confirmed', ['reservationID'=>$reservationID, 'customerName' => $customerName, 'customerEmail' => $customerEmail]);
        
        }
        catch(ModelNotFoundException $e){
            \Log::error("Reservation not found: {$reservationID}. Error: " . $e->getMessage());
            abort(404);
        }
    }


    public function getDestinationDetails($slug){
       
        $trip = TripsModel::where('slug', $slug)->where('active', true)->firstOrFail();
        $tripID = $trip->tripID;
        $isMostPopular = false;

        $testimonials = Testimonials::with('trip')
        ->where('tripID', $tripID)
        ->where('testimonial_approval_status', 'approved')
        ->get();

        $averageTestimonialRating = $testimonials->isNotEmpty() ? $testimonials->avg('trip_rating') : 0;
        // Combines both bookings and reservations into a single dataset.
        
        $mostPopularBooking = DB::table(function ($query) {
            $query->select('tripID', DB::raw('COUNT(*) as total_count'))
                ->from('bookings')
                ->groupBy('tripID')
                ->unionAll(
                    DB::table('reservations')
                        ->select('tripID', DB::raw('COUNT(*) as total_count'))
                        ->groupBy('tripID')
                );
        }, 'combined_counts')
            ->select('tripID', DB::raw('SUM(total_count) as booking_count'))
            ->groupBy('tripID')
            ->having('booking_count', '>', 2)
            ->orderByDesc('booking_count')
            ->first();

        if($mostPopularBooking && isset($tripID)){
            $isMostPopular = ($mostPopularBooking->tripID == $tripID);
        }

        // Old logic using Stripe, need to update to use Square
        
        // if ($mostPopularBooking) {
        //     // Retrieve the product from Stripe
        //     $product = $this->stripe->products->retrieve($mostPopularBooking->stripe_product_id);
    
        //     // Check if the current trip is the most popular
        //     $isMostPopular = $trip->stripe_product_id === $mostPopularBooking->stripe_product_id;

        // }
        
        return view('/landing/destination', [
            'tripID' => $tripID,
            'trip' => $trip,
            'testimonials' => $testimonials,
            'averageTestimonialRating' => $averageTestimonialRating,
            'isMostPopular' => $isMostPopular,
        ]);
    }

    public function destinationsPage(){
        // Fetch all trips
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice', 'slug', 'num_trips')->where('active', true)->get();
         
        // Fetch approved testimonials
        $testimonials = Testimonials::with('trip')->where('testimonial_approval_status', 'Approved')->get();
    
         /*
         The query retrieves the top 4 most popular trips based on the number of bookings.
          It joins the bookings table with the trips table to get additional trip details, 
          groups the results by trip and booking counts, 
          filters to include only trips with more than 2 bookings, 
          orders the trips by the number of bookings in descending order,
           and limits the results to the top 4 most popular trips.
         */
 
         $mostPopularBookings = BookingModel::select('bookings.tripID', DB::raw('COUNT(*) as booking_count'))
         ->where('trips.active', true)
         ->join('trips', 'bookings.tripID', '=', 'trips.tripID')
         ->groupBy('bookings.tripID', 'trips.tripID', 'trips.slug',  'trips.tripPhoto')
         ->having('booking_count', '>', 2)
         ->orderByDesc('booking_count')
         ->take(4)
         ->get();
 
    $popularTrips = [];
    $mostPopularTripId = null;
    $highestBookingCount = 0;
 
    foreach ($mostPopularBookings as $booking) {
        $trip = TripsModel::where('tripID', $booking->tripID)->first();
  
       
        if ($trip) {
            $popularTrips[] = [
                'id' => $trip->tripID,
                'slug'=>$trip->slug,
                'name' => $trip->tripLocation,
                'count' => $booking->booking_count,
                'image' => $trip->tripPhoto,
                // 'coupon'=>$trip->square_coupon_id,
            ];

            if ($booking->booking_count > $highestBookingCount) {
                $mostPopularTripId = $trip->tripID;
                $highestBookingCount = $booking->booking_count;
            }
        }
    }
 
    return view('landing/destinations', compact('trips', 'popularTrips', 'mostPopularTripId'));
         
     }

     public function bookingPage($slug, $reservationID = null) {
        try {
            $trip = TripsModel::where('slug', $slug)->firstOrFail();
            $tripID = $trip->tripID;
    
            // Check if reservationID is provided before finding the reservation
           $reservation = $reservationID ? Reservations::findOrFail($reservationID) : null;
            if($trip->active == false || $trip->num_trips == 0){
                abort(404); // If the trip is inactive, return page not found 
            }

            return view('booking/booking', [
                'tripID' => $tripID,
                'trip' => $trip,
                'reservationID' => $reservationID,
                'reservation' => $reservation
            ]);
        } catch (ModelNotFoundException $e) {
            \Log::error($e->getMessage());
            return redirect('/');
        }
    }
    
    
    

    public function aboutPage(){
        $totalBookings = BookingModel::count();
        $totalTrips = TripsModel::count();
        $response = $this->square->getCustomersApi()->listCustomers();

        if ($response->isSuccess()) {
            $customers = $response->getResult()->getCustomers(); // Fetch customers array
            $totalCustomers = is_array($customers) ? count($customers) : 0;
        } else {
            $totalCustomers = 0; // Default to 0 if API fails
        }

        return view('/landing/about', compact('totalBookings', 'totalTrips', 'totalCustomers'));
    }

    public function galleryPage(){
        return view('/landing/gallery');
    }

    public function contactPage(){
        return view('/landing/contact');
    }
}
