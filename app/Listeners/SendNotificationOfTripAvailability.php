<?php

namespace App\Listeners;

use App\Events\TripAvailabilityUpdated;
use App\Notifications\TripAvailabilityNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationOfTripAvailability
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TripAvailabilityUpdated $event): void
    {
        $user = $event->reservation->user;

        $user->notify(new TripAvailabilityNotification($event->reservation, $event->isAvailable));
    }
}
