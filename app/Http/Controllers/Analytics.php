<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MaxMindService;
use App\Models\VisitorModel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class Analytics extends Controller
{
    protected $maxMindService;
    protected $CrawlerDetect; 

    public function __construct(MaxMindService $maxMindService, CrawlerDetect $crawlerDetect)
    {
        $this->maxMindService = $maxMindService;
        $this->crawlerDetect = $crawlerDetect;
    
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
   



    // Implementing a more effective caching strategy 
    // caching both location and IP data for a week 
    public function showAnalytics()
    {
        $cacheDuration = now()->addWeek(); // Set cache expiration date

        // Check if analytics data is already cached
        if (Cache::has('analytics_data')) {
            $analyticsData = Cache::get('analytics_data');
            return view('admin.analytics', $analyticsData);
        }
    
        // Fetch and process data if cache is missing
        $visitors = VisitorModel::select(
            'visitor_uuid', 
            'visitor_ip_address', 
            'visitor_user_agent', 
            'visited_url', 
            'visitor_referrer', 
            'visited_at', 
            'unique_identifier'
        )->get()->toArray();
    
        // Calculate the most visited URL
        $mostVisitedURLs = array_column($visitors, 'visited_url');
        $urlCounts = array_count_values($mostVisitedURLs);
        arsort($urlCounts);
        $mostVisitedURL = array_key_first($urlCounts);
    
        // Get referrer data
        $visitorReferrers = DB::table('visitors')
            ->select('visitor_referrer')
            ->whereNotNull('visitor_referrer')
            ->get();
        
        $topReferrerURLs = $visitorReferrers->pluck('visitor_referrer')->toArray();
        $topReferrerURLs = array_map(fn($url) => is_string($url) && $url !== '' ? $url : 'unknown', $topReferrerURLs);
        $referrerURLCounts = array_count_values($topReferrerURLs);
        arsort($referrerURLCounts);
        $topReferrerURL = array_key_first($referrerURLCounts);
    
        // Count total visitors
        $totalVisitors = count($visitors);
    
        // Extract unique IP addresses and cache location data
        $ips = array_unique(array_column($visitors, 'visitor_ip_address'));
        $locations = [];
    
        foreach ($ips as $ip) {
            $locations[$ip] = Cache::remember("geo_" . md5($ip), now()->addWeek(), function () use ($ip) {
                return app(MaxMindService::class)->getLocation($ip);
            });
        }
    
        // Initialize aggregation variables
        $countries = [];
        $browsers = [];
        $operatingSystems = [];
        $locationCounts = [];
    
        // Processing visitors and aggregating the data

        foreach ($visitors as &$visitor) {
            $ip = $visitor['visitor_ip_address'];
            $location = $locations[$ip] ?? null;
    
            $visitor['country'] = $location['country'] ?? null;
            $visitor['continent'] = $location['continent'] ?? null;
            $visitor['state'] = $location['state'] ?? null;
            $visitor['city'] = $location['city'] ?? null;
            $visitor['latitude'] = $location['latitude'] ?? null;
            $visitor['longitude'] = $location['longitude'] ?? null;
    
            $parsedAgent = $this->parseUserAgent($visitor['visitor_user_agent']);
            $visitor['browser'] = $parsedAgent['browser'];
            $visitor['operating_system'] = $parsedAgent['os'];
    
            if (!empty($visitor['country'])) {
                $countries[] = $visitor['country'];
            }
    
            $browsers[$visitor['browser']] = ($browsers[$visitor['browser']] ?? 0) + 1;
            $os = $visitor['operating_system'] ?: 'Unknown';
            $operatingSystems[$os] = ($operatingSystems[$os] ?? 0) + 1;

            $topBrowsers = array_slice($browsers, 0, 5, true);
            $topOperatingSystems = array_slice($operatingSystems, 0, 5, true);
        
        // aggregating heatmap data 

        $country = $visitor['country'];
        $state = $visitor['state'];
        $city = $visitor['city'];
        $lat = $visitor['latitude'];
        $lon = $visitor['longitude'];

        if(!$country || !$state || !$city || $lat === null || $lon === null){
            continue; 
        }

        $key = "{$country}|{$state}|{$city}|{$lat}|{$lon}";

        if (!isset($locationCounts[$key])) {
            $locationCounts[$key] = [
                'country' => $country,
                'state' => $state,
                'city' => $city,
                'latitude' => $lat,
                'longitude' => $lon,
                'count' => 0
            ];
        }
        
            $locationCounts[$key]['count']++;
        }
    
        arsort($browsers);
        arsort($operatingSystems);
        $heatmapData = array_values($locationCounts);

            
        // Get bot crawler data
        $botData = $this->getBotCrawlers($visitors);
    
        // Store the timestamp for when the data was last refreshed
        $currentTimestamp = now()->toDateTimeString();
        $cache_expiration_date = $cacheDuration->toDateTimeString();
        // Prepare analytics data
        $analyticsData = [
            'topBrowsers' => $topBrowsers,
            'topOperatingSystems' => $topOperatingSystems,
            'heatmapData' => $heatmapData,
            'most_visited_url' => $mostVisitedURL,
            'topReferrerURL' => $topReferrerURL,
            'total_visitors_count' => $totalVisitors,
            'totalBots' => $botData['totalBots'],
            'mostFrequentBot' => $botData['mostFrequentBot'],
            'botPercentage' => $botData['botPercentage'],
            'realVisitorsPercentage' => $botData['realVisitorsPercentage'],
            'data_current_as_of' => $currentTimestamp, // Add timestamp
            'cache_expiration_date' => $cache_expiration_date // when the data will refresh
        ];
    
        // Store in cache for one week
        Cache::put('analytics_data', $analyticsData, now()->addWeek());
    
        return view('admin.analytics', $analyticsData);
    }
    
   

    /**
     * Parse user agent to get browser and operating system.
     *
     * @param string $userAgent
     * @return array
     */
    
     private function parseUserAgent($userAgent): array {
        
        $browser = 'unknown';
        $os = 'unknown';

        $userAgentsFile = storage_path('app/userAgents.json');

        if(!file_exists($userAgentsFile)){
            return ['browser' => $browser, 'os' => $os];
        }

        $userAgents = json_decode(file_get_contents($userAgentsFile), true);

        if(!is_array($userAgents)) {
            return ['browser' => $browser, 'os' => $os];
        }

        if ($browser === 'unknown' || $os === 'unknown') {
            \Log::info('Unknown User Agent Detected', ['ua' => $userAgent]);
        }
        

        // here, we will initialize the lists and 
        // iterate over each user agent for the browsers and os and 
        // we will collect those agents into these arrays 

        $browsers = [];
        $operatingSystems = [];

        // scanning user agents string and extracting all unique tokens 
        foreach($userAgents as $ua) {

            // browsers
            if (preg_match_all('/\b(Firefox|Chrome|Chromium|Safari|MSIE|Trident|Edge|Edg|Opera|OPR|SamsungBrowser|UCBrowser|QQBrowser|Baidu|Vivaldi|Maxthon|Iceweasel|IceCat|chromeframe)\b/i', $ua, $matches)) {
                foreach($matches[1] as $match) {
                    $browsers[$match] = $match;
                }
            }

            // operating systems 
            if (preg_match_all('/\b(Windows NT [\d.]+|Windows [\d.]+|Mac OS X|Mac_PowerPC|Android|Linux|iPhone|iPad|iPod|CrOS|BlackBerry|BB10|Tizen|WebOS|FreeBSD|OpenBSD|Nintendo|PlayStation)\b/i', $ua, $matches)) {
                  
                foreach($matches[1] as $match) {
                    $operatingSystems[$match] = $match;
                }
            }
        }

        foreach ($browsers as $webBrowser) {
            if (stripos($userAgent, $webBrowser) !== false) {
                $browser = $webBrowser;
                break;
            }
        }

        foreach ($operatingSystems as $operatingSystem) {
            if (stripos($userAgent, $operatingSystem) !== false) {
                $os = $operatingSystem;
                break;
            }
        }

        return [
            'browser' => $browser,
            'os' => $os,
        ];
    }

    // Determines if user agents are bots and returns number of bots found 

    private function getBotCrawlers(array $visitors)
    {
        $userAgents = array_column($visitors, 'visitor_user_agent');
    
        $botCounts = [];
        $totalBots = 0;
        $totalVisitors = count($visitors);
    
        foreach ($userAgents as $userAgent) {
            if ($this->crawlerDetect->isCrawler($userAgent)) {
                $totalBots++;
                $botName = $this->crawlerDetect->getMatches();
                $botCounts[$botName] = ($botCounts[$botName] ?? 0) + 1;
            }
        }
        // This is the most prevelant bot hitting the site 

        $mostFrequentBot = !empty($botCounts) ? array_keys($botCounts, max($botCounts))[0] : 'None';

        \Log::debug('Bot Counts:', $botCounts);

        

        // calculate the percentage of fake and real visitors 
        $botPercentage = ($totalVisitors > 0) ? ($totalBots / $totalVisitors) * 100 : 0;
        $realVisitorsPercentage = 100 - $botPercentage; 

        return [
            'totalBots' => $totalBots,
            'mostFrequentBot' => $mostFrequentBot,
            'botPercentage'=>$botPercentage,
            'realVisitorsPercentage'=>$realVisitorsPercentage,
        ];
    }
}
