<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TripsModel;

class AvailableBookings extends Component
{

  

    public function render()
    {
        $trips = TripsModel::all();

        return view('livewire.available-bookings', ['trips'=>$trips]);
    }
}
