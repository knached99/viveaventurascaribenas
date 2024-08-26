<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Admin;
use Livewire\Volt\Volt;
use App\Http\Livewire\TripInfoForm;

// Landing Pages 

Route::get('/', [Home::class, 'homePage'])->name('/');

Route::get('/landing/destination/{tripID}', [Home::class, 'getDestinationDetails'])->name('landing.destination');
Route::get('/about', [Home::class, 'aboutPage'])->name('about');

Route::get('/destinations', [Home::class, 'destinationsPage'])->name('destinations');

Route::get('/gallery', [Home::class, 'galleryPage'])->name('gallery');

Route::get('/contact', [Home::class, 'contactPage'])->name('contact');

Route::get('/booking/{tripID}', [Home::class, 'bookingPage'])->name('booking');
// Protected Routes 
Route::group(['middleware' => 'auth', 'verified'], function () {
    Route::get('/admin/dashboard', [Admin::class, 'dashboardPage'])->name('admin.dashboard');
    Route::get('/admin/profile', [Admin::class, 'profilePage'])->name('admin.profile');
    Route::get('/admin/all-trips', [Admin::class, 'allTripsPage'])->name('admin.all-trips');
    Route::get('/admin/testimonials', [Admin::class, 'testimonialsPage'])->name('admin.testimonials');
    Route::get('/admin/testimonial/{testimonialID}', [Admin::class, 'testimonialPage'])->name('admin.testimonial');
    Route::put('/admin/testimonial/approveTestimonial/{testimonialID}', [Admin::class, 'approveTestimonial'])->name('admin.testimonial.approveTestimonial');
    Route::put('/admin/testimonial/declineTestimonial/{testimonialID}', [Admin::class, 'declineTestimonial'])->name('admin.testimonial.declineTestimonial');
    Route::delete('/admin/testimonial/delete/{testimonialID}', [Admin::class, 'deleteTestimonial'])->name('admin.testimonial.delete');
    Volt::route('/admin/createTrip', 'pages.create-trip')->name('admin.create-trip');
    Route::get('/admin/trip/{tripID}', [Admin::class, 'getTripDetails'])->name('admin.trip');
    Route::delete('/admin/trip/delete/{tripID}', [Admin::class, 'deleteTrip'])->name('admin.trip.delete');
});
// Route::view('admin/dashboard', 'admin/dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('admin.dashboard');

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

require __DIR__.'/auth.php';
