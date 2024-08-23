<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TripsModel;
use App\Models\Testimonials;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException; 

class Admin extends Controller
{
    public function dashboardPage()
    {
        $directories = [
            storage_path('app/public/booking_photos'),
            // Any other directories go here
        ];
    
        // Calculate storage usage
        $storageData = $this->calculateStorageUsage($directories);

        // Format storage values
        $usedStorage = $this->formatSize($storageData['usedSpace']);        // Formatted used space
        $totalStorage = $this->formatSize($storageData['totalSpace']);      // Formatted total space
        $remainingStorage = $this->formatSize($storageData['freeSpace']);   // Formatted remaining space
        
        return view('admin.dashboard', compact('storageData','usedStorage', 'totalStorage', 'remainingStorage'));
    }
    

    public function profilePage(){
        return view('admin/profile');
    }


    public function tripsPage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice')->get()->take(5);
        return view('admin/trips', compact('trips'));
    }

    public function allTripsPage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'tripPrice')->get();
        
        return view('admin/all-trips', compact('trips'));
    }

    public function testimonialsPage(){
        $testimonials = Testimonials::select('testimonialID', 'name', 'email', 'trip_details', 'trip_date', 'trip_rating', 'testimonial_approval_status', 'created_at')->orderBy('created_at', 'desc')->get();
        return view('admin/testimonials', compact('testimonials'));
    }

    public function testimonialPage($testimonialID){
        $testimonial = Testimonials::where('testimonialID', $testimonialID)->firstOrFail();
        return view('admin/testimonial', ['testimonialID'=>$testimonialID, 'testimonial'=>$testimonial]);
    }

    public function approveTestimonial($testimonialID){
        try{

        $testimonial = Testimonials::findOrFail($testimonialID);
        $testimonial->testimonial_approval_status = 'Approved';
        $testimonial->save();
        return redirect()->back()->with('testominal_approve', 'This testimonial is approved and is now visible on the homepage!');
        }
        catch(\Exception $e){
            return redirect()->back()->with('testominal_approve_error', 'Something went wrong approving this testimonial. If this happens again, please contact the developer');
            \Log::error('Unable to approve testimonial. Function: '.__FUNCTION__.' on line: '.__LINE__.' '.$e->getMessage());

        }
    }

    public function getTripDetails($tripID){
        
        try{

        $trip = TripsModel::where('tripID', $tripID)->firstOrFail();
        
        return view('admin/trip', ['tripId'=>$tripID, 'trip'=>$trip]);

        }
        
        catch(ModelNotFoundException $e){
            \Log::error('Unable to get trip details for method: '.__FUNCTION__.' on line: '.__LINE__.' '.$e->getMessage());
            abort(404);
        }

    }

    public function deleteTrip($tripID){
        try {
            $trip = TripsModel::findOrFail($tripID);
    
            \Log::info('Logging trip details: '. json_encode($trip));
    
            \Log::info('Deleting trip details for trip ID: '.$tripID);
    
            \Log::info('Checking if photo exists');
    
            // The correct file path relative to the 'public' disk
            $filePath = $trip->tripPhoto;
    
            // Check if the file exists
            if (Storage::disk('public')->exists($filePath)) {
                \Log::info('File exists: ' . $filePath);
    
                // Delete the file
                Storage::disk('public')->delete($filePath);
                \Log::info('File deleted: ' . $filePath);
            } else {
                \Log::info('File does not exist: ' . $filePath);
            }
    
            // Delete the trip record
            $trip->delete();
            \Log::info('Deleted Trip.');
    
            \Log::info('Verifying deletion');
    
            if (!$trip->exists) {
                \Log::info('Verified, trip is deleted');
            }
    
            return redirect()->back()->with('trip_deleted', 'That trip was successfully deleted');
        } catch (ModelNotFoundException $e) {
            \Log::error('ModelNotFoundException in class: ' . __CLASS__ . ' on line: ' . __LINE__ . ' Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Exception in class: ' . __CLASS__ . ' on line: ' . __LINE__ . ' Error: ' . $e->getMessage());
        }
    }

    private function formatSize($bytes)
{
    if ($bytes >= 1073741824) {
        $size = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $size = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $size = number_format($bytes / 1024, 2) . ' KB';
    } else {
        $size = $bytes . ' bytes';
    }

    return $size;
}



    private function getDirectorySize($directory) {
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }
    
    
    private function getFileCount($directory) {
        $count = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)) as $file) {
            $count++;
        }
        return $count;
    }


    private function calculateStorageUsage(array $directories)
    {
        $totalUsedSpace = 0;
    
        foreach ($directories as $dir) {
            $dirSize = $this->getDirectorySize($dir);
            $totalUsedSpace += $dirSize;
        }
    
        $directory = reset($directories);
        $totalSpace = disk_total_space($directory);
        $freeSpace = disk_free_space($directory);
    
        $usedSpace = $totalSpace - $freeSpace;

        return [
            'usedSpace' => $usedSpace,
            'totalSpace' => $totalSpace,
            'freeSpace' => $freeSpace
        ];
    }
    
    
    



}
