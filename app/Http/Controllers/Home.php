<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripsModel;
use App\Models\Testimonials;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\StripeClient;
use App\Models\BookingModel;
use App\Models\Reservations;
use Illuminate\Database\Eloquent\ModelNotFoundException; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingSubmittedAdmin;
use App\Notifications\BookingSubmittedCustomer;
class Home extends Controller
{


    public function __construct(){
         Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
         $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
         $this->bookingID = Str::uuid();

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

        return view('/landing/destinations', compact('trips', 'popularTrips', 'mostPopularTripIds'));
        
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
    
        // Validate tripID and sessionID
        if (empty($tripID) || empty($sessionID)) {
            abort(404, 'Trip ID or Session ID is missing.');
        }
    
        // Initialize Stripe client
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    
        try {
            // Retrieve the Stripe session using the session ID
            $session = $stripe->checkout->sessions->retrieve($sessionID);
    
            // Check if the session exists and has the correct payment status
            if ($session && $session->payment_status == 'paid') {
                // Check if a booking already exists with the same stripe_checkout_id
                $existingBooking = BookingModel::where('stripe_checkout_id', $session->id)->first();
    
                if ($existingBooking) {
                    return redirect()->route('booking.cancel', ['tripID' => $tripID])
                                     ->with('message', 'Booking already completed.');
                }
    
                // Create a new booking record
                BookingModel::create([
                    'bookingID' => $this->bookingID,
                    'tripID' => $session->metadata->tripID ?? null,
                    'name' => $session->metadata->name ?? 'N/A',
                    'email' => $session->metadata->email ?? 'N/A',
                    'phone_number' => $session->metadata->phone_number ?? 'N/A',
                    'address_line_1' => $session->metadata->address_line_1 ?? 'N/A',
                    'address_line_2' => $session->metadata->address_line_2 ?? '',
                    'city' => $session->metadata->city ?? 'N/A',
                    'state' => $session->metadata->state ?? 'N/A',
                    'zip_code' => $session->metadata->zipcode ?? '00000',
                    'stripe_checkout_id' => $session->id,
                    'stripe_product_id' => $session->metadata->stripe_product_id ?? null,
                ]);
    
                // Retrieve charges and receipt link
                $receiptLink = null;
                if (!empty($session->payment_intent)) {
                    $charges = $stripe->charges->all(['payment_intent' => $session->payment_intent]);
    
                    if (!empty($charges->data) && count($charges->data) > 0) {
                        $receiptLink = $charges->data[0]->receipt_url;
                    }
                }
    
                // Retrieve the Stripe product
                if (!empty($session->metadata->stripe_product_id)) {
                    $product = $stripe->products->retrieve($session->metadata->stripe_product_id);
                }
    
                // Send notifications
                Notification::route('mail', config('mail.mailers.smtp.to_email'))
                    ->notify(new BookingSubmittedAdmin($session->metadata->name, $this->bookingID));
    
                Notification::route('mail', $session->metadata->email)
                    ->notify(new BookingSubmittedCustomer($session->metadata->name, $this->bookingID, $receiptLink));
    
                // Pass the metadata to the view
                return view('booking.success', [
                    'customerName' => $session->metadata->name,
                    'customerEmail' => $session->metadata->email,
                    'tripID' => $tripID,
                ]);
            } else {
                // Handle the case where the payment was not successful or the session is invalid
                return redirect()->route('booking.cancel', ['tripID' => $tripID]);
            }
        } catch (\Exception $e) {
            \Log::error('Uncaught database or stripe exception in class: ' . __CLASS__ . ' On line: ' . __LINE__ . ' Error Message: ' . $e->getMessage());
            abort(500, 'An error occurred while processing your booking.');
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

    public function reservationConfirmed($reservationID){
        $reservation = Reservations::find($reservationID);
        $customerName = $reservation->name;
        $customerEmail = $reservation->email;
        return view('reservation-confirmed', ['reservationID'=>$reservationID, 'customerName' => $customerName, 'customerEmail' => $customerEmail]);
    }
    
    

    public function aboutPage(){
        return view('/landing/about');
    }

    public function galleryPage(){
        return view('/landing/gallery');
    }

    public function contactPage(){
        return view('/landing/contact');
    }
}
