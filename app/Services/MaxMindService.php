<?php 
namespace App\Services;

use GeoIp2\Database\Reader;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class MaxMindService
{
    protected $reader;

    public function __construct()
    {
        
        $databasePath = storage_path('app/mmdb/GeoLite2-Country_20241129/GeoLite2-Country_20241129/GeoLite2-Country.mmdb');
        if (!file_exists($databasePath)) {
            throw new \Exception('GeoLite2-Country database not found at ' . $databasePath);
        }

        // Initialize the MaxMind Reader
        $this->reader = new Reader($databasePath);
    }

    public function getLocation($ip)
    {

        // Decrypt IP 
        $decryptedIP = Crypt::decryptString($ip);

        $cacheKey = 'geo_location_' . md5($decryptedIP);

        // Check if location is cached
        $location = Cache::get($cacheKey);
        if ($location) {
            return $location;
        }

        try {
            // Validate IP
            // if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            //     Log::warning("Invalid IP address: {$decryptedIP}");
            //     return null;
            // }

            // Use MaxMind Reader to get location data
            $record = $this->reader->country($decryptedIP);

            $location = [
                'country' => $record->country->isoCode ?? null,
                'continent' => $record->continent->name ?? null,
                'is_eu' => $record->country->isInEuropeanUnion ?? false,
            ];

            // Cache the location for 1 day
            Cache::put($cacheKey, $location, 1440);

            return $location;
        } catch (\Exception $e) {
            Log::error("MaxMindService lookup failed for IP {$decryptedIP}: " . $e->getMessage());
            return null;
        }
    }
}
