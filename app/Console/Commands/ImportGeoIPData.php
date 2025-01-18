<?php 
use Illuminate\Console\Command;
use App\Models\GeoIP;
use Illuminate\Support\Facades\DB;

class ImportGeoIPData extends Command
{
    protected $signature = 'geoip:import {locations} {ipv4} {ipv6}';
    protected $description = 'Import GeoIP data from MaxMind CSV files';

    public function handle()
    {
        $locationsFile = $this->argument('locations');
        $ipv4File = $this->argument('ipv4');
        $ipv6File = $this->argument('ipv6');

        if (!file_exists($locationsFile) || !file_exists($ipv4File) || !file_exists($ipv6File)) {
            $this->error('One or more files not found.');
            return;
        }

        // Step 1: Load Locations Data
        $locations = [];
        $handle = fopen($locationsFile, 'r');
        $header = fgetcsv($handle); // Skip header
        while (($row = fgetcsv($handle)) !== false) {
            $locations[$row[0]] = [ // Map geoname_id to country data
                'continent_code' => $row[2],
                'continent_name' => $row[3],
                'country_iso_code' => $row[4],
                'is_in_european_union' => $row[5],
            ];
        }
        fclose($handle);

        // Helper to process IP blocks
        $this->processIPBlocks($ipv4File, $locations, 'IPv4');
        $this->processIPBlocks($ipv6File, $locations, 'IPv6');

        $this->info('GeoIP data imported successfully.');
    }

    private function processIPBlocks($file, $locations, $type)
    {
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle); // Skip header

        while (($row = fgetcsv($handle)) !== false) {
            $geonameId = $row[1];
            $locationData = $locations[$geonameId] ?? null;

            GeoIP::create([
                'network' => $row[0],
                'geoname_id' => $row[1],
                'registered_country_geoname_id' => $row[2],
                'represented_country_geoname_id' => $row[3],
                'is_anonymous_proxy' => $row[4],
                'is_satellite_provider' => $row[5],
                'is_anycast' => $row[6],
                'country_iso_code' => $locationData['country_iso_code'] ?? null,
                'continent_code' => $locationData['continent_code'] ?? null,
                'continent_name' => $locationData['continent_name'] ?? null,
                'is_in_european_union' => $locationData['is_in_european_union'] ?? false,
            ]);
        }

        fclose($handle);
    }
}
