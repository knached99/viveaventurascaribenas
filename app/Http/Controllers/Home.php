<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripsModel;
use App\Models\Testimonials;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\StripeClient;
use App\Models\BookingModel;
use Illuminate\Database\Eloquent\ModelNotFoundException; 
use Illuminate\Support\Str;


class Home extends Controller
{


    public function __construct(){
         Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
         $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

    }

    public function homePage()
    {
        // Fetch all trips
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice', 'stripe_product_id')->get();
        
        // Fetch approved testimonials
        $testimonials = Testimonials::with('trip')->where('testimonial_approval_status', 'Approved')->get();
    
        // Find the top 4 most popular bookings with more than one entry
        $mostPopularBookings = BookingModel::select('stripe_product_id')
            ->selectRaw('COUNT(*) as booking_count')
            ->groupBy('stripe_product_id')
            ->having('booking_count', '>', 1) // Ensure only bookings with more than 1 entry are considered
            ->orderByDesc('booking_count')
            ->take(4) // Get the top 4 most popular bookings
            ->get();
    
        $popularTrips = [];
        $mostPopularTripIds = [];
    
        foreach ($mostPopularBookings as $booking) {
            // Retrieve the product from Stripe using the stripe_product_id
            $product = $this->stripe->products->retrieve($booking->stripe_product_id);
            
            // Fetch the trip details based on the product ID
            $trip = TripsModel::where('stripe_product_id', $booking->stripe_product_id)->first();
            
            if ($trip) {
                $popularTrips[] = [
                    'id' => $trip->tripID,
                    'name' => $product->name,
                    'count' => $booking->booking_count,
                    'image' => $trip->tripPhoto, // Use trip photo or default image
                ];
                $mostPopularTripIds[] = $trip->tripID; // Collect trip IDs directly
            }
        }
    
        return view('landing.home', [
            'trips' => $trips,
            'testimonials' => $testimonials,
            'popularTrips' => $popularTrips,
            'mostPopularTripIds' => $mostPopularTripIds
        ]);
    }
    
    

    public function getDestinationDetails($tripID){
        $trip = TripsModel::where('tripID', $tripID)->firstOrFail();
        $testimonials = Testimonials::with('trip')
            ->where('tripID', $tripID)
            ->where('testimonial_approval_status', 'approved')
            ->get();
        $averageTestimonialRating = $testimonials->isNotEmpty() ? $testimonials->avg('trip_rating') : 0;
    
        // Retrieve the most popular booking
        $mostPopularBooking = BookingModel::select('stripe_product_id')
            ->selectRaw('COUNT(*) as booking_count') 
            ->groupBy('stripe_product_id')
            ->having('booking_count', '>', 1) // only bookings with more than 1 entry are considered
            ->orderByDesc('booking_count')
            ->first();
        
        $isMostPopular = false;
    
        if ($mostPopularBooking) {
            // Retrieve the product from Stripe
            $product = $this->stripe->products->retrieve($mostPopularBooking->stripe_product_id);
    
            // Check if the current trip is the most popular
            $isMostPopular = $trip->stripe_product_id === $mostPopularBooking->stripe_product_id;
        }
    
        return view('/landing/destination', [
            'tripID' => $tripID,
            'trip' => $trip,
            'testimonials' => $testimonials,
            'averageTestimonialRating' => $averageTestimonialRating,
            'isMostPopular' => $isMostPopular
        ]);
    }
    

    public function destinationsPage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice')->get();
         // Find the top 4 most popular bookings with more than one entry
         $mostPopularBookings = BookingModel::select('stripe_product_id')
         ->selectRaw('COUNT(*) as booking_count')
         ->groupBy('stripe_product_id')
         ->having('booking_count', '>', 1) // Ensure only bookings with more than 1 entry are considered
         ->orderByDesc('booking_count')
         ->take(4) // Get the top 4 most popular bookings
         ->get();
 
     $popularTrips = [];
 
