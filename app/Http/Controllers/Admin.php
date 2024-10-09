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
use Stripe\Stripe;
use Stripe\Product;
use Stripe\StripeClient;
use Carbon\Carbon;
use App\Jobs\ProcessStripeCharges;
use Illuminate\Support\Facades\Cache;

class Admin extends Controller
{

    public function __construct(){
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
         $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    

    }
    
    public function dashboardPage()
    {
        // Cache Stripe products and charges
        $cacheKeyProducts = 'stripe_products';
        $cacheKeyCharges = 'stripe_charges';
        $popularTrips = [];
        $stripeProducts = Cache::remember($cacheKeyProducts, 3600, function () {
            return $this->stripe->products->all(['limit' => 100]); 
        });
    
        $charges = Cache::remember($cacheKeyCharges, 3600, function () {
            return $this->stripe->charges->search([
                'query' => "status:'succeeded'",
            ]);
        });
    
        // We need to get all transactions that weren't refunded
        $transactions = array_filter($charges->data, function ($charge) {
            return !$charge->refunded && isset($charge->amount_captured) && $charge->amount_captured > 0;
        });
    
        $transactionsPerDay = [];
        foreach ($transactions as $charge) {
            $date = Carbon::parse($charge->created)->format('Y-m-d');
            $transactionsPerDay[$date] = ($transactionsPerDay[$date] ?? 0) + (float) $charge->amount_captured / 100;
        }
        
        // Sort transactions by date in ascending order using built in ksort() method to sort the returned array by key 
        ksort($transactionsPerDay);
        
        // Format transaction data for the current year
        $transactionData = array_map(function ($date, $amount) {
            return [
                'date' => $date,
                'amount' => $amount
            ];
        }, array_keys($transactionsPerDay), $transactionsPerDay);
        
        // Calculate gross profit
        $grossProfit = array_reduce($transactions, function ($carry, $charge) {
            return $carry + (float) $charge->amount_captured / 100; // Convert from cents to dollars
        }, 0);
    
     
        $totalNetCosts = TripsModel::select('tripCosts')->where('active', true)->get()
            ->flatMap(function ($trip) {
                return json_decode($trip->tripCosts, true) ?? [];
            })
            ->sum(function ($cost) {
                return isset($cost['amount']) ? (float)$cost['amount'] : 0;
            });
    
        $netProfit = $grossProfit - $totalNetCosts;
    
        // Fetch bookings and reservations
        $bookings = BookingModel::with('trip')
            ->select('bookingID', 'name', 'stripe_checkout_id', 'stripe_product_id', 'tripID', 'created_at')
            ->get();
    
        $reservations = Reservations::select('reservationID', 'name', 'email', 'phone_number', 'address_line_1', 'address_line_2', 'city', 'state', 'zip_code', 'stripe_product_id')->get();
    
        // Optimizing Stripe API calls here
        $allProductIds = $bookings->pluck('stripe_product_id')
            ->merge($reservations->pluck('stripe_product_id'))
            ->unique()
            ->values()
            ->toArray();
    
        $productMap = collect($stripeProducts->data)
            ->mapWithKeys(function ($product) {
                return [$product->id => $product->name];
            });
    
        // This query retrieves the most popular booking
        $mostPopularBookings = BookingModel::select('bookings.stripe_product_id', DB::raw('COUNT(*) as booking_count'))
            ->join('trips', 'bookings.stripe_product_id', '=', 'trips.stripe_product_id')
            ->groupBy('bookings.stripe_product_id', 'trips.tripID', 'trips.tripPhoto')
            ->having('booking_count', '>', 2)
            ->orderByDesc('booking_count')
            ->first();
    
        $mostPopularTripName = $mostPopularTripPhoto = null;
        if ($mostPopularBookings) {
            $product = $this->stripe->products->retrieve($mostPopularBookings->stripe_product_id);
            $trip = TripsModel::where('stripe_product_id', $mostPopularBookings->stripe_product_id)->first();
    
            if ($trip) {
                $mostPopularTripName = $product->name;
                $mostPopularTripPhoto = json_decode($trip->tripPhoto, true)[0] ?? null;
    
                $popularTrips[] = [
                    'id' => $trip->tripID,
                    'name' => $mostPopularTripName,
                    'count' => $mostPopularBookings->booking_count,
                    'image' => $mostPopularTripPhoto
                ];
            }
        }
    
        return view('admin.dashboard', compact(
            'transactionData',
            'bookings',
            'reservations',
            'mostPopularTripName',
            'mostPopularTripPhoto',
            'productMap',
            'grossProfit',
            'totalNetCosts',
            'netProfit',
            'popularTrips'
        ));
    }
    
    

    
    public function profilePage(){
        return view('admin/profile');
    }

    public function bookingInfo($bookingID)
    {
        try {
            $booking = BookingModel::with('trip')->where('bookingID', $bookingID)->firstOrFail();
            return view('admin.booking', [
                'bookingID' => $bookingID,
                'booking' => $booking
            ]);
    
        } catch (ModelNotFoundException $e) {
            \Log::error('ModelNotFoundException encountered on line ' . __LINE__ . ' in class: ' . __CLASS__ . ' Error Message: ' . $e->getMessage());
            abort(404);
        } catch (\Exception $e) {
            \Log::error('Exception encountered on line ' . __LINE__ . ' in class: ' . __CLASS__ . ' Error Message: ' . $e->getMessage());
            abort(500);
        }
    }

