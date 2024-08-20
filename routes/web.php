<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Admin;
use Livewire\Volt\Volt;
use App\Http\Livewire\TripInfoForm;

// Landing Pages 

Route::get('/', [Home::class, 'homePage'])->name('/');

Route::get('/about', [Home::class, 'aboutPage'])->name('about');

Route::get('/destinations', [Home::class, 'destinationsPage'])->name('destinations');

Route::get('/gallery', [Home::class, 'galleryPage'])->name('gallery');

Route::get('/contact', [Home::class, 'contactPage'])->name('contact');

// Protected Routes 
Route::group(['middleware' => 'auth', 'verified'], function () {
    Route::get('/admin/dashboard', [Admin::class, 'dashboardPage'])->name('admin.dashboard');
    Route::get('/admin/profile', [Admin::class, 'profilePage'])->name('admin.profile');
    Route::get('/admin/trips', [Admin::class, 'tripsPage'])->name('admin.trips');
    Route::get('/admin/all-trips', [Admin::class, 'allTripsPage'])->name('admin.all-trips');
    Volt::route('/admin/createTrip', 'pages.create-trip')->name('admin.create-trip');
    Route::get('/admin/trip/{tripID}', [Admin::class, 'getTripDetails'])->name('admin.trip');
});
// Route::view('admin/dashboard', 'admin/dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('admin.dashboard');

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

require __DIR__.'/auth.php';
