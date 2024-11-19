@props(['trips', 'mostPopularTripId'])
@php

    use Stripe\StripeClient;
    $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
@endphp

<section class="ftco-section ftco-no-pt">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4" style="font-weight: 900;">Available Bookings</h2>
            </div>
        </div>
        <div class="row">
            @if ($trips->isEmpty())
                <h5 class="text-muted fw-normal font-italic text-center">We're currently planning new trips! Check back
                    soon for exciting upcoming adventures.</h5>
            @else
                @foreach ($trips as $trip)
               
                    @php
                        // Decode tripPhoto if it exists
                        $tripPhotos = isset($trip->tripPhoto) ? json_decode($trip->tripPhoto, true) : [];
                        $landscapes = isset($trip->tripLandscape) ? json_decode($trip->tripLandscape) : [];
                    @endphp
                    <div class="col-md-4 col-sm-6 ftco-animate">
                        <a href="{{ route('destination', ['slug' => $trip->slug]) }}">
                            <div class="project-wrap card">
                                <div id="carouselExampleControls{{ $loop->index }}" class="carousel slide"
                                    data-bs-interval="false">
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
                                        data-bs-target="#carouselExampleControls{{ $loop->index }}"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselExampleControls{{ $loop->index }}"
                                        data-bs-slide="next">
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

                                    @php
                                        $tripPrice = $trip->tripPrice; // Start with the original price
                                        $newPrice = $tripPrice; // Default to original price

                                        // Check if the coupon ID is valid
                                        if (!empty($trip->stripe_coupon_id)) {
                                            try {
                                                $coupon = $stripe->coupons->retrieve($trip->stripe_coupon_id);

                                                // Calculate new price based on the coupon
                                                if (isset($coupon)) {
                                                    if (isset($coupon->percent_off)) {
                                                        $discount = ($coupon->percent_off / 100) * $tripPrice;
                                                        $newPrice = $tripPrice - $discount;
                                                    }

                                                    if (isset($coupon->amount_off)) {
                                                        $newPrice = $tripPrice - $coupon->amount_off;
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                // Log the error message or handle it as needed
                                                \Log::error('Error retrieving coupon: ' . $e->getMessage());
                                                // Optionally set $newPrice to tripPrice if coupon retrieval fails
                                            }
                                        } else {
                                            // Handle case where coupon ID is not set
                                            \Log::warning('No coupon ID provided for trip: ' . $trip->id);
                                        }
                                    @endphp
                                    @if ($trip->tripAvailability == 'available')
                                        <span class="price">
                                            @if (isset($newPrice) && $newPrice < $tripPrice)
                                                <span class="text-decoration-line-through text-danger">
                                                    ${{ number_format($tripPrice, 2) }}
                                                </span>
                                                <span class="fw-bold">
                                                    ${{ number_format($newPrice, 2) }}
                                                </span>
                                            @else
                                                <span class="fw-bold">
                                                    ${{ number_format($tripPrice, 2) }}
                                                </span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-secondary fw-bold"> Trip Price Coming soon </span>
                                    @endif


                                    @if (!in_array($trip->tripAvailability, ['coming soon']))
                                        <span class="days">Duration:
                                            {{ \Carbon\Carbon::parse($trip->tripStartDate)->diffInDays($trip->tripEndDate) }}
                                            Days</span>
                                    @endif
                                    {{-- <h3><a style="text-decoration:underline; text-decoration-color: #3b82f6;" href="{{ route('destination', ['slug' => $trip->slug]) }}">{{ $trip->tripLocation }}</a> --}}
                                    {{-- </h3> --}}
                                    <h3 class="fw-bold">{{ $trip->tripLocation }}</h3>
                                   @php
                                    $badgeClass = '';
                                    $badgeText = '';
                                    
                                    if ($trip->num_trips == 0) {
                                        $badgeClass = 'danger-badge';
                                        $badgeText = 'Unavailable to book';
                                    } else {
                                        switch ($trip->tripAvailability) {
                                            case 'available':
                                                $badgeClass = 'success-badge';
                                                $badgeText = $trip->tripAvailability;
                                                break;
                                            case 'coming soon':
                                                $badgeClass = 'warning-badge';
                                                $badgeText = $trip->tripAvailability;
                                                break;
                                            case 'unavailable':
                                                $badgeClass = 'danger-badge';
                                                $badgeText = $trip->tripAvailability;
                                                break;
                                        }
                                    }
                                @endphp

                                <span class="{{ $badgeClass }}">{{ $badgeText }}</span>

                                  
                                    <ul>
                                        <li>
                                            <img src="{{ asset('assets/images/calendar.png') }}"
                                                style="width: 20px; height: 20px; margin: 5px;" />

                                            @if (!in_array($trip->tripAvailability, ['coming soon']))
                                                {{ date('F jS, Y', strtotime($trip->tripStartDate)) }} -
                                                {{ date('F jS, Y', strtotime($trip->tripEndDate)) }}
                                            @else
                                                trip dates coming soon
                                            @endif
                                        </li>
                                        {{-- @switch($trip->tripLandscape)
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
                                @endswitch --}}

                                        @if (is_array($landscapes))
                                            <div class="d-flex align-items-center overflow-x-auto">
                                                @foreach ($landscapes as $landscape)
                                                    @switch($landscape)
                                                        @case('Beach')
                                                            <img src="{{ asset('assets/images/beach.png') }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                data-bs-title="{{ $landscape }}"
                                                                style="height: 40px; width: 40px; margin: 5px;" />
                                                        @break

                                                        @case('City')
                                                            <img src="{{ asset('assets/images/buildings.png') }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                data-bs-title="{{ $landscape }}"
                                                                style="height: 40px; width: 40px; margin: 5px;" />
                                                        @break

                                                        @case('Country Side')
                                                            <img src="{{ asset('assets/images/farm.png') }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                data-bs-title="{{ $landscape }}"
                                                                style="height: 40px; width: 40px; margin: 5px;" />
                                                        @break

                                                        @case('Mountainous')
                                                            <img src="{{ asset('assets/images/mountain.png') }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                data-bs-title="{{ $landscape }}"
                                                                style="height: 40px; width: 40px; margin: 5px;" />
                                                        @break

                                                        @case('Forested')
                                                            <img src="{{ asset('assets/images/forest.png') }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                data-bs-title="{{ $landscape }}"
                                                                style="width: 40px; height: 40px; margin: 5px;" />
                                                        @break
                                                    @endswitch
                                                @endforeach
                                            </div>
                                        @else
                                            <!-- Don't display anything -->
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
