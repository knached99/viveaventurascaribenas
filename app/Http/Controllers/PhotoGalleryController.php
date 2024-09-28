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


        $photos = PhotoGalleryModel::with(['trip'])->select('photoID', 'tripID', 'photos', 'photoLabel', 'photoDescription', 'created_at', 'updated_at')->get()->toArray();        
        return view('admin/photo-gallery', compact('photos'));

    }

    public function deletePhotosFromGallery($photoID)
    {
        try {
            $photos = PhotoGalleryModel::where('photoID', $photoID)->pluck('photos')->first();
    
            if ($photos) {
                Storage::delete(json_decode($photos, true));  // Deletes all photos in one operation 
            }
    
            PhotoGalleryModel::destroy($photoID);
    
            return redirect()->back()->with('delete_success', 'Photos deleted from the gallery successfully!');
        } catch (\Exception $e) {
            \Log::error("Exception in " . __CLASS__ . " at " . __METHOD__ . " on line " . __LINE__ . ": " . $e->getMessage());
    
            return redirect()->back()->with('delete_error', 'Unable to delete photos from the gallery, something went wrong!');
        }
    }
    
  
}
