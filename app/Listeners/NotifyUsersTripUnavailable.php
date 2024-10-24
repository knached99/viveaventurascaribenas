<?php 
namespace App\Listeners;

use App\Events\TripBecameUnavailable;
use App\Notifications\TripUnavailableNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Reservations;

class NotifyUsersTripUnavailable implements ShouldQueue
{
    public function handle(TripBecameUnavailable $event)
    {
        // Fetch reservations for the trip
        $reservations = Reservations::where('tripID', $event->trip->tripID)->get();
        
        foreach ($reservations as $reservation) {
            // Send notification to the email address stored in the reservation
            Notification::route('mail', $reservation->email)->notify(new TripUnavailableNotification($event->trip));
            
            // Delete the reservation
            $reservation->delete();
        }
    }
}
