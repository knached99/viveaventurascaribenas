<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Testimonials extends Model
{
    use HasFactory;

    protected $table = 'testimonials';
    protected $primaryKey = 'testimonialID';
    public $incrementing = false; // Disable auto-increment
    protected $keyType = 'string'; // Key type is string

    protected $fillable = [
        'testimonialID',
        'name',
        'email',
        'trip_details',
        'trip_date',
        'trip_rating',
        'testimonial',
        'consent',
        'testimonial_approval_status',
    ];

    protected $casts = [
        'testimonialID' => 'string'
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         if (empty($model->testimonialID)) {
    //             $model->testimonialID = (string) Str::uuid();
    //         }
    //     });
    // }
}
