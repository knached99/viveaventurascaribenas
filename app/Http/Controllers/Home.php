<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Home extends Controller
{
    public function homePage(){
        return view('/landing/home');
    }

    public function aboutPage(){
        return view('/landing/about');
    }

    public function destinationsPage(){
        return view('/landing/destinations');
    }

    public function blogPage(){
        return view('/landing/blog');
    }

    public function galleryPage(){
        return view('/landing/gallery');
    }

    public function contactPage(){
        return view('/landing/contact');
    }
}
