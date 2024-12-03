<?php 
namespace App\Services;

use GeoIp2\Database\Reader;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MaxMindService
{
    protected $reader;

    public function __construct()
    {
        // Path to your GeoLite2 database file
        $databasePath = storage_path('app/mmdb/GeoLite2-Country_20241129/GeoLite2-Country_20241129/GeoLite2-Country.mmdb');
        if (!file_exists($databasePath)) {
            throw new \Exception('GeoLite2-Country database not found at ' . $databasePath);
        }

        // Initialize the MaxMind Reader
        $this->reader = new Reader($databasePath);
    }

    public function getLocation($ip)
    {
        $cacheKey = 'geo_location_' . md5($ip);

        // Check if location is cached
        $location = Cache::get($cacheKey);
        if ($location) {
            return $location;
        }

        try {
            // Validate IP
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                Log::warning("Invalid IP address: {$ip}");
                return null;
            }

            // Use MaxMind Reader to get location data
            $record = $this->reader->country($ip);

            $location = [
                'country' => $record->country->isoCode ?? null,
                'continent' => $record->continent->name ?? null,
                'is_eu' => $record->country->isInEuropeanUnion ?? false,
            ];

            // Cache the location for 1 day
            Cache::put($cacheKey, $location, 1440);

            return $location;
        } catch (\Exception $e) {
            Log::error("MaxMindService lookup failed for IP {$ip}: " . $e->getMessage());
            return null;
        }
    }
}
