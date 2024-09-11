@props(['trips', 'mostPopularTripId'])
<section class="ftco-section ftco-no-pt">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4" style="font-weight: 900;">Available Bookings</h2>
            </div>
        </div>
        <div class="row">
            @foreach ($trips as $trip)
                @php
                    // Decode tripPhoto if it exists
                    $tripPhotos = isset($trip->tripPhoto) ? json_decode($trip->tripPhoto, true) : [];
                @endphp
                <div class="col-md-4 col-sm-6 ftco-animate">
                    <div class="project-wrap card">
                        <div id="carouselExampleControls{{ $loop->index }}" class="carousel slide"
                            data-bs-ride="carousel">
                            <div class="carousel-inner fixed-carousel-height">
                                @if (!empty($tripPhotos))
                                    @foreach ($tripPhotos as $index => $photo)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ $photo }}" class="d-block w-100 card-img-top"
                                                alt="Photo">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="carousel-item active">
                                        <img src="{{ asset('assets/images/image_placeholder.jpg') }}"
                                            class="d-block w-100 card-img-top" alt="Placeholder">
                                    </div>
                                @endif
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleControls{{ $loop->index }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleControls{{ $loop->index }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            @if ($trip->tripID == $mostPopularTripId)
                                <div class="popular-badge">
                                    <img src="{{ asset('assets/theme_assets/assets/img/popularBadge.webp') }}"
                                        alt="Popular" />
                                </div>
                            @endif
                        </div>
                        <div class="text p-4 card-body">
                            <span class="price">${{ number_format($trip->tripPrice, 2) }}/person</span>
                            <span
                                class="days">{{ \Carbon\Carbon::parse($trip->tripStartDate)->diffInDays($trip->tripEndDate) }}
                                Days</span>
                            <h3><a
                                    href="{{ route('landing.destination', ['tripID' => $trip->tripID]) }}">{{ $trip->tripLocation }}</a>
                            </h3>
                            @switch($trip->tripAvailability)
                                @case('available')
                                    <span class="success-badge">{{ $trip->tripAvailability }}</span>
                                @break

                                @case('coming soon')
                                    <span class="warning-badge">{{ $trip->tripAvailability }}</span>
                                @break

                                @case('unavailable')
                                    <span class="danger-badge">{{ $trip->tripAvailability }}</span>
                                @break
                            @endswitch
                            <ul>
                                <li>
                                    <img src="{{ asset('assets/images/calendar.png') }}"
                                        style="width: 20px; height: 20px; margin: 5px;" />
                                    {{ date('F jS, Y', strtotime($trip->tripStartDate)) }} -
                                    {{ date('F jS, Y', strtotime($trip->tripEndDate)) }}
                                </li>
                                @switch($trip->tripLandscape)
                                    @case('Beach')
                                        <li><img src="{{ asset('assets/images/beach.png') }}"
                                                style="width: 40px; height: 40px; margin: 5px;" /> {{ $trip->tripLandscape }}
                                        </li>
                                    @break

                                    @case('City')
                                        <li><img src="{{ asset('assets/images/buildings.png') }}"
                                                style="width: 40px; height: 40px; margin: 5px;" />{{ $trip->tripLandscape }}
                                        </li>
                                    @break

                                    @case('Country Side')
                                        <li><img src="{{ asset('assets/images/farm.png') }}"
                                                style="width: 40px; height: 40px; margin: 5px;" />{{ $trip->tripLandscape }}
                                        </li>
                                    @break

                                    @case('Mountainous')
                                        <li><img src="{{ asset('assets/images/mountain.png') }}"
                                                style="width: 40px; height: 40px; margin: 5px;" />{{ $trip->tripLandscape }}
                                        </li>
                                    @break

                                    @case('Forested')
                                        <li><img src="{{ asset('assets/images/forest.png') }}"
                                                style="width: 40px; height: 40px; margin: 5px;" />{{ $trip->tripLandscape }}
                                        </li>
                                    @break
                                @endswitch
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
