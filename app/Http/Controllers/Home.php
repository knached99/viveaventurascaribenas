<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TripsModel;

class Home extends Controller
{
    public function homePage(){
        $trips = TripsModel::all(); 
        return view('/landing/home', compact('trips'));
    }

    public function aboutPage(){
        return view('/landing/about');
    }

    public function destinationsPage(){
        return view('/landing/destinations');
    }


    public function galleryPage(){
        return view('/landing/gallery');
    }

    public function contactPage(){
        return view('/landing/contact');
    }
}
