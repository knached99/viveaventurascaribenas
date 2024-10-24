<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TripsModel;

class Reservations extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $primaryKey = 'reservationID';

    protected $fillable = [
        'reservationID',
        'stripe_product_id',
        'tripID',
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
    ];

    protected $casts = [
        'reservationID'=>'string',
        'stripe_product_id'=>'string',
    ];


    public function trip(){
        return $this->belongsTo(TripsModel::class, 'tripID', 'tripID');
    }
    
}
