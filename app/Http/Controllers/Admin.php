<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TripsModel;
use App\Models\BookingModel;
use App\Models\Testimonials;
use App\Models\Reservations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Square\SquareClient;
use Square\Models\CatalogQueryResponse;
use Square\Models\SearchPaymentsResponse;
use Carbon\Carbon;
use App\Jobs\ProcessStripeCharges;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Analytics;

use Square\SquareClientBuilder;
use Square\Environment;
use Square\Models\Money;
use Square\Models\CatalogItemVariation;
use Square\Models\CatalogObject;
use Square\Models\CatalogItem;
use Square\Models\UpsertCatalogObjectRequest;
use Square\Authentication\BearerAuthCredentialsBuilder;
use Square\Exceptions\ApiException;


class Admin extends Controller
{

    public $client;
    public $catalog;
    public $accessToken;

    public function __construct()
    {
        $this->accessToken = getenv('SQUARE_ACCESS_TOKEN');

        $this->client = SquareClientBuilder::init()
            -> bearerAuthCredentials(
                BearerAuthCredentialsBuilder::init($this->accessToken)
            )
            ->environment(Environment::SANDBOX)
            ->build();
    }

    
    public function dashboardPage(){
        
        $visitors = Analytics::quickAnalytics();

        // caching data to avoid repeated API calls 
        $cacheKeyProducts = 'square_products';
        $cacheKeyPayments = 'square_payments';
        $popularTrips = [];
        
        $squareProducts = Cache::remember($cacheKeyProducts, 3600, function() {

            $catalogApi = $this->client->getCatalogApi();
            $response = $catalogApi->listCatalog(null, 'ITEM');
            return $response->isSuccess() ? $response->getResult()->getObjects() : [];

        });

            // Safely handle when Square API doesn't return any payments
        $payments = Cache::remember($cacheKeyPayments, 3600, function () {
            $paymentsApi = $this->client->getPaymentsApi();
            $response = $paymentsApi->listPayments();
            return $response->isSuccess() ? $response->getResult()->getPayments() : [];
        });

        // Filtering transactions to exclude refunds, ensuring $payments is not null and is an array
        $transactions = [];
        if (is_array($payments)) {
            $transactions = array_filter($payments, function ($payment) {
                return $payment->getRefunds() === null && $payment->getAmountMoney()->getAmount() > 0;
            });
        }

        $transactionsPerDay = [];
        foreach ($transactions as $payment) {
            $date = Carbon::parse($payment->getCreatedAt())->format('Y-m-d');
            $transactionsPerDay[$date] = ($transactionsPerDay[$date] ?? 0) + $payment->getAmountMoney()->getAmount() / 100;
        }

        ksort($transactionsPerDay);
        $transactionData = array_map(fn($date, $amount) => ['date' => $date, 'amount' => $amount], array_keys($transactionsPerDay), $transactionsPerDay);


        /* 
        array_reduce()
        Iteratively reduces the array to a single value
        using a callback function
        */

        $grossProfit = array_reduce($transactions, fn($carry, $payment) => $carry + $payment->getAmountMoney()->getAmount() / 100, 0);
      
        $totalNetCosts = TripsModel::where('active', true)->pluck('tripCosts')->map(fn($cost) => json_decode($cost, true))->sum(fn($costs) => collect($costs)->sum('amount'));

        $netProfit = $grossProfit - $totalNetCosts;
        
        $bookings = BookingModel::with('trip')->select('bookingID', 'tripID', 'created_at')->get();

        $reservations = Reservations::with('trip')->select('reservationID', 'tripID', 'name', 'email', 'phone_number', 'address_line_1', 'address_line_2', 'city', 'state', 'zip_code')->get();

        // optimizing square api calls 
        
        $allCatalogObjectIds = $bookings->pluck('bookingID')->merge($reservations->pluck('reservationID'))->unique()->values()->toArray();
        $productMap = collect($squareProducts)->mapWithKeys(fn($product) => [$product->getId() => $product->getItemData()->getName()]);
        
        // most popular booking 
        $mostPopularBookings = BookingModel::select('bookingID', DB::raw('COUNT(*) as booking_count'))
        ->groupBy('bookingID')->having('booking_count', '>', 2)->orderByDesc('booking_count')->first();

         $mostPopularReservations = Reservations::select('reservationID', DB::raw('COUNT(*) as reservation_count'))
        ->groupBy('reservationID')->having('reservation_count', '>', 2)->orderByDesc('reservation_count')->first();
    
        $mostPopularTripName = $mostPopularTripPhoto = null;

        if($mostPopularBookings){

            $trip = TripsModel::where('tripID', $mostPopularBookings->tripID)->first();

            if($trip){
                $mostPopularTripName = $productMap[$mostPopularBookings->square_catalog_object_id] ?? 'Unknown';
                $mostPopularTripPhoto = json_decode($trip->tripPhoto, true)[0] ?? null;
                $popularTrips[] = ['id' => $trip->tripID, 'name' => $mostPopularTripName, 'count' => $mostPopularBookings->booking_count, 'image' => $mostPopularTripPhoto];
            }
        }

        // most popular reserved trip 

        $mostPopularReservedTripName = '';
        $mostReservedTrips = [];

        if($mostPopularReservations){

            $reservedTrip = TripsModel::where('square_catalog_object_id', $mostPopularReservations->square_catalog_object_id)->first();

            if($reservedTrip){

                $mostPopularReservedTripName = $reservedTrip->tripLocation;
                $mostPopularReservedTripPhoto = json_decode($reservedTrip->tripPhoto, true)[0] ?? null;
                $reservations = Reservations::where('tripID', $reservedTrip->tripID)->get();
                $reservationCount = $reservations->count();
                $totalDays = $reservations->sum(fn($r) => Carbon::parse($r->preferred_end_date)->diffInDays(Carbon::parse($r->preferred_start_date)));
                $avgTripDays = $reservationCount > 0 ? round($totalDays / $reservationCount) : 0;
                $mostReservedTrips[] = ['id' => $reservedTrip->tripID, 'name' => $mostPopularReservedTripName, 'count' => $reservationCount, 'average_trip_days' => $avgTripDays, 'image' => $mostPopularReservedTripPhoto];

            }
        }

        return view('admin.dashboard', compact(
            'visitors', 'squareProducts', 'totalNetCosts', 'grossProfit', 'netProfit', 'transactionsPerDay', 'transactionData',
            'popularTrips', 'mostReservedTrips', 'bookings', 'reservations'
        ));

    }


