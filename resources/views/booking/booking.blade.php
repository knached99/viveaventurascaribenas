@php
    $heading = $trip->tripAvailability === 'unavailable' ? 'Trip unavailable!' : 'Finish booking your trip to '.$trip->tripLocation;
    $message = $trip->tripAvailability === 'unavailable' ? 'You cannot book this trip as it is currently unavailable.' : 'Fill out the form to complete booking your trip!';
@endphp

<x-travelcomponents.header />
<x-travelcomponents.navbar />
<div id="booking" class="section">
    <div class="section-center">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-md-push-5">
                    <div class="booking-cta">
                        <h1>{{ $heading }}</h1>
                        <p style="font-size: 30px;">{{ $message }}</p>

                    </div>
                </div>
                <div class="col-md-4 col-md-pull-7">
                    <!-- Form Start -->
                    @if($trip->tripAvailability === 'unavailable')
                    @else
                    <livewire:forms.booking-form :tripID="$tripID" />
                    <!-- Form End -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<x-travelcomponents.footer />
