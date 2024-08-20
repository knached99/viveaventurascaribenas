<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripsModel extends Model
{
    use HasFactory;


    protected $primaryKey = 'tripID';

    protected $table = 'trips';

    protected $fillable = [
        'tripID',
        'tripLocation',
        'tripPhoto',
        'tripDescription',
        'tripLandscape',
        'tripAvailability',
        'tripStartDate',
        'tripEndDate',
        'tripPrice'
    ];

    protected $casts = [
        'tripID'=>'string'
    ];


}
