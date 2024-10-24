<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Analytics;
use App\Http\Controllers\Admin;
use App\Http\Controllers\PhotoGalleryController;
use Livewire\Volt\Volt;
use App\Http\Livewire\TripInfoForm;

// Landing Pages 

Route::get('/', [Home::class, 'homePage'])->name('/');

Route::get('/landing/destination/{slug}', [Home::class, 'getDestinationDetails'])->name('landing.destination');
Route::get('/about', [Home::class, 'aboutPage'])->name('about');

Route::get('/destinations', [Home::class, 'destinationsPage'])->name('destinations');

Route::get('/gallery', [Home::class, 'galleryPage'])->name('gallery');

Route::get('/contact', [Home::class, 'contactPage'])->name('contact');

Route::get('/booking/{slug}', [Home::class, 'bookingPage'])->name('booking');
Route::get('/success', [Home::class, 'bookingSuccess'])->name('booking.success');
Route::get('/booking/{tripID}/cancel', [Home::class, 'bookingCancel'])->name('booking.cancel');
Route::get('/reservation-confirmed/{reservationID}', [Home::class, 'reservationConfirmed'])->name('reservation-confirmed');

// 2FA Challenge 

Route::get('/two-factor-challenge', function(){
    return view('auth.two-factor-challenge'); 
})->name('two-factor.challenge');

Route::post('/two-factor-challenge', function(Request $request){
    $user = Auth::loginUsingId(session('login.id'));

    if(!$user->verifyTwoFactorCode($request->code)){
        throw ValidationException::withMessages([
            'code' => __('The provided two-factor authentication code was invalid.'),
        ]);
    }

    Auth::login($user, session('login.remember'));

    return redirect()->intended();
})->name('two-factor.verify');


// Protected Routes 
Route::group(['middleware' => 'auth', 'verified'], function () {
    Route::get('/admin/dashboard', [Admin::class, 'dashboardPage'])->name('admin.dashboard');
    Route::get('/admin/profile', [Admin::class, 'profilePage'])->name('admin.profile');
    Route::get('/admin/analytics', [Analytics::class, 'showAnalytics'])->name('admin.analytics');
    Route::get('/admin/{bookingID}/booking', [Admin::class, 'bookingInfo'])->name('admin.booking');
    Route::get('/admin/all-trips', [Admin::class, 'allTripsPage'])->name('admin.all-trips');
    Route::get('/admin/testimonials', [Admin::class, 'testimonialsPage'])->name('admin.testimonials');
    Route::get('/admin/testimonial/{testimonialID}', [Admin::class, 'testimonialPage'])->name('admin.testimonial');
    Route::put('/admin/testimonial/approveTestimonial/{testimonialID}', [Admin::class, 'approveTestimonial'])->name('admin.testimonial.approveTestimonial');
    Route::put('/admin/testimonial/declineTestimonial/{testimonialID}', [Admin::class, 'declineTestimonial'])->name('admin.testimonial.declineTestimonial');
    Route::delete('/admin/testimonial/delete/{testimonialID}', [Admin::class, 'deleteTestimonial'])->name('admin.testimonial.delete');
    Volt::route('/admin/createTrip', 'pages.create-trip')->name('admin.create-trip');
    Volt::route('/admin/uploadPhoto', 'pages.photo-gallery-upload')->name('admin.photo-gallery-upload');
    Route::get('/admin/trip/{tripID}', [Admin::class, 'getTripDetails'])->name('admin.trip');
    Route::delete('/admin/trip/delete/{tripID}', [Admin::class, 'deleteTrip'])->name('admin.trip.delete');
    Route::get('/admin/reservation/{reservationID}', [Admin::class, 'getReservationDetails'])->name('admin.reservation');
    
    // Photo Gallery 

    Route::get('/admin/photo-gallery', [PhotoGalleryController::class, 'retrivePhotoGallery'])->name('admin.photo-gallery');
    Route::delete('/admin/deletePhotosFromGallery/{photoID}', [PhotoGalleryController::class, 'deletePhotosFromGallery'])->name('admin.deletePhotosFromGallery');
});
// Route::view('admin/dashboard', 'admin/dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('admin.dashboard');

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

require __DIR__.'/auth.php';
