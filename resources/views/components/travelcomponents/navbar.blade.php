
@php

$isLandingDestination = \Route::currentRouteName() === 'landing.destination' || \Route::currentRouteName() === 'booking.success' || \Route::currentRouteName() === 'booking.cancel';

$linkClass = $isLandingDestination ? 'nav-link text-dark' : 'nav-link';

$fontSizeStyle = 'font-size: 20px;';

@endphp

<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light"  id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" style="background-color: rgb(239, 173, 76);" href="{{ route('/') }}">viveaventuras<span>
                caribenas</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
            aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

  <div class="collapse navbar-collapse" id="ftco-nav">
    <ul class="navbar-nav ml-auto">
    
        <li class="nav-item {{ request()->routeIs('/') ? 'active' : '' }}">
            <a href="/" class="{{ $linkClass }}" style="{{ $fontSizeStyle }}">Home</a>
        </li>
        
        <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
            <a href="{{ route('about') }}" class="{{ $linkClass }}" style="{{ $fontSizeStyle }}">About</a>
        </li>

        <li class="nav-item {{ request()->routeIs('destinations') ? 'active' : '' }}">
            <a href="{{ route('destinations') }}" class="{{ $linkClass }}" style="{{ $fontSizeStyle }}">Destinations</a>
        </li>

        <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
            <a href="{{ route('contact') }}" class="{{ $linkClass }}" style="{{ $fontSizeStyle }}">Contact</a>
        </li>

        {{-- <li class="nav-item cta">
            <a href="{{ $isLandingDestination ? route('booking', ['tripID' => last(request()->segments())]) : route('destinations') }}"
               class="nav-link">
               Book Now
            </a>
        </li> --}}
    </ul>
</div>

    </div>
</nav>
<!-- END nav -->
