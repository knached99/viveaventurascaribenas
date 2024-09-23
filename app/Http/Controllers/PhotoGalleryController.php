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


        try{
        $photos = PhotoGalleryModel::with(['trip'])->select('photoID', 'tripID', 'photoLabel', 'photoDescription', 'tripPhotos', 'created_at')->get();
        return view('admin/photo-gallery', compact('photos'));
        }

        catch(QueryException $e){
             \Log::error('Uncaught PHP Exception occurred on line: '.__LINE__. ' in method: '.__FUNCTION__. ' in class: '.__CLASS__. ' error: '.$e->getMessage());
             return redirect()->route('admin.photo-gallery')->with(['error'=>'Unable to retrieve photos from gallery']);
            // return redirect()->back()->with(['photoGalleryError'=>'Unable to retrieve photos, something went wrong']);
        }

        // try{
        //     $photos = PhotoGalleryModel::with(['trip'])->select('photoID', 'tripID', 'photoLabel', 'photoDescription', 'tripPhotos')->get();   
        //     $trips = TripsModel::select('tripID', 'tripLocation')->get();
        //     return view('admin/photo-gallery', compact('photos', 'trips'));
        // }

        // catch(\Exception $e){
        //     \Log::error('Uncaught PHP Exception occurred on line: '.__LINE__. ' in method: '.__FUNCTION__. ' in class: '.__CLASS__. ' error: '.$e->getMessage());
        //    // return view('admin/photo-gallery');
        //     return redirect()->back()->with(['photoGalleryError'=>'Unable to retrieve photos, something went wrong']);
        // }

    }
}
