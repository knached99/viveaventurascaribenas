<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TripsModel;
use Laravel\Scout\Searchable;

class Reservations extends Model
{
    use HasFactory,Searchable;

    protected $table = 'reservations';

    protected $primaryKey = 'reservationID';

    protected $fillable = [
        'reservationID',
        // 'square_product_id',
        // 'square_catalog_object_id',
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
        'tripID'=>'string',
    ];


    public function trip(){
        return $this->belongsTo(TripsModel::class, 'tripID', 'tripID');
    }

        /**
     * Get the data that should be indexed for search.
     *
     * @return array
     */

     public function toSearchableArray() {

        return [
            'reservationID' => $this->reservationID,
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
        ];
     }
    
}
