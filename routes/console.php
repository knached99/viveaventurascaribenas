<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ExpireStripeCoupons;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

// app(Schedule::class)->command(ExpireStripeCoupons::class)->daily();
app(Schedule::class)->command(ExpireStripeCoupons::class)->everyMinute()->runInBackground();
