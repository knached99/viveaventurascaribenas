<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TripsModel;
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

    $usedStorage = $storageData['usedSpace'] / (1024 * 1024 * 1024); // Used space in GB
    $totalStorage = $storageData['totalSpace'] / (1024 * 1024 * 1024); // Total space in GB
    $remainingStorage = $storageData['freeSpace'] / (1024 * 1024 * 1024); // Remaining space in GB
    
    
    return view('admin.dashboard', compact('usedStorage', 'totalStorage', 'remainingStorage'));
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
            $totalUsedSpace += $this->getDirectorySize($dir);
        }
    
        // Total disk space (in bytes) for the first directory (assuming all directories are on the same disk)
        $totalSpace = disk_total_space(reset($directories));
    
        return [
            'usedSpace' => $totalUsedSpace,
            'totalSpace' => $totalSpace,
            'freeSpace' => $totalSpace - $totalUsedSpace
        ];
    }
    
    



}