    public function profilePage(){
        $objectIDs = [];
        $response = $this->client->getCatalogApi()->listCatalog();
     
            $result = $response->getResult();
            // dd($result);
            $resultArray = json_decode(json_encode($result), true);
            foreach ($resultArray['objects'] as $object) {
                $objectIDs[] = $object['id'];
            }

           $body = new \Square\Models\BatchDeleteCatalogObjectsRequest();
           $body->setObjectIds($objectIDs);
           $response = $this->client->getCatalogApi()->batchDeleteCatalogObjects($body);
            
        if($response){
            dd($response->getResult());
        }

        // else{
        //     $errors = $response->getErrors();
        //     dd($errors);
        // }
        return view('admin/profile');
    }

    public function allTripsPage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'active', 'tripPrice', 'created_at', 'updated_at')->get();
         return view('admin/all-trips', compact('trips'));
    }

    public function testimonialsPage()
        {
            $testimonials = Testimonials::with('trip')
                ->select('testimonialID', 'name', 'email', 'tripID', 'trip_date', 'trip_rating', 'testimonial_approval_status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin/testimonials', compact('testimonials'));
        }

    public function testimonialPage($testimonialID){
        $testimonial = Testimonials::with('trip')->where('testimonialID', $testimonialID)->firstOrFail();
        return view('admin/testimonial', ['testimonialID'=>$testimonialID, 'testimonial'=>$testimonial]);
    }   

    public function approveTestimonial($testimonialID){
        try{

        $testimonial = Testimonials::findOrFail($testimonialID);
        $testimonial->testimonial_approval_status = 'Approved';
        $testimonial->save();
        return redirect()->back()->with('testominal_approve', 'This testimonial is approved and is now visible on the homepage!');
        }

        catch(ModelNotFoundException $e){
            \Log::error('Unable to approve testimonial. Function: '.__FUNCTION__.' on line: '.__LINE__.' '.$e->getMessage());
            return redirect()->back()->with('testominal_approve_error', 'Something went wrong approving this testimonial. If this happens again, please contact the developer');

        }
        catch(\Exception $e){
            return redirect()->back()->with('testominal_approve_error', 'Something went wrong approving this testimonial. If this happens again, please contact the developer');
            \Log::error('Unable to approve testimonial. Function: '.__FUNCTION__.' on line: '.__LINE__.' '.$e->getMessage());

        }
    }

    public function declineTestimonial($testimonialID){
        try{
            $testimonial = Testimonials::findOrFail($testimonialID);
            $testimonial->testimonial_approval_status = 'Declined';
            $testimonial->save();
            return redirect()->back()->with('testimonial_declined', 'This testimonial is declined and will not be visible on the homepage. You may feel free to delete it.');
        }

        catch(ModelNotFoundException $e){
            \Log::error('Testimonial Not Found. Function: '.__FUNCTION__.' on line: '.__LINE__.' '.$e->getMessage());
            return redirect()->back()->with('testimonial_decline_error', 'Something went wrong declining this testimonial. If this happens again, please contact the developer');

        }
        catch(\Exception $e){
            return redirect()->back()->with('testimonial_decline_error', 'Something went wrong declining this testimonial. If this happens again, please contact the developer');
            \Log::error('Unable to decline testimonial. Function: '.__FUNCTION__.' on line: '.__LINE__.' '.$e->getMessage());

        }

    }

    public function deleteTestimonial($testimonialID){
       try{

        Testimonials::destroy($testimonialID);
        return redirect()->route('admin.testimonials')->with('testimonial_delete_success', 'Testimony deleted successfully!');

       }

       catch(ModelNotFoundException $e){
        \Log::error('Testimonial Not Found. Function: '.__FUNCTION__.' on line: '.__LINE__.' '.$e->getMessage());
        return redirect()->route('admin.testimonials')->With('testimonial_delete_error', 'Unable to delete testimony. Something went wrong and if this error persists, please contact the developer');
       }

       catch(\Exception $e){
        \Log::error('Testimonial deletion error. Function: '.__FUNCTION__.' on line: '.__LINE__.' '.$e->getMessage());
        return redirect()->route('admin.testimonials')->With('testimonial_delete_error', 'Unable to delete testimony. Something went wrong and if this error persists, please contact the developer');
       }
    }
  
    // Optimized Algorithm

    public function getTripDetails($tripID)
{
    $cacheKey = "trip_details_{$tripID}";
    $cachedData = Cache::get($cacheKey);


    if ($cachedData) {
        return view('admin.trip', $cachedData);
    }

    try {
        // Browser does not send #trip_ to the server so we will
        // need to decode and encode it to be sent properly 
        
        $trip = TripsModel::where('tripID', $tripID)->firstOrFail();
        
        // Retrieve the most popular reserved trip containing a count of 2 or more reservations 
        $mostPopularReservations = Reservations::select('reservations.tripID', DB::raw('COUNT(*) as reservation_count'))
        ->join('trips', 'reservations.tripID', '=', 'trips.tripID')
        ->groupBy('reservations.tripID', 'trips.tripID', 'trips.tripPhoto')
        ->having('reservation_count', '>', 2)
        ->orderByDesc('reservation_count')
        ->first();
        
        
        $mostPopularReservedTripName = '';
        $mostReservedTrips = [];
        
    
        $mostPopularTripName = $mostPopularTripPhoto = null;

        // if ($mostPopularBookings) {
        //     $product = $this->stripe->products->retrieve($mostPopularBookings->stripe_product_id);
        //     $trip = TripsModel::where('stripe_product_id', $mostPopularBookings->stripe_product_id)->first();
    
        //     if ($trip) {
        //         $mostPopularTripName = $product->name;
        //         $mostPopularTripPhoto = json_decode($trip->tripPhoto, true)[0] ?? null;
    
        //         $popularTrips[] = [
        //             'id' => $trip->tripID,
        //             'name' => $mostPopularTripName,
        //             'count' => $mostPopularBookings->booking_count,
        //             'image' => $mostPopularTripPhoto,
        //         ];
        //     }
        // }

        
        // Calculate data for most reseserved trip 

        if ($mostPopularReservations) {
            $reservedTrip = Tripsmodel::where('tripID', $mostPopularReservations->tripID)->first();
          
            if ($reservedTrip && $reservedTrip->tripID == $tripID) {
                // Extract the trip photo
                $tripPhotos = json_decode($reservedTrip->tripPhoto, true);
                $mostPopularReservedTripName = $reservedTrip->tripLocation;
                $mostPopularReservedTripPhoto = $tripPhotos[0] ?? null;  // Get the first photo or null if none exists
        
                // Extracting all reservations for this trip
                $reservations = Reservations::where('tripID', $reservedTrip->tripID)->get();
        
                // Initialize variables to store total start and end dates
                $totalStartDates = 0;
                $totalEndDates = 0;
                $totalDays = 0;
                $reservationCount = $reservations->count();
               
                if($reservationCount > 0){
                    foreach($reservations as $reservation){
                        $startDate = Carbon::parse($reservation->preferred_start_date);
                        $endDate = Carbon::parse($reservation->preferred_end_date);
                        $dateRange = abs($endDate->diffInDays($startDate));
                        
                        // Sum and calculate the date range average 

                        $totalStartDates += $startDate->timestamp;
                        $totalEndDates += $endDate->timestamp;

                        $totalDays += $dateRange; 
                    }

                    $averageStartDate = Carbon::createFromTimestamp($totalStartDates / $reservationCount);
                    $averageEndDate = Carbon::createFromTimestamp($totalEndDates / $reservationCount);

                    $averageDateRange = round($totalDays / $reservationCount);
                }

                else{
                    $averageStartDate = null;
                    $averageEndDate = null;
                    $averageDateRange = 0;
                }

             
            }
        }
        
                 

        $tripCosts = json_decode($trip->tripCosts, true) ?: [];
       
        $totalNetCost = array_reduce($tripCosts, fn($carry, $cost) => $carry + (float) $cost['amount'], 0);


        $grossProfit = BookingModel::where('tripID', $tripID)->sum('amount_captured');
        $netProfit = isset($grossProfit) && isset($totalNetCost) ? $grossProfit - $totalNetCost : 0;
        

        $dataToCache = [
            'tripId' => $tripID,
            'trip' => $trip,
            'totalNetCost' => $totalNetCost,
            'grossProfit' => $grossProfit,
            'netProfit' => $netProfit,
            'averageStartDate' => isset($averageStartDate) ? $averageStartDate->format('m/d/Y') : null,
            'averageEndDate' => isset($averageEndDate) ? $averageEndDate->format('m/d/Y') : null,
            'averageDateRange' => isset($averageDateRange) ? round($averageDateRange, 2) : null,
        ];

        Cache::put($cacheKey, $dataToCache, 60);
        return view('admin.trip', $dataToCache);
    
    }
     catch (ModelNotFoundException $e) {
        \Log::error('Unable to get trip details: ' . $e->getMessage());
        abort(404);
    } catch (Exception $e) {
        \Log::error('Error retrieving Stripe charges: ' . $e->getMessage());
        // $error = 'Something went wrong fetching trip details';
        // return view('admin.trip', $error);
        abort(500);
    }
}


    
public function deleteTrip($tripID) {
    try {
        
        $trip = TripsModel::where('tripID', $tripID)->firstOrFail();
        \Log::info('Logging trip details: ' . json_encode($tripID));

        \Log::info('Deleting trip details for trip ID: ' . $tripID);

        // Delete associated photos
        \Log::info('Checking if photo exists');
        $photos = json_decode($trip->tripPhoto, true);

        if (!empty($photos) && is_array($photos)) {
            foreach ($photos as $photoUrl) {
                $filePath = parse_url($photoUrl, PHP_URL_PATH);
                $filePath = ltrim($filePath, '/'); // Ensure relative path

                // Check if the file exists before attempting to delete
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                    \Log::info("Deleted photo: {$filePath}");
                } else {
                    \Log::warning("Photo not found: {$filePath}");
                }
            }
        }

        // Delete trip record from database
        $trip->delete();
        \Log::info("Deleted trip record from database: {$tripID}");

        // Delete from Square as well 
        $squareTrip = $this->client->getCatalogApi()->retrieveCatalogObject($tripID, false);

        if ($squareTrip->isSuccess()) {
            \Log::info("Successfully retrieved Square trip: " . json_encode($squareTrip->getResult()));
        } else {
            \Log::error("Square API error: " . json_encode($squareTrip->getErrors()));
        }

        return response()->json(['success' => true, 'message' => 'Trip deleted successfully']);

    } catch (ModelNotFoundException $e) {
        \Log::error("Trip not found: {$tripID}. Error: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Trip not found'], 404);
    } catch (\Exception $e) {
        \Log::error("Exception deleting trip ID {$tripID}: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'An error occurred while deleting the trip'], 500);
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
