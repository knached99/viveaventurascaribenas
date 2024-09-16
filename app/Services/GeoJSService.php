<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeoJSService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getLocation($ip)
    {
        $cacheKey = "geo_location_{$ip}";

        // Attempt to retrieve the location from cache
        $location = Cache::get($cacheKey);

        if (!$location) {
            try {
                $response = $this->client->request('GET', "https://get.geojs.io/v1/ip/geo/{$ip}.json");
                $location = json_decode($response->getBody(), true);

                // Cache the result for 1 day
                Cache::put($cacheKey, $location, 1440);
            } catch (\Exception $e) {
                Log::error('GeoJS lookup failed: ' . $e->getMessage());
                $location = null;
            }
        }

        return [
            'city' => $location['city'] ?? null,
            'region' => $location['region'] ?? null,
            'country' => $location['country'] ?? null
        ];
    }
}
