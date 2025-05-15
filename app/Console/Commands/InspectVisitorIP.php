<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VisitorModel;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class InspectVisitorIP extends Command
{
    protected $signature = 'inspect:ip';
    protected $description = 'Inspect visitor IPs and get geolocation data';

    public function handle()
    {
        $action = $this->choice('Select an action:', [
            '1' => 'Inspect a single IP',
            '2' => 'Inspect and plot multiple IPs',
            '3' => 'Search for IPs by country',
        ], 0);

        match($action){
            '1' => $this->inspectSingleIP(),
            '2' => $this->inspectAndPlotMultipleIPs(),
            '3' => $this->searchIPsByCountry(),
        };
    }

    protected function inspectSingleIP()
    {
        $output = new ConsoleOutput();
        $spinner = new ProgressIndicator($output);
        $spinner->start('Fetching IP addresses from VisitorModel...');
        sleep(1);

        $ipAddresses = VisitorModel::pluck('visitor_ip_address')->unique()->values();
        if ($ipAddresses->isEmpty()) {
            $this->error("No IP addresses found.");
            return;
        }

        $spinner->finish('IP addresses fetched!');

        $choice = $this->choice('How many IPs would you like to view?', ['10', '50', '100', '1000'], 0);
        $limitedIPs = match ($choice) {
            '10' => $ipAddresses->take(10),
            '50' => $ipAddresses->take(50),
            '100' => $ipAddresses->take(100),
            '1000' => $ipAddresses->take(1000),
            'default'=>$ipAddresses->take(5),
        };

        $ipArray = $limitedIPs->values()->toArray();
        foreach ($ipArray as $index => $ip) {
            $this->line("[$index] $ip");
        }

        $index = (int) $this->ask("Select an IP by index");

        if (!isset($ipArray[$index])) {
            $this->error('Invalid choice selected');
            return;
        }

        $selectedIp = $ipArray[$index];
        $decryptedSelectedIP = Crypt::decryptString($selectedIp);
        $this->info("You selected IP: $decryptedSelectedIP");

        $ipDetails = VisitorModel::where('visitor_ip_address', $selectedIp)->first();

        if (!$ipDetails) {
            $this->warn('No additional data found for this IP in the database.');
        } else {
            $this->info('Database record for this IP:');
            $this->table(['Field', 'Value'], collect($ipDetails)->map(fn($v, $k) => [$k, $v])->toArray());
        }

        $this->info('Fetching geolocation data...');
        $response = Http::get("http://ip-api.com/json/{$decryptedSelectedIP}");

        if ($response->failed() || $response->json()['status'] === 'fail') {
            $this->error('Failed to fetch geolocation data.');
            return;
        }

        $geoData = $response->json();
        $this->info('Geolocation Data:');
        $this->table(['Field', 'Value'], collect($geoData)->map(fn($v, $k) => [$k, $v])->toArray());
    }


    protected function inspectAndPlotMultipleIPs()
{
    $output = new ConsoleOutput();
    $spinner = new ProgressIndicator($output);

    // Step 1: Fetch IPs
    $spinner->start('Fetching IP addresses from VisitorModel...');
    sleep(1);

    $encryptedIps = VisitorModel::pluck('visitor_ip_address')->unique()->values();
    if ($encryptedIps->isEmpty()) {
        $spinner->finish('No IP addresses found.');
        $this->error("No IP addresses found.");
        return;
    }

    $spinner->finish('IP addresses fetched!');

    // Step 2: User selects number of IPs
    $choice = $this->choice('How many IPs would you like to inspect?', ['10', '50', '100', '1000'], 0);
    $limit = match ($choice) {
        '10' => 10,
        '50' => 50,
        '100' => 100,
        '1000' => 1000,
    };

    $limitedIps = $encryptedIps->take($limit)->values()->toArray();
    $decryptedIps = [];

    foreach ($limitedIps as $encryptedIp) {
        try {
            $decryptedIps[] = Crypt::decryptString($encryptedIp);
        } catch (\Exception $e) {
            continue;
        }
    }

    if (empty($decryptedIps)) {
        $this->error("No IPs could be decrypted.");
        return;
    }

    // Step 3: Geolocate with progress bar
    $geoPoints = [];
    $progressBar = $this->output->createProgressBar(count($decryptedIps));
    $progressBar->start();

    $chunks = array_chunk($decryptedIps, 10);

    foreach ($chunks as $chunk) {
        foreach ($chunk as $ip) {
            $response = Http::get("http://ip-api.com/json/{$ip}");
            if ($response->successful() && $response->json('status') === 'success') {
                $geoPoints[] = [
                    'ip' => $ip,
                    'lat' => $response->json('lat'),
                    'lon' => $response->json('lon'),
                    'country' => $response->json('country')
                ];
            }
            usleep(300000);
            $progressBar->advance();
        }
    }

    $progressBar->finish();
    $this->newLine();

    if (empty($geoPoints)) {
        $this->error("No valid geolocation data found.");
        return;
    }

    // Step 4: Generate dark-mode hacker-style map
    $html = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>Visitor IP Map</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            body {
                background-color: #0d0d0d;
                color: #00ff00;
                font-family: "Courier New", monospace;
                margin: 0;
                padding: 0;
                text-align: center;
            }
            h2 {
                margin: 20px 0;
            }
            #map { height: 90vh; width: 100%; border-top: 2px solid #00ff00; background-color: #000;}
        </style>
    </head>
    <body>
        <h2>🌐 Tracing Visitor IPs...</h2>
        <div id="map"></div>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            var map = L.map('map', {
                zoomControl: false
            }).setView([20, 0], 2);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);

            var greenIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
    HTML;

    foreach ($geoPoints as $point) {
        $ip = htmlspecialchars($point['ip'], ENT_QUOTES);
        $lat = $point['lat'];
        $lon = $point['lon'];
        $country = htmlspecialchars($point['country'], ENT_QUOTES);
        $html .= "\nL.marker([$lat, $lon], {icon: greenIcon}).addTo(map).bindPopup('<b>{$ip}</b><br>{$country}');";
    }

    $html .= <<<HTML

        </script>
    </body>
    </html>
    HTML;

    $filename = public_path('visitor_map.html');

    if(file_exists($filename)){
        unlink($filename);
    }

    file_put_contents($filename, $html);
    $mapURL = env('APP_URL') . 'visitor_map.html';

    $this->info("🛰️  Map generated! View it at:\n$mapURL");
}


