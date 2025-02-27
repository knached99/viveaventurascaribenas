<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class TripsModel extends Model
{
    use HasFactory,Searchable;

    protected $primaryKey = 'tripID';
    protected $table = 'trips';

    protected static function boot(){
    
        parent::boot();

        static::creating(function($trip){
            $trip->slug = Str::slug($trip->tripLocation);
        });
    }

    protected $fillable = [
        // 'tripID',
        'tripID',
        'idempotencyKey',
        'tripLocation',
        'tripPhoto',
        'tripDescription',
        'tripActivities',
        'tripLandscape',
        'tripAvailability',
        'tripStartDate',
        'tripEndDate',
        'tripPrice',
        'tripCosts',
        'num_trips',
        'active',
        'slug',
        'testimonial_id' // Ensure this field is fillable
    ];

    protected $casts = [
        'tripID' => 'string',
        'idempotencyKey' => 'string',
        'tripPhoto'=>'array',
        'tripLandsacpe'=>'array',
    ];

    public function testimonial()
    {
        return $this->belongsTo(Testimonials::class, 'testimonial_id', 'testimonialID');
    }

       /**
     * Get the data that should be indexed for search.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'tripID' => $this->tripID,
            'tripLocation' => $this->tripLocation,
            'tripDescription' => $this->tripDescription,
            'tripActivities' => $this->tripActivities,
            'tripLandscape' => $this->tripLandscape,
            'tripAvailability' => $this->tripAvailability,
            'tripStartDate' => $this->tripStartDate,
            'tripEndDate' => $this->tripEndDate,
            'tripPrice' => $this->tripPrice,
            'active' => $this->active,
            'slug' => $this->slug,
        ];
    }
}
