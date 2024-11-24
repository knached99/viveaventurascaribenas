<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeoJSService;
use App\Models\VisitorModel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class Analytics extends Controller
{
    protected $geoJSService;

    public function __construct(GeoJSService $geoJSService)
    {
        $this->geoJSService = $geoJSService;
    }


   public static function quickAnalytics(){
    // Static method call in the Admin dashboard page, retrieves 
    // most visited urls and total number of visits 

    $visitors = VisitorModel::select('visited_url', 'visitor_user_agent', 'visited_at')->get()->toArray();
    // Return the values from a single column in the mostVisitedURLs array
    $mostVisitedURLs = array_column($visitors, 'visited_url');
    
    
    // Counts all the values of an array

    $urlCounts = array_count_values($mostVisitedURLs);

    // Sort an array in reverse order and maintain index association

    arsort($urlCounts);
    //Gets the first key of an array
    // Gets the most visited URL (first element in the sorted array)
    $mostVisitedURL = array_key_first($urlCounts);

    $totalVisitors = count($visitors);

    return [
        'most_visited_url' => $mostVisitedURL,
        'total_visitors_count' => $totalVisitors
    ];

   }
   

   public function showAnalytics()
   {
       $visitors = VisitorModel::all();
   
       // Unique IPs
       $ips = $visitors->pluck('visitor_ip_address')->unique();
   
       $locations = [];
       $browsers = [];
       $operatingSystems = [];
   
       // Get location data based on IPs with caching
       foreach ($ips as $ip) {
           // Check cache first before making API call
           $locations[$ip] = Cache::remember("geo_" . substr(md5($ip), 0, 32), 1440, function() use ($ip) {

               return $this->geoJSService->getLocation($ip);
           });
       }
   
       // Attach city, state, and country to each visitor
       foreach ($visitors as $visitor) {
           $location = $locations[$visitor->visitor_ip_address] ?? null;
           $visitor->city = $location['city'] ?? null;
           $visitor->state = $location['region'] ?? null;
           $visitor->country = $location['country'] ?? null;
           // Parse user agent to get browser and OS
           $parsedAgent = $this->parseUserAgent($visitor->visitor_user_agent);
           $visitor->browser = $parsedAgent['browser'];
           $visitor->operating_system = $parsedAgent['os'];
       }
   
       // Group visitors by date for the 'Unique Visitors' chart
       $visitorData = VisitorModel::selectRaw('DATE(visited_at) as visit_date, COUNT(*) as visit_count')
           ->groupBy('visit_date')
           ->orderBy('visit_date')
           ->get();
   
       // Aggregate device data
       $ipData = VisitorModel::selectRaw('visitor_ip_address, COUNT(*) as ip_count')
           ->groupBy('visitor_ip_address')
           ->get();
   
       // User Agents data
       $userAgentsData = VisitorModel::selectRaw('visitor_user_agent, COUNT(*) as agent_count')
           ->groupBy('visitor_user_agent')
           ->orderBy('agent_count', 'DESC')
           ->take(5)
           ->get();
   
       // URLs and referrers data
       $urlData = VisitorModel::selectRaw('visited_url, visitor_ip_address, visitor_user_agent, created_at, COUNT(*) as visit_count, visitor_referrer')
           ->groupBy('visited_url', 'visitor_ip_address', 'visitor_user_agent', 'visitor_referrer', 'created_at')
           ->orderBy('visit_count', 'DESC')
           ->get()
           ->map(function ($url) use ($locations) {
               // Use cached location data for IP lookup
               $location = $locations[Crypt::decryptString($url->visitor_ip_address)] ?? null;
               $url->geoLocation = $location;
               $url->city = $location['city'] ?? 'Unknown City';
               $url->state = $location['region'] ?? 'Unknown State';
               $url->country = $location['country'] ?? 'Unknown Country';
               $url->latitude = $location['latitude'] ?? null;
               $url->longitude = $location['longitude'] ?? null;
   
               $parsedAgent = $this->parseUserAgent($url->visitor_user_agent);
               $url->browser = $parsedAgent['browser'] ?? 'Unknown Browser';
               $url->operating_system = $parsedAgent['os'] ?? 'Unknown OS';
   
               return $url;
           });
   
       foreach ($urlData as $url) {
           // Update browser count
           if (isset($browsers[$url->browser])) {
               $browsers[$url->browser] += $url->visit_count;
           } else {
               $browsers[$url->browser] = $url->visit_count;
           }
   
           // Update operating system count, sanitized for valid key
           $os = isset($url->operating_system) ? preg_replace('/[^a-zA-Z0-9\s]/', '', $url->operating_system) : 'Unknown';
           if (isset($operatingSystems[$os])) {
               $operatingSystems[$os] += $url->visit_count;
           } else {
               $operatingSystems[$os] = $url->visit_count;
           }
       }
   
       $topBrowsers = collect($browsers)->sortDesc();
       $topOperatingSystems = collect($operatingSystems)->sortDesc();
   
       // Prepare heatmap data
       $heatmapData = $visitors->groupBy('country')->map(function ($group) {
           return ['country' => $group->first()->country, 'count' => $group->count()];
       })->values()->toArray();
   
       return view('admin.analytics', compact(
           'visitorData',
           'ipData',
           'userAgentsData',
           'urlData',
           'heatmapData',
           'topBrowsers',
           'topOperatingSystems',
       ));
   }
   

    /**
     * Parse user agent to get browser and operating system.
     *
     * @param string $userAgent
     * @return array
     */

     // Refactored code for readibility. Also time complexity is O(1) because we're using a hashmap 
    protected function parseUserAgent($userAgent)
    {
        $browser = 'Unknown';
        $os = 'Unknown';
    
        // Browser detection using a hash map (associative array)
        $browserMap = [
            'MSIE' => 'Internet Explorer',
            'Trident' => 'Internet Explorer',
            'Firefox' => 'Firefox',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'Opera' => 'Opera',
            'OPR' => 'Opera'
        ];
    
        foreach ($browserMap as $key => $name) {
            if (strpos($userAgent, $key) !== false) {
                $browser = $name;
                break;
            }
        }
    
        // OS detection using a hash map
        $osMap = [
            'Windows NT 11.0' => 'Windows 11',
            'Windows NT 10.0' => 'Windows 10',
            'Windows NT 6.3' => 'Windows 8.1',
            'Windows NT 6.2' => 'Windows 8',
            'Windows NT 6.1' => 'Windows 7',
            'Mac OS X' => 'Mac OS X',
            'Android' => 'Android',
            'iPhone' => 'iOS'
        ];
    
        foreach ($osMap as $key => $name) {
            if (strpos($userAgent, $key) !== false) {
                $os = $name;
                break;
            }
        }
    
        return [
            'browser' => $browser,
            'os' => $os
        ];
    }
    
}
