<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TripsModel;
use Laravel\Scout\Searchable;

class BookingModel extends Model
{
    use HasFactory, Searchable;

    protected $table = 'bookings';
    
    protected $primaryKey = 'bookingID';

    protected $fillable = [
        'bookingID',
        'tripID',
        // 'square_product_id',
        // 'square_payment_id',
        // 'square_catalog_object_id',
        'name',
        'email',
        'phone_number',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'zip_code',
        'preferred_start_date',
        'preferred_end_date',
        'amount_captured'
    ];

    protected $casts = [
        'bookingID'=>'string',
        // 'stripe_checkout_id'=>'string',
        // 'stripe_product_id'=>'string'
    ];


    public function trip()
    {
        return $this->belongsTo(TripsModel::class, 'tripID', 'tripID');
    }
    
      /**
     * Get the data that should be indexed for search.
     *
     * @return array
     */

     public function toSearchableArray(){

        return [
            'bookingID' => $this->bookingID,
            'tripID' => $this->tripID,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'preferred_start_date' => $this->preferred_start_date,
            'preferred_end_date' => $this->preferred_end_date,
            'amount_captured' => $this->amount_captured,
        ];
     }

}
