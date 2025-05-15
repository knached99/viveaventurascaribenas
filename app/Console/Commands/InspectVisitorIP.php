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
            '2' => 'Inspect and plot multiple IPs'
        ], 0);

        return $action === '1'
            ? $this->inspectSingleIP()
            : $this->inspectAndPlotMultipleIPs();
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

        $choice = $this->choice('How many IPs would you like to view?', ['10', '50', '100', 'All'], 0);
        $limitedIPs = match ($choice) {
            '10' => $ipAddresses->take(10),
            '50' => $ipAddresses->take(50),
            '100' => $ipAddresses->take(100),
            default => $ipAddresses,
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
        $spinner->start('Fetching IP addresses from VisitorModel...');
        sleep(1);

        $encryptedIps = VisitorModel::pluck('visitor_ip_address')->unique()->values();
        if ($encryptedIps->isEmpty()) {
            $this->error("No IP addresses found.");
            return;
        }

        $spinner->finish('IP addresses fetched!');

        $choice = $this->choice('How many IPs would you like to inspect?', ['10', '50', '100', 'All'], 0);
        $limit = match ($choice) {
            '10' => 10,
            '50' => 50,
            '100' => 100,
            default => $encryptedIps->count(),
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

        $geoPoints = [];
        $chunks = array_chunk($decryptedIps, 10); // Process in batches of 10 to avoid rate-limiting

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
                usleep(300000); // 0.3s delay per IP to be kind to the API
            }
        }

        if (empty($geoPoints)) {
            $this->error("No valid geolocation data found.");
            return;
        }

        $markers = [];
        foreach ($geoPoints as $point) {
            $label = urlencode($point['ip']);
            $markers[] = "color:red|label:•|{$point['lat']},{$point['lon']}";
        }

        $mapUrl = "https://quickchart.io/map?markers=" . implode('&markers=', $markers);
        $this->info("Map URL with IP pins:\n$mapUrl");
    }
}
