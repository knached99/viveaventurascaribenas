<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;

// Landing Pages 


Route::get('/', [Home::class, 'homePage'])->name('/');

Route::get('/about', [Home::class, 'aboutPage'])->name('about');

Route::get('/destinations', [Home::class, 'destinationsPage'])->name('destinations');

Route::get('/blog', [Home::class, 'blogPage'])->name('blog');

Route::get('/gallery', [Home::class, 'galleryPage'])->name('gallery');

Route::get('/contact', [Home::class, 'contactPage'])->name('contact');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
