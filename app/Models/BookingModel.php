<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TripsModel;

class BookingModel extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    
    protected $primaryKey = 'bookingID';

    protected $fillable = [
        'bookingID',
        'tripID',
        'stripe_checkout_id',
        'stripe_product_id',
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
        'stripe_checkout_id'=>'string',
        'stripe_product_id'=>'string'
    ];


    public function trip()
    {
        return $this->belongsTo(TripsModel::class, 'tripID', 'tripID');
    }
    

}
