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
    
        $visitors = VisitorModel::select(
            'visitor_uuid', 
            'visitor_ip_address', 
            'visitor_user_agent', 
            'visited_url', 
            'visitor_referrer', 
            'visited_at', 
            'unique_identifier'
        )
        ->whereNotNull('visitor_uuid')
        ->whereNotNull('visitor_ip_address')
        ->whereNotNull('visitor_user_agent')
        ->whereNotNull('visited_url')
        ->whereNotNull('visitor_referrer')
        ->whereNotNull('visited_at')
        ->whereNotNull('unique_identifier')
        ->get()
        ->toArray();

   
       // Calculate the most visited URL
       $mostVisitedURLs = array_column($visitors, 'visited_url');
       $urlCounts = array_count_values($mostVisitedURLs);
       arsort($urlCounts);
       $mostVisitedURL = array_key_first($urlCounts);


       // Calcualte where most of the referrers are from 
        $topReferrerURLs = array_column($visitors, 'visitor_referrer');

        // Replacing null or non string values with 'unknown' 
        $topReferrerURLs = array_map(
            fn($url) => is_string($url) && $url !== '' ? $url : 'unknown',
            $topReferrerURLs
        );

        // counting total number of occurrences of each referrer 

        $referrerURLCounts = array_count_values($topReferrerURLs);

        arsort($referrerURLCounts);

        // Here, we're retrieving the most common referrer url 

        $topReferrerURL = array_key_first($referrerURLCounts);
        
       // Count total visitors
       $totalVisitors = count($visitors);
   
       // Extract unique IP addresses
       $ips = array_unique(array_column($visitors, 'visitor_ip_address'));
   
       // Fetch and cache location data for IPs
       $locations = [];
       foreach ($ips as $ip) {
           $locations[$ip] = Cache::remember("geo_" . md5($ip), 1440, function () use ($ip) {
               return app(MaxMindService::class)->getLocation($ip);
           });
       }
   
       // Initialize aggregation variables
       $countries = [];
       $browsers = [];
       $operatingSystems = [];
   
       // Process visitors and aggregate data
       foreach ($visitors as &$visitor) {
           $ip = $visitor['visitor_ip_address'];
           $location = $locations[$ip] ?? null;
   
           $visitor['country'] = $location['country'] ?? null;
           $visitor['continent'] = $location['continent'] ?? null;
   
           $parsedAgent = $this->parseUserAgent($visitor['visitor_user_agent']);
           $visitor['browser'] = $parsedAgent['browser'];
           $visitor['operating_system'] = $parsedAgent['os'];
   
           if (!empty($visitor['country'])) {
               $countries[] = $visitor['country'];
           }
   
           $browsers[$visitor['browser']] = ($browsers[$visitor['browser']] ?? 0) + 1;
           $os = $visitor['operating_system'] ?: 'Unknown';
           $operatingSystems[$os] = ($operatingSystems[$os] ?? 0) + 1;
       }
   
       // Sort browsers and operating systems in descending order
       arsort($browsers);
       arsort($operatingSystems);
   
       // Get the top 5 browsers and operating systems
       $topBrowsers = array_slice($browsers, 0, 5, true);
       $topOperatingSystems = array_slice($operatingSystems, 0, 5, true);
   
       // Prepare heatmap data
       $heatmapData = [];
       foreach (array_count_values($countries) as $country => $count) {
           $heatmapData[] = ['country' => $country, 'count' => $count];
       }
   
       // Return the view with calculated data
       return view('admin.analytics', [
           'topBrowsers' => $topBrowsers,
           'topOperatingSystems' => $topOperatingSystems,
           'heatmapData' => $heatmapData,
           'most_visited_url' => $mostVisitedURL,
           'topReferrerURL' => $topReferrerURL,
           'total_visitors_count' => $totalVisitors,
       ]);
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
