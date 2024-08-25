<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\TripsModel;

class Testimonials extends Model
{
    use HasFactory;

    protected $table = 'testimonials';
    protected $primaryKey = 'testimonialID';
    public $incrementing = false; 
    protected $keyType = 'string'; 

    protected $fillable = [
        'testimonialID',
        'name',
        'email',
        'tripID', 
        'trip_date',
        'trip_rating',
        'testimonial',
        'consent',
        'testimonial_approval_status',
    ];

    protected $casts = [
        'testimonialID' => 'string',
        'tripID' => 'string', 
    ];


    public function trip()
    {
        return $this->belongsTo(TripsModel::class, 'tripID', 'tripID');
    }
    
}
