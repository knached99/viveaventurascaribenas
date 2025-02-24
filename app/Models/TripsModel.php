<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TripsModel extends Model
{
    use HasFactory;

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
}
