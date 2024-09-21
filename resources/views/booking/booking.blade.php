@php
    $heading =
        $trip->num_trips === 0
            ? 'This trip is fully booked!'
            : ($trip->tripAvailability === 'unavailable'
                ? 'Trip unavailable!'
                : 'Finish booking your trip to ' . $trip->tripLocation);

    $message =
        $trip->num_trips === 0
            ? 'Unfortunately, this trip is fully booked at the moment.'
            : ($trip->tripAvailability === 'unavailable'
                ? 'You cannot book this trip as it is currently unavailable.'
                : 'Fill out the form to complete booking your trip!');

    $tripPhotos = json_decode($trip->tripPhoto, true);
    $firstPhotoURL = !empty($tripPhotos) ? asset($tripPhotos[0]) : asset('assets/images/booking_page_bg.webp');
@endphp

<x-travelcomponents.header />
<x-travelcomponents.navbar />

<div id="booking" class="section"
    style="background-image: url({{ $firstPhotoURL }}); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="section-center py-5 d-flex flex-column justify-content-center align-items-center"
        style="min-height: 100vh;">
        <div class="container text-center">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-7 col-md-10 text-center text-white mb-4">
                    <div class="booking-cta">
                        <h1>{{ $heading }}</h1>
                        <p class="lead">{{ $message }}</p>
                    </div>
                </div>
                <div class="col-lg-7 col-md-10 col-sm-12">
                    <!-- Form Start -->
                    @if ($trip->tripAvailability === 'unavailable' || $trip->num_trips === 0)
                    @else
                        <div class="booking-form-wrapper p-4 rounded">
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
