<?php

namespace App\Events;

use App\Models\Reservations;
use App\Models\TripsModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripAvailabilityUpdated implements ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

     public Reservations $reservation;
     public string $isAvailable; 

    public function __construct(Reservations $reservation, string $isAvailable){
        $this->reservation = $reservation;
        $this->isAvailable = $isAvailable;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->reservation->email),
        ];
        
    }

    public function broadcastWith(): array {

        return [
            'reservationID' => $this->reservation->reservationID,
            'tripID'=>$this->returnTripID($this->reservation->stripe_product_id),
            'isAvailable'=>$this->isAvailable,
        ];
    }


    private function returnTripID($stripe_product_id){

        try{
        
            $tripID = TripsModel::where('stripe_product_id', $stripe_product_id)->first();
        
            return $tripID;
        
        }

        catch(\Exception $e){
            \Log::error('Unable to retrieve tripID due to error: '.$e->getMessage());
        }
    }
}
