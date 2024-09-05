<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $primaryKey = 'reservationID';

    protected $fillable = [
        'reservationID',
        'stripe_product_id',
        'name',
        'email',
        'phone_number',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'zip_code'
    ];

    protected $casts = [
        'reservationID'=>'string',
        'stripe_product_id'=>'string',
    ];

    
}
