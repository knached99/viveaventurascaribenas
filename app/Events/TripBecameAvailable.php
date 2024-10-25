<?php

namespace App\Events;

use App\Models\TripsModel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripBecameAvailable
{
    use Dispatchable, SerializesModels;

    public $trip;

    public function __construct(TripsModel $trip)
    {
        $this->trip = $trip;
  
    }
}
