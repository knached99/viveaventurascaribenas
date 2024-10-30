<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;


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
              $decryptedIP = Crypt::decryptString($ip);

                $response = $this->client->request('GET', "https://get.geojs.io/v1/ip/geo/{$decryptedIP}.json");
                $responseBody = $response->getBody()->getContents(); // Get response body as string
                $location = json_decode($responseBody, true);
        
                // Log the raw response body
                Log::info('Response: ' . $responseBody);
        
                // Log the decoded location array
                Log::info('Location: ' . print_r($location, true));
        
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
            'country' => $location['country'] ?? null,
            'latitude' => $location['latitude'] ?? null,
            'longitude' => $location['longitude'] ?? null,
        ];
    }
}
