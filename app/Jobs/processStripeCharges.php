<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class processStripeCharges implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(){
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
         $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $charges = $this->stripe->charges->search(['query'=>"status: 'succeeded' "]);
        Cache::put('stripe_charges', $charges, now()->addHours(1));
    }
}
