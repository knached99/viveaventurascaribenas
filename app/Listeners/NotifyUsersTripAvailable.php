<?php
namespace App\Listeners;

use App\Events\TripBecameAvailable;
use Symfony\Component\Process\Process;
use App\Notifications\TripAvailableNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Models\Reservations;

class NotifyUsersTripAvailable implements ShouldQueue
{
    public function handle(TripBecameAvailable $event)
    {
        \Log::info('TripBecameAvailable event handled for trip ID: ' . $event->trip->tripID);
    
        // Fetch reservations associated with this trip
        $reservations = Reservations::where('tripID', $event->trip->tripID)->get();
        
       // Notify all users who made reservations for this trip
        foreach ($reservations as $reservation) {
            \Log::info('Notification being sent to: ' . $reservation->email);
            
            Notification::route('mail', $reservation->email)
                ->notify(new TripAvailableNotification($event->trip, $reservation->reservationID, $reservation->customerName));
        }

        // Trigger the laravel queue worker manually 

        $phpBinary = '/usr/bin/php8.3-cli';

        $artisanPath = base_path('artisan');

        // using process instead of shell_exec() as it is safer 

        $process = new Process([$phpBinary, $artisanPath, 'queue:work', '--once']);
        $process->start();
    }
}
