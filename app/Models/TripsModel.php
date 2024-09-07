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
        'stripe_product_id',
        'tripLocation',
        'tripPhoto',
        'tripDescription',
        'tripActivities',
        'tripLandscape',
        'tripAvailability',
        'tripStartDate',
        'tripEndDate',
        'tripPrice',
        'testimonial_id' // Ensure this field is fillable
    ];

    protected $casts = [
        'tripID' => 'string',
        //'tripPhoto'=>'array',
    ];

    public function testimonial()
    {
        return $this->belongsTo(Testimonials::class, 'testimonial_id', 'testimonialID');
    }
}