    public function getReservationDetails($reservationID){
        try{
            
            $reservation = Reservations::findOrFail($reservationID);

            return view('admin/reservations/', ['reservationID' => $reservationID, 'reservation'=>$reservation]);
        
    }
    catch(ModelNotFoundException $e){
        \Log::error('ModelNotFoundException encountered on line ' . __LINE__ . ' in class: ' . __CLASS__ . ' Error Message: ' . $e->getMessage());
        abort(404);
    }

}



    public function allTripsPage(){
        $trips = TripsModel::select('tripID', 'tripLocation', 'tripPhoto', 'tripLandscape', 'tripAvailability', 'tripStartDate', 'tripEndDate', 'active', 'tripPrice')->get();
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
        $trip = TripsModel::where('tripID', $tripID)->firstOrFail();

      

        $tripCosts = json_decode($trip->tripCosts, true) ?: [];
       
        $totalNetCost = array_reduce($tripCosts, fn($carry, $cost) => $carry + (float) $cost['amount'], 0);
        // $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        // $stripeProductID = $trip->stripe_product_id;

        $grossProfit = BookingModel::where('tripID', $tripID)->sum('amount_captured');
        $netProfit = $grossProfit - $totalNetCost;
      


        // Query payment intents for successful payments
        // $paymentIntents = $stripe->paymentIntents->search([
        //     'query' => 'status:"succeeded" AND metadata["product_id"]:"'.$stripeProductID.'"', // Filter by product ID in metadata
        //     'limit' => 100,
        // ]);


        // \Log::info(json_encode($paymentIntents));

        // $filteredCharges = [];


        // foreach ($paymentIntents->data as $intent) {
        //     $charges = $stripe->charges->all([
        //         'payment_intent' => $intent->id,
        //         'limit' => 100,
        //     ]);


        //     foreach ($charges->data as $charge) {
        //         if ($charge->amount_refunded == 0 && $charge->amount_captured > 0) {
        //             if ($charge->invoice) {
        //                 \Log::info('Charge Invoice: '.json_encode($charge->invoice));
        //                 $invoice = $stripe->invoices->retrieve($charge->invoice, ['expand' => ['lines']]);
        //                 foreach ($invoice->lines->data as $lineItem) {
        //                     if ($lineItem->price->product === $stripeProductID) {
        //                         $filteredCharges[] = $charge;
        //                         break;
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }


        // Calculate gross profit based on the captured amount
        // $grossProfit = array_reduce($filteredCharges, fn($carry, $charge) => $carry + (float) $charge->amount_captured / 100, 0);


        $dataToCache = [
            'tripId' => $tripID,
            'trip' => $trip,
            'totalNetCost' => $totalNetCost,
            'grossProfit' => $grossProfit,
            'netProfit' => $netProfit
        ];

        Cache::put($cacheKey, $dataToCache, 60);
        return view('admin.trip', $dataToCache);

    } catch (ModelNotFoundException $e) {
        \Log::error('Unable to get trip details: ' . $e->getMessage());
        abort(404);
    } catch (Exception $e) {
        \Log::error('Error retrieving Stripe charges: ' . $e->getMessage());
        $error = 'Something went wrong fetching trip details';
        return view('admin.trip', $error);
    }
}

    
    
    
    public function deleteTrip($tripID) {
        try {
            $trip = TripsModel::findOrFail($tripID);
            \Log::info('Logging trip details: ' . json_encode($trip));
            \Log::info('Stripe Product ID: ' . $trip->stripe_product_id);
    
            \Log::info('Deleting trip details for trip ID: ' . $tripID);
    
            \Log::info('Checking if photo exists');
    
            // Assuming tripPhoto is an array of URLs, convert it back to its original format for deletion
            $photos = json_decode($trip->tripPhoto, true);
    
            if ($photos && is_array($photos)) {
                foreach ($photos as $photoUrl) {
                    $filePath = str_replace(asset(Storage::url('')), '', $photoUrl);
    
                    // Check if the file exists
                    if (Storage::disk('public')->exists($filePath)) {
    
                        // Delete the file
                        Storage::disk('public')->delete($filePath);
                    } else {
                        throw new \Exception('Cannot delete Image!');
                    }
                }
            }
    
            // Retrieve all prices associated with the Stripe product
            $prices = $this->stripe->prices->all(['product' => $trip->stripe_product_id]);
    
            foreach ($prices->data as $price) {
                \Log::info('Setting Stripe price inactive: ' . $price->id);
                $this->stripe->prices->update($price->id, ['active' => false]);
            }
    
            $this->stripe->products->update($trip->stripe_product_id, ['active' => false]);
            // $this->stripe->products->delete($trip->stripe_product_id);
            // Delete the trip from the database
            $trip->delete();
            \Log::info('Deleted Trip.');
    
    
            if (!$trip->exists) {
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
    
    
    private function isJson($data) {
        return ((is_string($data) &&
                (is_object(json_decode($data)) ||
                is_array(json_decode($data))))) ? true : false;
    }
    



}
