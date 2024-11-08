@props(['popularTrips'])
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4" style="font-weight: 900;">Most Popular Attractions</h2>
            </div>
        </div>
        <div class="row">
            @foreach ($popularTrips as $trip)
                @php

                    $tripPhotos = isset($trip['image']) ? json_decode($trip['image'], true) : [];
                @endphp

                <div class="col-md-3 ftco-animate">
                    <div class="project-destination">
                        <a href="{{ url('/destination/' . $trip['slug']) }}" class="img"
                            style="background-image: url({{ !empty($tripPhotos) ? $tripPhotos[0] : asset('assets/images/image_placeholder.jpg') }});">
                            <div class="text">
                                <h3 style="font-weight: 900; color: #fff;">{{ $trip['name'] }}</h3>
                                <span>{{ $trip['count'] }} bookings</span>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
