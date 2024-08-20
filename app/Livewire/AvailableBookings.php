<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TripsModel;

class AvailableBookings extends Component
{

  

    public function render()
    {
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice')->where('tripAvailability', 'available')->orderBy('created_at', 'desc')->get();

        return view('livewire.available-bookings', ['trips'=>$trips]);
    }
}
