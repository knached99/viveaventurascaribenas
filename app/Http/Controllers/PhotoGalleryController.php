<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhotoGalleryModel;
use App\Models\TripsModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
class PhotoGalleryController extends Controller
{
    
    public function retrivePhotoGallery(){


        $photos = PhotoGalleryModel::select('photoID', 'tripID', 'photos', 'photoLabel', 'photoDescription', 'created_at', 'updated_at')->get();        
        // $photos = PhotoGalleryModel::with(['trip'])->select('photoID', 'tripID', 'photoLabel', 'photoDescription', 'photos', 'created_at')->get();
        \Log::info('Photos in gallery: '.json_encode($photos));
        return view('admin/photo-gallery', compact('photos'));
        


   

    }
}
