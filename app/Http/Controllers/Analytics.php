<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeoJSService;
use App\Models\VisitorModel;

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
            $url->country = $this->geoJSService->getLocation(\Crypt::decryptString($url->visitor_ip_address))['country'] ?? null;
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
    protected function parseUserAgent($userAgent)
    {
        $browser = 'Unknown';
        $os = 'Unknown';

        if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            $browser = 'Internet Explorer';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            $browser = 'Opera';
        }

        if (strpos($userAgent, 'Windows NT 10.0') !== false) {
            $os = 'Windows 10';
        } elseif (strpos($userAgent, 'Windows NT 6.3') !== false) {
            $os = 'Windows 8.1';
        } elseif (strpos($userAgent, 'Windows NT 6.2') !== false) {
            $os = 'Windows 8';
        } elseif (strpos($userAgent, 'Windows NT 6.1') !== false) {
            $os = 'Windows 7';
        } elseif (strpos($userAgent, 'Mac OS X') !== false) {
            $os = 'Mac OS X';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $os = 'Android';
        } elseif (strpos($userAgent, 'iPhone') !== false) {
            $os = 'iOS';
        }

        return [
            'browser' => $browser,
            'os' => $os
        ];
    }
}
