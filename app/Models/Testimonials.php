<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\TripsModel;
use Laravel\Scout\Searchable;

class Testimonials extends Model
{
    use HasFactory,Searchable;

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

      /**
     * Get the data that should be indexed for search.
     *
     * @return array
     */

     public function toSearchableArray() {

        return [
            'testimonialID' => $this->testimonialID,
            'name' => $this->name,
            'email' => $this->email,
            'tripID' => $this->tripID, 
            'trip_date' => $this->trip_date,
            'trip_rating' => $this->trip_rating,
            'testimonial' => $this->testimonial,
            'consent' => $this->consent,
            'testimonial_approval_status' => $this-> testimonial_approval_status,
        ];
     }
    
}
