<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VisitorModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class InspectVisitorIP extends Command {

    protected $signature = "inspect:ip";
    protected $description = "Inspect visitor IPs and retrieve geolocation data";

 
    public function handle(){

        $output = new ConsoleOutput();
        
        $choice = $this->choice('Select an option from the menu: ', [
            '1' => 'Inspect a single IP',
            '2' => 'Inspect and plot multiple IPs on a geographical map',
        ], 0);

        return $choice === '1' ? $this->inspectSingleIP() : $this->inspectAndPlotMultipleIPs();
    }


    protected function inspectSingleIP(){

        $output = new ConsoleOutput();
        $spinner = new ProgressIndicator($output);
        $spinner->start("Fetching Ip addresses from the database...");
        sleep(1);

        $ipAddresses = VisitorModel::pluck('visitor_ip_address')->unique()->values();

        if($ipAddresses->isEmpty()){
            $this->error("No Ip addresses found");
            return;
        }

        $spinner->finish("Ip addresses retrieved!");

        $choice = $this->choice("How many Ip addresses would you like to inspect? ", ['10', '50', '100', 'All']);

        $limitedIps = match($choice){
            '10' => $ipAddresses->take(10),
            '50' => $ipAddresses->take(50),
            '100' => $ipAddresses(10),
            default => $ipAddresses,
        };

        $ipArray = $limitedIps->values()->toArray();

        foreach($ipArray as $index => $ip){
            $this->line("[$index] $ip");
        }

        $index = (int) $this->ask("Select an IP by index");

        if(!isset($ipArray[$index])){
            $this->error("Invalid choice selected");
            return;
        }

        $selectedIP = $ipArray[$index];
        $decryptedSelectedIP = Crypt::decryptString($selectedIP);
        $this->info("You selected IP: $decryptedSelectedIP");
        
        $ipDetails = VisitorModel::where('visitor_ip_address', $selectedIP)->first();

        if(!$ipDetails){
            $this->warn("No additional data found for this IP in the database");
        }

        else{

            $this->info("Database record for selected IP: ");
            $this->table(['Field', 'Value'], collect($ipDetails)->map(fn($v, $k) => [$k, $v])->toArray());
        }

        $this->info("Fetching geolocation data...");
        $response = Http::get("ttp://ip-api.com/json/{$decryptedSelectedIP}");

        if($response->failed() || $response->json()['status'] === 'fail'){
            $this->error('Failed to fetch geolocation data');
            return;
        }

        $geoData = $response->json();
        $this->info("Geolocation Data:");
        $this->table(['Field', 'Value'], collect($geoData)->map(fn($v, $k) => [$k, $v])->toArray());

    }



    protected function inspectAndPlotMultipleIPs(){

        $output = new ConsoleOutput();
        $spinner = new ProgressIndicator($output);
        $spinner->start('Fetching IP addresses from the database...');
        sleep(1);

        $encryptedIPs = VisitorModel::pluck('visitor_ip_address')->unique()->values();

        if($encryptedIPs->isEmpty()){
            $this->error("No IP addresses found");
            return;
        }

        $spinner->finish("IP addresses retrieved!");

        $choice = $this->choice('How many IPs would you like to inspect?', ['10', '50', '100', 'All'], 0);
        $limit = match ($choice) {
            '10' => 10,
            '50' => 50,
            '100' => 100,
            default => $encryptedIps->count(),
        };


        $limitedIPs = $encryptedIPs->take($limit)->values()->toArray();
        $decryptedIPs = [];

        foreach($limitedIPs as $encryptedIP){

            try {
                $decryptedIPs[] = Crypt::decryptString($encryptedIP);
            }

            catch(\Exception $e){
                continue;
            }
        }

        $geoPoints = [];
        $chunks = array_chunk($decryptedIps, 10); // processing in batches of 10 to avoid rate limiting 

        foreach($chunks as $chunk){

            foreach($chunk as $ip){

                $response = Http::get('http://ip-api.com/json/{$ip}');

                if($response->successful() && $response->json('status') === 'success'){

                    $geoPoints[] = [
                        'ip'=>$ip,
                        'lat' => $response->json('lat'),
                        'lon' => $response->json('lon'),
                        'country' => $response->json('country')
                    ];
                }
                usleep(300000); // sleeping for 0.3s for the IP
            }
        }

        if(empty($geoPoints)){

            $this->error("No valid geolocation data found");
            return; 
        }

        $markers = [];

        foreach($geoPoints as $point){

            $label = urlencode($point['ip']);
            $markers[] = "color:red|label:•|{$point['lat']},{$point['lon']}";
        }
        

        $mapURL = "https://quickchart.io/map?markers=".implode('&markers=', $markers);
        $this->info("Map URL with IP pins: \n$mapURL");
    }
}