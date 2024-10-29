<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeoJSService;
use App\Models\VisitorModel;
use Illuminate\Support\Facades\Crypt;


class Analytics extends Controller
{
    protected $geoJSService;

    public function __construct(GeoJSService $geoJSService)
    {
        $this->geoJSService = $geoJSService;
    }

    public function showAnalytics()
    {
        $visitors = VisitorModel::all();

        // Unique IPs
        $ips = $visitors->pluck('visitor_ip_address')->unique();

        $locations = [];

        // Get location data based on IPs
        foreach ($ips as $ip) {
            $locations[$ip] = $this->geoJSService->getLocation($ip);
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
        // $deviceData = $visitors->groupBy('device')->map(function ($group) {
        //     return $group->count();
        // })->toArray();

        // Unique IPs for the IP chart
        $ipData = VisitorModel::selectRaw('visitor_ip_address, COUNT(*) as ip_count')
            ->groupBy('visitor_ip_address')
            ->get();

        // User Agents data
        $userAgentsData = VisitorModel::selectRaw('visitor_user_agent, COUNT(*) as agent_count')
            ->groupBy('visitor_user_agent')
            ->orderBy('agent_count', 'DESC')
            ->take(5) // Get top 5 user agents
            ->get();

        // URLs and referrers data
           // URLs and referrers data
        $urlData = VisitorModel::selectRaw('visited_url, visitor_ip_address, visitor_user_agent, created_at, COUNT(*) as visit_count, visitor_referrer')
        ->groupBy('visited_url', 'visitor_ip_address', 'visitor_user_agent', 'visitor_referrer', 'created_at')
        ->orderBy('visit_count', 'DESC')
        ->get()
        ->map(function ($url) {
            $parsedAgent = $this->parseUserAgent($url->visitor_user_agent);
            $url->browser = $parsedAgent['browser'];
            $url->operating_system = $parsedAgent['os'];
            $url->state = $this->geoJSService->getlocation(Crypt::decryptString($url->visitor_ip_address))['region'] ?? null;
            $url->city = $this->geoJSService->getLocation(Crypt::decryptString($url->visitor_ip_address))['city'] ?? null;
            $url->country = $this->geoJSService->getLocation(Crypt::decryptString($url->visitor_ip_address))['country'] ?? null;
            $url->latitude = $this->geoJSService->getLocation(Crypt::decryptString($url->visitor_ip_address))['latitude'] ?? null;
            $url->longitude = $this->geoJSService->getLocation(Crypt::decryptString($url->visitor_ip_address))['longitude'] ?? null;
            return $url;
        });

        // Log URL Data

        // Heatmap data based on visitor's country
        $heatmapData = [];

        foreach ($visitors as $visitor) {
            if ($visitor->latitude && $visitor->longitude) {
                $heatmapData[] = [
                    'lat' => $visitor->latitude,
                    'lng' => $visitor->longitude,
                    'count' => 1 // Adjust count or intensity if necessary
                ];
            }
        }

        return view('admin.analytics', compact(
            'visitorData',
            //'deviceData', // Include device data
            'ipData',
            'userAgentsData',
            'urlData',
            'heatmapData'
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
