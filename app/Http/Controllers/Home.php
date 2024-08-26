<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripsModel;
use App\Models\Testimonials;

class Home extends Controller
{
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
        return view('/booking', ['tripID'=>$tripID]);
    }


    public function galleryPage(){
        return view('/landing/gallery');
    }

    public function contactPage(){
        return view('/landing/contact');
    }
}
