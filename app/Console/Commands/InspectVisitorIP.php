<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VisitorModel;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\Crypt;

class InspectVisitorIP extends Command {

    protected $signature = 'inspect:ip';
    protected $description = 'Inspect visitor IPs and get geolocation data';
    
    

    public function handle(){

        $output = new ConsoleOutput();
        $spinner = new ProgressIndicator($output);
        $spinner->start('Fetching IP addresses from VisitorModel...');
        sleep(1); // Optional delay to show the spinner

        $ipAddresses = VisitorModel::pluck('visitor_ip_address')->unique()->values();

        if($ipAddresses->isEmpty()){
            $this->error("No IP addresses found.");
            return;
        }


        $spinner->finish('IP addresses fetched!');

        $choice = $this->choice(
            'How many IPs would you like to view?',
            ['10', '50', '100', 'All'],
            0
        );

        switch($choice){
            case '10':
                $limitedIPs = $ipAddresses->take(10);
                break;

            case '50':
                $limitedIPs = $ipAddresses->take(50);
                break;

            case '100':
                $limitedIPs = $ipAddresses->take(100);
                break;

            case 'All':
                $limitedIPs = $ipAddresses;
                break;
        }

        $ipArray = $limitedIPs->values()->toArray();

        foreach($ipArray as $index => $ip){
            $this->line("[$index] $ip");
        }

        $index = (int) $this->ask("Select an IP by index");

        if(!isset($ipArray[$index])){
            $this->error('Invalid choice selected');
            return;
        }
        $selectedIp = $ipArray[$index];
        $decryptedSelectedIP = Crypt::decryptString($selectedIp);
        $this->info("You selected IP: $decryptedSelectedIP");

        // Fetch additional info from the database
        $ipDetails = VisitorModel::where('visitor_ip_address', $selectedIp)->first();

        if (!$ipDetails) {
            $this->warn('No additional data found for this IP in the database.');
        } else {
            $this->info('Database record for this IP:');
            $this->line(json_encode($ipDetails->toArray(), JSON_PRETTY_PRINT));
        }

        // Fetch geolocation data
        $this->info('Fetching geolocation data...');
        $response = Http::get("http://ip-api.com/json/{$decryptedSelectedIP}");

        if ($response->failed()) {
            $this->error('Failed to fetch geolocation data.');
            return;
        }

        $geoData = $response->json();

        if ($geoData['status'] === 'fail') {
            $this->error("Geolocation lookup failed: " . $geoData['message']);
            return;
        }

        $this->info('Geolocation Data:');
        $this->table(
            ['Field', 'Value'],
            collect($geoData)->map(fn($value, $key) => [$key, $value])->toArray()
        );

    }
}