<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Carbon\Carbon;
use App\Models\TripsModel;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class ExpireCoupons implements ShouldQueue 
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    

    public function handle(): void
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        
        Log::info('Starting to expire coupons job.');
    
        // Retrieve all trips with coupons
        $trips = TripsModel::select('stripe_coupon_id')->get();
       
        
        Log::info('Found ' . $trips->count() . ' trips with coupons to check.');
    
        foreach ($trips as $trip) {
            Log::info('Checking coupon for trip: ' . $trip->id);
    
            // Skip trips without a valid coupon ID
            if (empty($trip->stripe_coupon_id) || trim($trip->stripe_coupon_id) === '') {
                Log::warning('Invalid or empty coupon ID for trip: ' . $trip->id);
                continue; // Skip invalid entries
            }

            
            // Retrieve the coupon from Stripe
            try {
                $coupon = $stripe->coupons->retrieve($trip->stripe_coupon_id);
            } catch (\Stripe\Exception\InvalidArgumentException $e) {
                Log::error('Error retrieving coupon for trip ' . $trip->id . ': ' . $e->getMessage());
                continue; // Skip to the next trip if coupon retrieval fails
            }
    
            // Calculate the expiration date based on the coupon creation date and duration
            $createdDate = Carbon::createFromTimestamp($coupon->created);
            $expirationDate = $createdDate->addMonths($coupon->duration_in_months);
    
            Log::info('Coupon for trip ' . $trip->id . ' expires on ' . $expirationDate->toDateString());
    
            // Check if the coupon has expired
            if (Carbon::today()->gt($expirationDate)) {
                Log::info('Coupon for trip ' . $trip->id . ' has expired. Deleting coupon.');
    
                // Delete the coupon from Stripe and update the database
                try {
                    $stripe->coupons->delete($trip->stripe_coupon_id);
                    $trip->stripe_coupon_id = null;
                    $trip->save();
                    Log::info('Coupon for trip ' . $trip->id . ' deleted successfully.');
                } catch (\Exception $e) {
                    Log::error('Error deleting coupon for trip ' . $trip->id . ': ' . $e->getMessage());
                }
            } else {
                Log::info('Coupon for trip ' . $trip->id . ' is still valid.');
            }
        }
    
        Log::info('Expire coupons job completed.');
    }
    
}
