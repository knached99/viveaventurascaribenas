<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ExpireCoupons;
use Illuminate\Support\Facades\Log;

class ExpireStripeCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'couponexp:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs daily to check and delete expired Stripe coupons from Stripe and the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('ExpireStripeCoupons command started.');

        // Dispatch the ExpireCoupons job
        ExpireCoupons::dispatch();

        // Output and logging
        $this->info('ExpireCoupons job dispatched and coupons check started.');
        Log::info('ExpireCoupons job dispatched successfully.');

        $this->info('ExpireStripeCoupons command completed.');
        Log::info('ExpireStripeCoupons command finished.');
    }
}
