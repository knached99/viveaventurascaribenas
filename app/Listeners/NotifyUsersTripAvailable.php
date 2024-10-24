<?php
namespace App\Listeners;

use App\Events\TripBecameAvailable;
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
            Notification::route('mail', $reservation->email)->notify(new TripAvailableNotification($event->trip));
        }
    }
}
