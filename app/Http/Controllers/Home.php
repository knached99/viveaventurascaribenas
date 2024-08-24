<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripsModel;
use App\Models\Testimonials;

class Home extends Controller
{
    public function homePage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice')->get();
        $testimonials = Testimonials::where('testimonial_approval_status', 'Approved')->get();

        return view('/landing/home', compact('trips', 'testimonials'));
    }

    public function aboutPage(){
        return view('/landing/about');
    }

    public function destinationsPage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice')->get();

        return view('/landing/destinations', compact('trips'));
    }


    public function galleryPage(){
        return view('/landing/gallery');
    }

    public function contactPage(){
        return view('/landing/contact');
    }
}