protected function searchIPsByCountry(){

    $country = $this->ask("Enter a country name (e.g. Mexico): ");

    $encryptedIPs = VisitorModel::pluck('visitor_ip_address')->unique()->values();

    if($encryptedIPs->isEmpty()){
        $this->error("No IP addresses found");
        return;
    }

    $limit = 100;

    $decryptedIPs = [];

    foreach($encryptedIps as $ip){
        try {

            $decryptedIPs[] = Crypt::decryptString(10);
        }

        catch(\Exception $e){
            continue;
        }
    }

    $matchingIPs = [];

    foreach($decryptedIPs as $ip){

        $response = Http::get("http://ip-api.com/json/{$ip}");

        if($response->successful() && $response->json('status') === 'success'){

            $data = $response->json();

            if(Str::lower($data['country']) === Str::lower($country)){
                
                $matchingIPs[] = [
                    'ip'=>$ip,
                    'city'=>$data['city'] ?? 'N/A',
                    'region'=>$data['regionName'] ?? 'N/A',
                    'lat' => $data['lat'],
                    'lon' => $data['lon'],
                ];

                if(count($matchingIPs) >= $limit) break;
            }
        }

        usleep(300000); // avoiding rate limits
    }

    if(empty($matchingIPs)){
        $this->warn("No IPs found for: $country");
        return;
    }

    $this->info("Found the following IPs for {$country}:");
    $this->table(['IP', 'City', 'Region/State', 'Latitude', 'Longitude'], $matchingIPs);

    $this->generateMapForGeoPoints($matchingIPs, $title =  "🌍 IPs from {$country}");
}


protected function generateMapForGeoPoints(array $geoPoints, string $title = '🌍 Visitor IPs')
{
    $html = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>{$title}</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            body {
                background-color: #0d0d0d;
                color: #00ff00;
                font-family: "Courier New", monospace;
                margin: 0;
                padding: 0;
                text-align: center;
            }
            h2 { margin: 20px 0; }
            #map { height: 90vh; width: 100%; border-top: 2px solid #00ff00; background-color: #000;}
        </style>
    </head>
    <body>
        <h2>{$title}</h2>
        <div id="map"></div>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            var map = L.map('map', { zoomControl: false }).setView([20, 0], 2);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);

            var greenIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
    HTML;

    foreach ($geoPoints as $point) {
        $ip = htmlspecialchars($point['ip'], ENT_QUOTES);
        $lat = $point['lat'];
        $lon = $point['lon'];
        $city = htmlspecialchars($point['city'] ?? '', ENT_QUOTES);
        $region = htmlspecialchars($point['region'] ?? '', ENT_QUOTES);
        $html .= "\nL.marker([$lat, $lon], {icon: greenIcon}).addTo(map).bindPopup('<b>{$ip}</b><br>{$city}, {$region}');";
    }

    $html .= <<<HTML

        </script>
    </body>
    </html>
    HTML;

    $filename = public_path('visitor_map.html');
    if(file_exists($filename)) unlink($filename);
    file_put_contents($filename, $html);

    $mapURL = env('APP_URL') . 'visitor_map.html';
    $this->info("🗺️  Map generated! View it at:\n$mapURL");
}

   
}
