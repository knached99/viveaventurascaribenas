<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripsModel;
use App\Models\Testimonials;
use Stripe\Stripe;
use App\Models\BookingModel;
use Illuminate\Database\Eloquent\ModelNotFoundException; 
use Illuminate\Support\Str;


class Home extends Controller
{


    public function __construct(){
         Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function homePage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice')->get();
        $testimonials = Testimonials::with('trip')->where('testimonial_approval_status', 'Approved')->get();
        
        return view('/landing/home', compact('trips', 'testimonials'));
    }

    public function getDestinationDetails($tripID){
        $trip = TripsModel::where('tripID', $tripID)->firstOrFail();
        $testimonials = Testimonials::with('trip')->where('tripID', $tripID)->where('testimonial_approval_status', 'approved')->get();
        $averageTestimonialRating = $testimonials->isNotEmpty() ? $testimonials->avg('trip_rating') : 0;

        return view('/landing/destination', ['tripID'=>$tripID, 'trip'=>$trip, 'testimonials'=>$testimonials, 'averageTestimonialRating'=>$averageTestimonialRating]);
    }

    public function aboutPage(){
        return view('/landing/about');
    }

    public function destinationsPage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice')->get();

        return view('/landing/destinations', compact('trips'));
    }

    public function bookingPage($tripID){
        try{
        TripsModel::findOrFail($tripID);
        return view('booking/booking', ['tripID'=>$tripID]);
        }
        catch(\Exception $e){
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