     foreach ($mostPopularBookings as $booking) {
         // Retrieve the product from Stripe using the stripe_product_id
         $product = $this->stripe->products->retrieve($booking->stripe_product_id);
         
         // Fetch the trip details based on the product ID
         $trip = TripsModel::where('stripe_product_id', $booking->stripe_product_id)->first();
 
         $popularTrips[] = [
             'id' => $trip->tripID,
             'name' => $product->name,
             'count' => $booking->booking_count,
             'image' => $trip ? $trip->tripPhoto : 'path/to/default-image.jpg' // Use trip photo or default image
         ];
     }

        return view('/landing/destinations', compact('trips', 'popularTrips'));
    }

    public function bookingPage($tripID){
        try{
        
            $trip = TripsModel::findOrFail($tripID);
        
        return view('booking/booking', ['tripID'=>$tripID, 'trip'=>$trip]);
        }
        
        catch(\Exception $e){
            \Log::error($e->getMessage());
        return redirect('/');
        }
    }

    public function bookingSuccess(Request $request)
    {
        $tripID = $request->query('tripID');
        $sessionID = $request->query('session_id');
    
        // Retrieve the Stripe session using the session ID
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $session = $stripe->checkout->sessions->retrieve($sessionID);
    
        if (!$tripID || !$sessionID) {
            abort(404);
        }
    
        // Check if the session exists and has the correct payment status
        if ($session && $session->payment_status == 'paid') {
            // Check if a booking already exists with the same stripe_checkout_id
            $existingBooking = BookingModel::where('stripe_checkout_id', $session->id)->first();
            
            if ($existingBooking) {
                return redirect()->route('booking.cancel', ['tripID' => $tripID])
                                 ->with('message', 'Booking already completed.');
            }
    
            try {
                BookingModel::create([
                    'bookingID' => STR::uuid(),
                    'tripID' => $session->metadata->tripID ?? null,
                    'name' => $session->metadata->name ?? 'N/A',
                    'email' => $session->email ?? 'N/A',
                    'phone_number' => $session->metadata->phone_number ?? 'N/A',
                    'address_line_1' => $session->metadata->address_line_1 ?? 'N/A',
                    'address_line_2' => $session->metadata->address_line_2 ?? '',
                    'city' => $session->metadata->city ?? 'N/A',
                    'state' => $session->metadata->state ?? 'N/A',
                    'zip_code' => $session->metadata->zipcode ?? '00000',
                    'stripe_checkout_id' => $session->id,
                    'stripe_product_id' => $session->metadata->stripe_product_id ?? null,
                ]);
    
                // Pass the metadata to the view
                return view('booking.success', [
                    'customerName' => $session->metadata->name,
                    'customerEmail' => $session->metadata->email,
                    'tripID' => $tripID,
                ]);
            } catch (\Exception $e) {
                \Log::error('Uncaught database or stripe exception in class: ' . __CLASS__ . ' On line: ' . __LINE__ . ' Error Message: ' . $e->getMessage());
                abort(500);
            }
        } else {
            // Handle the case where the payment was not successful or the session is invalid
            return redirect()->route('booking.cancel', ['tripID' => $tripID]);
        }
    }
    
    
    
    public function bookingCancel(Request $request, $tripID)
    {
        $stripeSessionId = $request->query('session_id'); // assuming you pass session_id as a query parameter
        
        $session = null;
        if ($stripeSessionId) {
            // Fetch the Stripe session using the session ID
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            try {
                $session = $stripe->checkout->sessions->retrieve($stripeSessionId);
            } catch (\Exception $e) {
                \Log::error('Failed to retrieve Stripe session: ' . $e->getMessage());
                // Optionally handle the error or set $session to null if an error occurs
                $session = null;
            }
        }
        
        return view('booking.cancel', [
            'tripID' => $tripID,
            'name' => $session && $session->metadata->name,
            'email' => $session && $session->customer_email,
            'stripe_session_id' => $session && $session->id ,
        ]);
    }
    
    


    public function galleryPage(){
        return view('/landing/gallery');
    }

    public function contactPage(){
        return view('/landing/contact');
    }
}
