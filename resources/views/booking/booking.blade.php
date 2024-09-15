@php
    $heading =
        $trip->tripAvailability === 'unavailable'
            ? 'Trip unavailable!'
            : 'Finish booking your trip to ' . $trip->tripLocation;
    $message =
        $trip->tripAvailability === 'unavailable'
            ? 'You cannot book this trip as it is currently unavailable.'
            : 'Fill out the form to complete booking your trip!';
    $tripPhotos = json_decode($trip->tripPhoto, true);
    $firstPhotoURL = !empty($tripPhotos) ? asset($tripPhotos[0]) : asset('assets/images/booking_page_bg.webp');
@endphp

<x-travelcomponents.header />
<x-travelcomponents.navbar />

<div id="booking" class="section"
    style="background-image: url({{ $firstPhotoURL }}); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="section-center py-5">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-lg-7 text-center text-white mb-4">
                    <div class="booking-cta">
                        <h1 class="display-4">{{ $heading }}</h1>
                        <p class="lead">{{ $message }}</p>
                    </div>
                </div>
                <div class="col-lg-7 col-md-10 col-sm-12 d-flex justify-content-center">
                    <!-- Form Start -->
                    @if ($trip->tripAvailability === 'unavailable')
                        <div class="alert alert-warning">
                            This trip is unavailable at the moment.
                        </div>
                    @else
                        <div class="container-fluid">
                            <livewire:forms.booking-form :tripID="$tripID" />
                        </div>
                    @endif
                    <!-- Form End -->
                </div>
            </div>
        </div>
    </div>
</div>

<x-travelcomponents.footer />
