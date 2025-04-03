<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Testimonials;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
        $this->app->singleton(\App\Services\GeoJSService::class, function($app){
            return new \App\Services\GeoJSService();
        });
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Globally retreiving number of testimonials 
        // for authenticated users only 
        View::composer('*', function($view){
            $numTestimonials = 0; // initialize to 0 even when user is not logged in
            if(Auth::check()){
                $numTestimonials = Testimonials::where('testimonial_approval_status', 'Pending')->count();
                $view->with('numTestimonials', $numTestimonials);
            }
        });
    }
}
