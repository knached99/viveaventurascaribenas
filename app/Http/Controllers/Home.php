<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripsModel;
use App\Models\Testimonials;
use App\Models\PhotoGalleryModel;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\StripeClient;
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
         Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
         $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
         $this->bookingID = Str::uuid();

    }
    
   
    public function termsAndConditions(){
        return view('landing.terms');
    }

    public function userPrivacy(){
        return view('landing.privacy');
    }


    // Optimized for performance 
    public function homePage()
    {

        $totalBookings = BookingModel::count();
        $totalTrips = TripsModel::count();
        $customers = $this->stripe->customers->all();
        $totalCustomers = count($customers);
       

        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice', 'slug',  'num_trips', 'stripe_product_id', 'stripe_coupon_id')->where('active', true)->get();
        $testimonials = Testimonials::with('trip')->where('testimonial_approval_status', 'Approved')->get();
        
        $photos = PhotoGalleryModel::with(['trip'])->select('photoID', 'photoLabel', 'photoDescription', 'photos', 'tripID')->get();
        
        $mostPopularBookings = BookingModel::select('bookings.stripe_product_id', DB::raw('COUNT(*) as booking_count'))
            ->where('trips.active', true)
            ->join('trips', 'bookings.stripe_product_id', '=', 'trips.stripe_product_id')
            ->groupBy('bookings.stripe_product_id', 'trips.tripID', 'trips.slug',  'trips.tripPhoto')
            ->having('booking_count', '>', 2)
            ->orderByDesc('booking_count')
            ->take(4)
            ->get();
    
        $popularTrips = [];
        $mostPopularTripId = null;
        $highestBookingCount = 0;
    
        foreach ($mostPopularBookings as $booking) {
            $product = $this->stripe->products->retrieve($booking->stripe_product_id);
    
            $trip = TripsModel::where('stripe_product_id', $booking->stripe_product_id)->first();
    
            if ($trip) {
                $popularTrips[] = [
                    'id' => $trip->tripID,
                    'slug'=>$trip->slug,
                    'name' => $product->name,
                    'count' => $booking->booking_count,
                    'image' => $trip->tripPhoto,
                    'coupon'=>$trip->stripe_coupon_id,
                    
                ];
    
                if ($booking->booking_count > $highestBookingCount) {
                    $mostPopularTripId = $trip->tripID; // Update to use trip ID directly
                    $highestBookingCount = $booking->booking_count;
                }
            }
        }

        return view('landing.home', [
            'trips' => $trips,
            'testimonials' => $testimonials,
            'popularTrips' => $popularTrips,
            'mostPopularTripId' => $mostPopularTripId, // Pass the most popular trip ID
            'photos'=>$photos,
            'totalBookings' => $totalBookings,
            'totalTrips' => $totalTrips,
            'totalCustomers'=>$totalCustomers, 
        ]);
    }

    
    

    public function getDestinationDetails($slug){
       
        $trip = TripsModel::where('slug', $slug)->where('active', true)->firstOrFail();

        $tripID = $trip->tripID;

        $testimonials = Testimonials::with('trip')
            ->where('tripID', $tripID)
            ->where('testimonial_approval_status', 'approved')
            ->get();
        $averageTestimonialRating = $testimonials->isNotEmpty() ? $testimonials->avg('trip_rating') : 0;
    
        // Retrieve the most popular booking
        $mostPopularBooking = BookingModel::select('stripe_product_id')
            ->selectRaw('COUNT(*) as booking_count') 
            ->groupBy('stripe_product_id')
            ->having('booking_count', '>', 2) // only bookings with more than 1 entry are considered
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
            'isMostPopular' => $isMostPopular,
        ]);
    }
    

    public function destinationsPage(){
       // Fetch all trips
       $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice', 'slug', 'num_trips', 'stripe_product_id', 'stripe_coupon_id')->where('active', true)->get();
        
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

       $mostPopularBookings = BookingModel::select('bookings.stripe_product_id', DB::raw('COUNT(*) as booking_count'))
       ->join('trips', 'bookings.stripe_product_id', '=', 'trips.stripe_product_id')
       ->groupBy('bookings.stripe_product_id', 'trips.tripID', 'trips.slug', 'trips.tripPhoto')
       ->having('booking_count', '>', 2)
       ->orderByDesc('booking_count')
       ->take(4)
       ->get();

   $popularTrips = [];
   $mostPopularTripId = null;
   $highestBookingCount = 0;

   foreach ($mostPopularBookings as $booking) {
       $product = $this->stripe->products->retrieve($booking->stripe_product_id);

       $trip = TripsModel::where('stripe_product_id', $booking->stripe_product_id)->first();

       if ($trip) {
           $popularTrips[] = [
               'id' => $trip->tripID,
               'slug'=>$trip->slug,
               'name' => $product->name,
               'count' => $booking->booking_count,
               'image' => $trip->tripPhoto,
               'coupon'=>$trip->stripe_coupon_id,

           ];

           if ($booking->booking_count > $highestBookingCount) {
               $mostPopularTripId = $trip->tripID; // Update to use trip ID directly
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
        } catch (\ModelNotFoundException $e) {
            \Log::error($e->getMessage());
            return redirect('/');
        }
    }
    
    
    public function bookingSuccess(Request $request)
    {
        $tripID = $request->query('tripID');
        $trip = TripsModel::findOrFail($tripID);
        $sessionID = $request->query('session_id');
    
        // Validate tripID and sessionID
        if (empty($tripID) || empty($sessionID)) {
            abort(404, 'Trip ID or Session ID is missing.');
        }
    
        // Initialize Stripe client
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    
        try {
            // Retrieve the Stripe session using the session ID
            $session = $stripe->checkout->sessions->retrieve($sessionID, [
                'expand'=>['payment_intent']
            ]);
    
            // Check if the session exists and has the correct payment status
            if ($session && $session->payment_status == 'paid') {
                // Check if a booking already exists with the same stripe_checkout_id
                $existingBooking = BookingModel::where('stripe_checkout_id', $session->id)->first();
    
                if ($existingBooking) {
                    return redirect()->route('booking.cancel', ['tripID' => $tripID])
                                     ->with('message', 'Booking already completed.');
                }
                $paymentIntent = $session->payment_intent;
                if(is_object($paymentIntent)){
                $amount_charged_in_cents = $paymentIntent->amount_received;
                $amount_charged_in_dollars = $amount_charged_in_cents / 100;
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
                    'preferred_start_date'=>$session->metadata->preferred_start_date,
                    'preferred_end_date'=>$session->metadata->preferred_end_date,
                    'amount_captured'=>$amount_charged_in_dollars ?? 0.00,
                    'stripe_checkout_id' => $session->id,
                    'stripe_product_id' => $session->metadata->stripe_product_id ?? null,
                ]);
                $trip->num_trips -=1;
                $trip->save();
    
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

                // Temporary Signed URL is valid for 2 minutes 
                $signedURLSuccess = URL::temporarySignedRoute(
                    'booking.success', now()->addMinutes(2), ['tripID' => $tripID]
                );

                $signedURLCancel = URL::temporarySignedRoute(
                    'booking.cancel', now()->addMinutes(2), ['tripID' => $tripID]
                );
    
                // Send notifications
                Notification::route('mail', config('mail.mailers.smtp.to_email'))
                    ->notify(new BookingSubmittedAdmin($session->metadata->name, $this->bookingID));
    
                    Notification::route('mail', $session->metadata->email)
                    ->notify(new BookingSubmittedCustomer(
                        $session->metadata->name,
                        $trip->tripLocation,
                        $trip->tripPhoto,
                        $session->metadata->preferred_start_date,
                        $session->metadata->preferred_end_date,
                        $this->bookingID,
                        $receiptLink
                    ));
                
                // Pass the metadata to the view
                return view('booking.success', [
                    'customerName' => $session->metadata->name,
                    'customerEmail' => $session->metadata->email,
                    'tripID' => $tripID,
                    'signedURL'=>$signedURLSuccess
                ]);
            } else {
                // Handle the case where the payment was not successful or the session is invalid
                return redirect()->route('booking.cancel', ['tripID' => $tripID, 'name'=>base64_encode($this->name), 'email'=>base64_encode($this->email),  'signedURL'=>$signedURLCancel]);
            }

            Cache::flush();
        } catch (\Exception $e) {
            \Log::error('Uncaught database or stripe exception in class: ' . __CLASS__ . ' On line: ' . __LINE__ . ' Error Message: ' . $e->getMessage());
            abort(500, 'An error occurred while processing your booking.');
        }
    }
    
    
    
    public function bookingCancel(Request $request)
    {
        \Log::info('Full URL: '.$request->fullUrl());
        $stripeSessionId = $request->query('session_id'); 
        // $name = $request->query('name') ? base64_decode($request->query('name')) : null;
        // $email = $request->query('email') ? base64_decode($request->query('email')) : null;
        $queryName = base64_decode($request->query('name'));
        $queryEmail = base64_decode($request->query('email'));
        $tripID = $request->query('tripID');
        $session = null;
        if ($stripeSessionId) {
            // Fetch the Stripe session using the session ID
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            try {
                $session = $stripe->checkout->sessions->retrieve($stripeSessionId);
            } catch (\Exception $e) {
                \Log::error('Failed to retrieve Stripe session: ' . $e->getMessage());
                $session = null;
            }
        }
    
        // Use query parameters if provided; otherwise, fall back to session metadata
        $name = $queryName ?? ($session && $session->metadata->name);
        $email = $queryEmail ?? ($session && $session->customer_email);
    
        return view('booking.cancel', [
            'tripID' => $tripID,
            'name' => $name,
            'email' => $email,
            'stripe_session_id' => $session && $session->id,
        ]);
    }
    

    public function reservationConfirmed($reservationID){
        $reservation = Reservations::find($reservationID);
        $customerName = $reservation->name;
        $customerEmail = $reservation->email;
        return view('reservation-confirmed', ['reservationID'=>$reservationID, 'customerName' => $customerName, 'customerEmail' => $customerEmail]);
    }
    
    

    public function aboutPage(){
        $totalBookings = BookingModel::count();
        $totalTrips = TripsModel::count();
        $customers = $this->stripe->customers->all();
        $totalCustomers = count($customers);
        return view('/landing/about', compact('totalBookings', 'totalTrips', 'totalCustomers'));
    }

    public function galleryPage(){
        return view('/landing/gallery');
    }

    public function contactPage(){
        return view('/landing/contact');
    }
}
