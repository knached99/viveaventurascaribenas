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
@endphp

<x-travelcomponents.header />
<x-travelcomponents.navbar />

<div class="section" id="booking-section">
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
                    @if ($trip->tripAvailability === 'unavailable')
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.2/color-thief.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const colorThief = new ColorThief();
        const sectionElement = document.getElementById('booking-section');
        const imageUrl = "{{ asset($tripPhotos[0]) }}";

        function updateBackground() {
            const img = new Image();
            img.crossOrigin = 'Anonymous'; // To avoid CORS issues
            img.src = imageUrl;
            img.onload = () => {
                // Create the dominant color
                const dominantColor = colorThief.getColor(img);
                const dominantColorRgb = `rgb(${dominantColor.join(',')})`;
                const backgroundColor = `rgba(${dominantColor.join(',')}, 0.5)`; // Faint color

                // Apply the color and image
                sectionElement.style.backgroundImage = `url(${imageUrl})`;
                sectionElement.style.backgroundSize = 'cover';
                sectionElement.style.backgroundPosition = 'center';
                sectionElement.style.backgroundRepeat = 'no-repeat';
                sectionElement.style.backgroundColor = backgroundColor;
                sectionElement.style.backgroundBlendMode =
                    'overlay'; // Ensures the color blends with the image
            };
        }

        updateBackground();
    });
</script>
