<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TripsModel;
use App\Models\BookingModel;

class PhotoGalleryModel extends Model
{
    use HasFactory;

    protected $table = 'photo_gallery';

    protected $primaryKey = 'photoID';

    protected $fillable = [
        'photoID',
        'tripID',
        'photoLabel',
        'photoDescription',
        'photos',
    ];

    protected $casts = [
        'photoID'=>'string',
        'tripID'=>'string',
    ];

    public function trip(){
        return $this->belongsTo(TripsModel::class, 'tripID', 'tripID');
    }


}
