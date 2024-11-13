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
        return view('terms_and_conditions');
    }

    public function userPrivacy(){
        return view('privacy');
    }

    // Optimized for performance 
    public function homePage()
    {
        $totalBookings = BookingModel::count();
        $totalTrips = TripsModel::count();
        $customers = $this->stripe->customers->all();
        $totalCustomers = count($customers);

        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice', 'slug', 'stripe_product_id', 'stripe_coupon_id')->where('active', true)->get();
        $testimonials = Testimonials::with('trip')->where('testimonial_approval_status', 'Approved')->get();
        
       

        $photos = PhotoGalleryModel::with(['trip'])->select('photoID', 'photoLabel', 'photoDescription', 'photos', 'tripID')->get();
        
        $mostPopularBookings = BookingModel::select('bookings.stripe_product_id', DB::raw('COUNT(*) as booking_count'))
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
            'isMostPopular' => $isMostPopular
        ]);
    }
    

    public function destinationsPage(){
       // Fetch all trips
       $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice', 'slug', 'stripe_product_id', 'stripe_coupon_id')->where('active', true)->get();
        
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
        $email = base64_decode($request->query('email'));
        $sessionID = $request->query('session_id');
    
        if (empty($tripID) || empty($sessionID)) {
            abort(404, 'Trip ID or Session ID is missing.');
        }
    
        $trip = TripsModel::findOrFail($tripID);
        $reservation = Reservations::where('tripID', $tripID)->where('email', $email)->firstOrFail();
    
        // Initialize Stripe client
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    
        try {
            // Retrieve the Stripe session and payment intent
            $session = $stripe->checkout->sessions->retrieve($sessionID, ['expand' => ['payment_intent']]);
    
            if ($session && $session->payment_status === 'paid') {
                // Prevent duplicate bookings
                if (BookingModel::where('stripe_checkout_id', $session->id)->exists()) {
                    return redirect()->route('booking.cancel', ['tripID' => $tripID])
                        ->with('message', 'Booking already completed.');
                }
    
                // Capture payment details
                $paymentIntent = $session->payment_intent;
                $amountCharged = isset($paymentIntent->amount_received) ? $paymentIntent->amount_received / 100 : 0.00;
    
                // Create booking record
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
                    'amount_captured' => $amountCharged,
                    'stripe_checkout_id' => $session->id,
                    'stripe_product_id' => $session->metadata->stripe_product_id ?? null,
                ]);
    
                // Update trip availability and delete reservation
                $trip->decrement('num_trips');
                $reservation->delete();
    
                // Retrieve receipt link if available
                $receiptLink = optional($stripe->charges->all(['payment_intent' => $session->payment_intent])->data[0] ?? null)->receipt_url;
    
                // Generate signed URLs for success and cancel routes
                $signedURLSuccess = URL::temporarySignedRoute('booking.success', now()->addMinutes(2), ['tripID' => $tripID]);
                $signedURLCancel = URL::temporarySignedRoute('booking.cancel', now()->addMinutes(2), ['tripID' => $tripID]);
    
                // Send notifications to admin and customer
                Notification::route('mail', config('mail.mailers.smtp.to_email'))
                    ->notify(new BookingSubmittedAdmin($session->metadata->name, $this->bookingID));
                Notification::route('mail', $session->metadata->email)
                    ->notify(new BookingSubmittedCustomer($session->metadata->name, $this->bookingID, $receiptLink));
    
                // Render success view
                return view('booking.success', [
                    'customerName' => $session->metadata->name,
                    'customerEmail' => $session->metadata->email,
                    'tripID' => $tripID,
                    'signedURL' => $signedURLSuccess
                ]);
            }
    
            // Redirect if payment was unsuccessful
            return redirect()->route('booking.cancel', ['tripID' => $tripID, 'signedURL' => $signedURLCancel]);
    
        } catch (\Exception $e) {
            \Log::error("Error processing booking in " . __METHOD__ . ": " . $e->getMessage());
            abort(500, 'An error occurred while processing your booking.');
        } finally {
            Cache::flush();
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
