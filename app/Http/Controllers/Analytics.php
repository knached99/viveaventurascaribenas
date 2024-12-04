<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MaxMindService;
use App\Models\VisitorModel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class Analytics extends Controller
{
    protected $maxMindService;

    public function __construct(MaxMindService $maxMindService)
    {
        $this->maxMindService = $maxMindService;
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

   
       $ips = $visitors->pluck('visitor_ip_address')->unique();
       $locations = [];
       $countries = [];
       $browsers = [];
       $operatingSystems = [];
   
       // Get location data based on IPs
       foreach ($ips as $ip) {
           $locations[$ip] = Cache::remember("geo_" . md5($ip), 1440, function () use ($ip) {
               return app(MaxMindService::class)->getLocation($ip);
           });
       }
   
       // Process visitors and aggregate data
       foreach ($visitors as $visitor) {
           $location = $locations[$visitor->visitor_ip_address] ?? null;
   
           $visitor->country = $location['country'] ?? null;
           $visitor->continent = $location['continent'] ?? null;
   
           $parsedAgent = $this->parseUserAgent($visitor->visitor_user_agent);
           $visitor->browser = $parsedAgent['browser'];
           $visitor->operating_system = $parsedAgent['os'];
   
           if ($visitor->country) {
               $countries[] = $visitor->country;
           }
   
           $browsers[$visitor->browser] = ($browsers[$visitor->browser] ?? 0) + 1;
           $os = $visitor->operating_system ?: 'Unknown';
           $operatingSystems[$os] = ($operatingSystems[$os] ?? 0) + 1;
       }
   
       $topBrowsers = collect($browsers)->sortDesc()->take(5);
       $topOperatingSystems = collect($operatingSystems)->sortDesc()->take(5);
   
       $heatmapData = collect($countries)
           ->countBy()
           ->map(function ($count, $country) {
               return ['country' => $country, 'count' => $count];
           })
           ->values()
           ->toArray(); 
    
       return view('admin.analytics', compact('topBrowsers', 'topOperatingSystems', 'heatmapData', 'most_visited_url', 'total_visitors_count'));
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
