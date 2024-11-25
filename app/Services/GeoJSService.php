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
        $cacheKey = 'geo_location_' . md5($ip);
        $rateLimitKey = 'geo_location_rate_' . md5($ip);
        $rateLimitWindow = 60; // In seconds
        $rateLimitMaxRequests = 5;
    
        $location = Cache::get($cacheKey);
    
        if (!$location) {
            $requestCount = Cache::get($rateLimitKey, 0);
    
            if ($requestCount >= $rateLimitMaxRequests) {
                Log::warning("Rate limit exceeded for IP: {$ip}");
                return null;
            }
    
            try {
                Cache::put($rateLimitKey, $requestCount + 1, $rateLimitWindow);
    
                // Decrypt IP or use as-is
                $decryptedIP = $this->tryDecrypt($ip);
                $geoLocationAPIKey = env('IPGEOLOCATION_API_KEY');
    
                // Validate the IP format
                if (!filter_var($decryptedIP, FILTER_VALIDATE_IP)) {
                    Log::warning("Invalid IP format: {$decryptedIP}");
                    return null;
                }
    
                // Log the IP being sent to the API
                Log::info("Requesting geo-location for IP: {$decryptedIP}");
    
                // API request
                $request = $this->client->request('GET', "https://api.ipgeolocation.io/ipgeo?apiKey={$geoLocationAPIKey}&ip={$decryptedIP}");
                //  $response = $this->client->request('GET', "http://ip-api.com/json/{$decryptedIP}");
                $responseBody = $response->getBody()->getContents();
                $location = json_decode($responseBody, true);
    
                // Check if the API response is valid
                if ($location['status'] == 'fail') {
                    Log::error("Geo-location lookup failed for IP {$decryptedIP}: {$location['message']}");
                    return null;
                }
    
                // Cache the location for 1 day
                Cache::put($cacheKey, $location, 1440);
            } catch (\Exception $e) {
                Log::error('GeoJS lookup failed for IP ' . $ip . '. Exception: ' . $e->getMessage(), ['ip' => $ip, 'apiKey' => $geoLocationAPIKey]);
                $location = null;
            }
        }
    
        return [
            'city' => $location['city'] ?? null,
            'region' => $location['state_prov'] ?? null,
            'country' => $location['country_name'] ?? null,
        ];
    }
    
    // This method attempts to decrypt an encrypted IP and returns that decrypted IP
    // If the IP cannot be decrypted, it will return null
    private function tryDecrypt($ip)
    {
        try {
            $decryptedIP = Crypt::decryptString($ip);
    
            // Ensure the decrypted value is a valid IP
            if (filter_var($decryptedIP, FILTER_VALIDATE_IP)) {
                return $decryptedIP;
            } else {
                Log::warning("Decrypted value is not a valid IP: {$decryptedIP}");
                return null; // Return null to indicate failure
            }
        } catch (\Exception $e) {
            // Log decryption failure
            Log::info('IP is not encrypted, using as-is: ' . $ip);
    
            // Validate the original IP before returning it
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            } else {
                Log::warning("Original value is not a valid IP: {$ip}");
                return null; // Return null to indicate failure
            }
        }
    }
    

}
