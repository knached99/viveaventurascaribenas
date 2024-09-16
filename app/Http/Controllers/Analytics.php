<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeoJSService;
use App\Models\VisitorModel;

class Analytics extends Controller
{
    protected $geoJSService;

    public function __construct(GeoJSService $geoJSService){
        $this->geoJSService = $geoJSService;
    }

    public function showAnalytics(){

        $visitors = VisitorModel::all();

        $ips = $visitors->pluck('visitor_unique_address')->unique();

        $locations = [];

        foreach($ips as $ip){
            $locations[$ip] = $this->geoJSService->getLocation($ip);
        }

        foreach($visitors as $visitor){
            $location = $locations[$visitor->visitor_ip_address] ?? null;
            $visitor->city = $location['city'] ?? null;
            $visitor->state = $location['region'] ?? null;
            $visitor->country = $location['country'] ?? null;
        }
        
        return view('admin.analytics', compact('visitors'));
    }
}
