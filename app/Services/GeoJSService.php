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


    // Attempt to retrieve the location from cache
    $location = Cache::get($cacheKey);

    if (!$location) {
        try {
            // Attempt to decrypt the IP, or use as-is if not encrypted
            $decryptedIP = $this->tryDecrypt($ip);

            // Perform API request 
            $apiKey = env('IPGEOLOCATION_API_KEY');
            $response = $this->client->request('GET', 'https://freeipapi.com/api/json/{$decryptedIP}');
           // $response = $this->client->request('GET', "https://api.ipgeolocation.io/ipgeo?apiKey={$apiKey}&ip={$decryptedIP}");
            $responseBody = $response->getBody()->getContents(); // Get response body as string
            $location = json_decode($responseBody, true);

            // Log the response for debugging
            Log::info('API Response for IP ' . $decryptedIP . ': ' . $responseBody);

            // Cache the result for 1 day (1440 minutes)
            Cache::put($cacheKey, $location, 1440);
        } catch (\Exception $e) {
            Log::error('GeoJS lookup failed for IP ' . $ip . ': ' . $e->getMessage());
            $location = null;
        }
    }

    // Map the API response to the required fields
    return [
        'city' => $location['cityName'] ?? null,
        'region' => $location['regionName'] ?? null,
        'country' => $location['countryName'] ?? null,
        'latitude' => $location['latitude'] ?? null,
        'longitude' => $location['longitude'] ?? null,
    ];
}


    // public function getLocation($ip)
    // {
    //     $cacheKey = "geo_location_{$ip}";

    //     // Attempt to retrieve the location from cache
    //     $location = Cache::get($cacheKey);

    //     if (!$location) {
    //         try {
    //           $decryptedIP = Crypt::decryptString($ip);

    //            // $response = $this->client->request('GET', "https://get.geojs.io/v1/ip/geo/{$decryptedIP}.json");
    //            $response = $this->client->request('GET', "https://freeipapi.com/api/json/{$decryptedIP}");
    //             $responseBody = $response->getBody()->getContents(); // Get response body as string
    //             $location = json_decode($responseBody, true);
        
    //             // Log the raw response body
    //             Log::info('Response: ' . $responseBody);
        
    //             // Log the decoded location array
    //             Log::info('Location: ' . print_r($location, true));
        
    //             // Cache the result for 1 day
    //             Cache::put($cacheKey, $location, 1440);
    //         } catch (\Exception $e) {
    //             Log::error('GeoJS lookup failed: ' . $e->getMessage());
    //             $location = null;
    //         }
    //     }
        
    //     return [
    //         'city'=>$location['cityName'] ?? null,
    //         'region'=>$location['regionName'] ?? null,
    //         'country'=>$location['countryName'] ?? null,
    //         'latitude'=>$location['latitude'] ?? null,
    //         'longitude'=>$location['longitude'] ?? null,
    //         // 'city' => $location['city'] ?? null,
    //         // 'region' => $location['region'] ?? null,
    //         // 'country' => $location['country'] ?? null,
    //         // 'latitude' => $location['latitude'] ?? null,
    //         // 'longitude' => $location['longitude'] ?? null,
    //     ];
    // }

//     public function getLocation($ip)
// {
//     $cacheKey = "geo_location_{$ip}";

//     // Attempt to retrieve the location from cache
//     $location = Cache::get($cacheKey);

//     if (!$location) {
//         try {
//           //  $decryptedIP = Crypt::decryptString($ip);

//             // $response = $this->client->request('GET', "https://freeipapi.com/api/json/{$ip}");
//             $response = $this->client->request('GET', "https://api.ipgeolocation.io/ipgeo?apiKey=b42ca3aa642e43c49a7f7a1ee3d4ca3f&ip={$ip}");
//             $responseBody = $response->getBody()->getContents(); // Get response body as string
//             $location = json_decode($responseBody, true);

//             // Log the response for debugging
//             Log::info('API Response for IP ' . $ip . ': ' . $responseBody);

//             // Cache the result for 1 day (1440 minutes)
//             Cache::put($cacheKey, $location, 1440);
//         } catch (\Exception $e) {
//             Log::error('GeoJS lookup failed for IP ' . $ip . ': ' . $e->getMessage());
//             $location = null;
//         }
//     }

//     // Map the API response to the required fields
//     return [
//         'city' => $location['city'] ?? null,
//         'region' => $location['state_prov'] ?? null,
//         'country' => $location['country_name'] ?? null,
//         'latitude' => $location['latitude'] ?? null,
//         'longitude' => $location['longitude'] ?? null,
//     ];
// }

/**
 * Attempt to decrypt the IP address, or return it as is if not encrypted.
 */
private function tryDecrypt($ip)
{
    try {
        return Crypt::decryptString($ip);
    } catch (\Exception $e) {
        // Log decryption failure for debugging, proceed with the plain IP
        Log::info('IP is not encrypted, using as is: ' . $ip);
        return $ip;
    }
}

}
