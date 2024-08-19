<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TripsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Admin extends Controller
{
    public function dashboardPage(){
        return view('admin/dashboard');
    }

    public function profilePage(){
        return view('admin/profile');
    }


    public function tripsPage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice')->get()->take(5);
        return view('admin/trips', compact('trips'));
    }

    public function allTripsPage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice')->get();
        
        return view('admin/all-trips', compact('trips'));
    }



}
