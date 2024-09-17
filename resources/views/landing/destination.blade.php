@php
    use Carbon\Carbon;

    $today = Carbon::today();

    $startDate = Carbon::parse($trip->tripStartDate);
    $endDate = Carbon::parse($trip->tripEndDate);
    $tripPhotos = json_decode($trip->tripPhoto, true);
    $landscapes = isset($trip->tripLandscape) ? json_decode($trip->tripLandscape, true) : [];
@endphp

<x-travelcomponents.header />

<x-travelcomponents.navbar />

<section class="trip-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- Photo Grid Section -->
                <div class="photo-grid">
                    @if (!empty($tripPhotos))
                        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false"
                            style="border-radius: 8px;">
                            <div class="carousel-inner">
                                @foreach ($tripPhotos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $photo }}" class="d-block w-100"
                                            alt="Photo {{ $index + 1 }}">
                                    </div>
                                @endforeach
                            </div>

                            {{-- Carousel navigation buttons --}}
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @else
                        <div class="photo-item">
                            <img src="{{ asset('assets/images/image_placeholder.jpg') }}" class="d-block w-100"
                                style="height: 300px;" />
                        </div>
                    @endif
                </div>

                <!-- End Photo Grid Section -->



                <div class="trip-details">
                    <h2>{{ $trip->tripLocation }}
                        <!-- If this is the most popular booking, then dispaly the badge here-->
                        @if ($isMostPopular)
                            <img src="{{ asset('assets/theme_assets/assets/img/popularBadge.webp') }}"
                                style="width: 100px; height: 100px;" />
                        @endif
                    </h2>



                    <!-- Average Star Rating -->
                    <div class="star-rating mb-3 text-dark">
                        @php
                            // Calculate the number of full stars
                            $fullStars = floor($averageTestimonialRating);

                            // Determine if there is a half star needed
                            $halfStar = $averageTestimonialRating - $fullStars >= 0.5;

                            // Calculate the number of empty stars
                            $emptyStars = 5 - ($fullStars + ($halfStar ? 1 : 0));

                            // Calculate the fraction of the star needed
                            $fraction = $averageTestimonialRating - $fullStars;
                        @endphp

                        <!-- Render full stars -->
                        @for ($i = 1; $i <= $fullStars; $i++)
                            <i class="bx bxs-star star-icon text-warning"></i>
                        @endfor

                        <!-- Render half star if needed -->
                        @if ($fraction >= 0.25 && $fraction < 0.75)
                            <i class="bx bxs-star-half star-icon text-warning"></i>
                        @elseif ($fraction >= 0.75)
                            <i class="bx bxs-star star-icon text-warning"></i>
                        @endif

                        <!-- Render empty stars -->
                        @for ($i = 1; $i <= $emptyStars; $i++)
                            <i class="bx bxs-star star-icon text-secondary"></i>
                        @endfor



                        <!-- Display the average rating -->
                        <span class="text-dark">({{ number_format($averageTestimonialRating, 1) }} / 5.0)</span>
                        <span class="mt-3 block text-dark">
                            <br />
                            @if ($testimonials->isEmpty())
                                Be among the first to experience this trip and share your review! Your feedback will
                                help others discover this amazing adventure.
                            @endif
                        </span>
                    </div>



                    <span class="trip-price text-dark">${{ number_format($trip->tripPrice, 2) }} /person</span>
                    <p class="trip-duration text-dark">
                        <!-- End Average Star Rating -->


                        {{ \Carbon\Carbon::parse($trip->tripStartDate)->diffInDays($trip->tripEndDate) }} Days Tour
                    </p>
                    <p class="trip-availability text-dark">
                        @switch($trip->tripAvailability)
                            @case('available')
                                <span class="success-badge">{{ $trip->tripAvailability }}</span>
                            @break

                            @case('coming soon')
                                <span class="warning-badge">{{ $trip->tripAvailability }}</span>
                                <!-- Add a disclaimer -->
                                <br />
                                <span class="text-dark mt-3" style="font-style: italic; ">This trip will be available soon!
                                    Once
                                    we have enough travelers,
                                    dates will be released. Let us know your preferred month to travel, and we’ll do our best to
                                    accommodate.</span>
                            @break

                            @case('unavailable')
                                <span class="danger-badge">{{ $trip->tripAvailability }}</span>
                            @break
                        @endswitch
                    </p>
                    <p class="trip-description" style="color: #000;"> {!! $trip->tripDescription !!}.</p>
                    <ul class="trip-info">
                        <li style="color: #000; font-weight: bold;"><img
                                src="{{ asset('assets/images/calendar.png') }}" class="icon" />
                            {{ date('F jS, Y', strtotime($trip->tripStartDate)) }} -
                            {{ date('F jS, Y', strtotime($trip->tripEndDate)) }}
                        </li>


                        @if (is_array($landscapes))
                            <div class="d-flex align-items-center">
                                @foreach ($landscapes as $landscape)
                                    @switch($landscape)
                                        @case('Beach')
                                            <div style="text-align: center;">
                                                <img src="{{ asset('assets/images/beach.png') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="{{ $landscape }}"
                                                    style="height: 40px; width: 40px; margin: 5px;" />
                                                <span style="display: block;">Beach</span>
                                            </div>
                                        @break

                                        @case('City')
                                            <div style="text-align: center;">
                                                <img src="{{ asset('assets/images/buildings.png') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="{{ $landscape }}"
                                                    style="height: 40px; width: 40px; margin: 5px;" />
                                                <span style="display: block;">City</span>
                                            </div>
                                        @break

                                        @case('Country Side')
                                            <div style="text-align: center;">
                                                <img src="{{ asset('assets/images/farm.png') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="{{ $landscape }}"
                                                    style="height: 40px; width: 40px; margin: 5px;" />
                                                <span style="display: block;">Country Side</span>
                                            </div>
                                        @break

                                        @case('Mountainous')
                                            <div style="text-align: center;">
                                                <img src="{{ asset('assets/images/mountain.png') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="{{ $landscape }}"
                                                    style="height: 40px; width: 40px; margin: 5px;" />
                                                <span style="display: block;">Mountainous</span>
                                            </div>
                                        @break

                                        @case('Forested')
                                            <div style="text-align: center;">
                                                <img src="{{ asset('assets/images/forest.png') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" data-bs-title="{{ $landscape }}"
                                                    style="height: 40px; width: 40px; margin: 5px;" />
                                                <span style="display: block;">Forested</span>
                                            </div>
                                        @break
                                    @endswitch
                                @endforeach
                            </div>
                        @else
                            <!-- Don't display anything-->
                        @endif
                    </ul>

                    <!-- No refunds disclaimer -->
                    <div class="mt-4 text-lg" style="font-weight: normal; font-style: italic; color: #000;">
                        <p>
                            Please note that due to the time-sensitive nature and significant costs involved in
                            organizing our trips, we are unable to offer refunds once the booking is confirmed. We
                            deeply value your understanding and appreciate your support in helping us maintain the
                            high-quality experiences we strive to provide.
                        </p>
                    </div>
                </div>


                <!-- Activities Section -->
                <div class="border-bottom-1 border-secondary"> </div>
                <div style="border-bottom: 1px solid #1e293b"></div>
                <h2 class="m-3" style="font-weight: 900;">Trip Activities</h2>
                <p class="trip-description" style="color: #000;">{!! $trip->tripActivities !!}</p>
                <!-- End Activities Section -->

                <!-- Testimonials Slider -->
                <div class="testimonials-slider mt-4">
                    <h3>What other travellers have to say</h3>
                    @if (!$testimonials->isEmpty())
                        <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($testimonials as $key => $testimonial)
                                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                        <div class="card testimonial-card">
                                            <div class="card-body">
                                                <p class="card-text">“{{ $testimonial->testimonial }}”</p>
                                                <h5 class="card-title">{{ $testimonial->name }}</h5>
                                                <div class="star-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="bx bxs-star star-icon {{ $i <= $testimonial->trip_rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                    @endfor
                                                </div>
                                                <p class="text-muted"><i class='bx bx-calendar'
                                                        style="font-size: 30px;"></i>
                                                    {{ date('F jS, Y', strtotime($testimonial->created_at)) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Custom buttons -->
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                                <span class="visually-hidden"><i class='bx bx-left-arrow-alt'></i></span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                                <span class="visually-hidden"><i class='bx bx-right-arrow-alt'></i></span>
                            </button>
                        </div>
                    @else
                        <p style="font-size: 25px; color: #000; margin-left: 10px;">Be the first to leave a review!
                        </p>
                    @endif
                </div>
                <!-- End Testimonials Slider -->


            </div>
            @if ($trip->tripAvailability === 'unavailable')
                <div class="col-md-4">
                    <!-- Booking Widget -->
                    <div class="booking-widget">
                        <h3 class="text-secondary">Trip not available to book</h3>


                    </div>
                </div>
            @elseif($trip->num_trips === 0)
                <div class="col-md-4">
                    <div class="booking-widget">
                        <h3 class="text-secondary">Unfortunately, this trip is fully booked at the moment.</h3>
                    </div>
                </div>
            @else
                <div class="col-md-4">
                    <!-- Booking Widget -->
                    <div class="booking-widget">
                        <h3>{{ $trip->tripAvailability === 'coming soon' ? 'Reserve this Trip' : 'Book this Trip' }}
                        </h3>
                        <a href="{{ route('booking', ['tripID' => $tripID]) }}" type="submit"
                            class="btn">{{ $trip->tripAvailability === 'coming soon'
                                ? 'Reserve Now'
                                : 'Book
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            Now' }}</a>

                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<x-travelcomponents.footer />

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.2/color-thief.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const colorThief = new ColorThief();
        console.log('Initialized Colortheif object: ' + colorThief);
        const carouselElement = document.getElementById('carouselExample');
        console.log('Carousel Element: ' + carouselElement);
        const sectionElement = document.querySelector('.trip-section');
        console.log('Section Element: ' + sectionElement);

        function updateBackgroundColor() {
            const activeItem = carouselElement.querySelector('.carousel-item.active img');
            console.log('Active Item: ' + activeItem);
            if (activeItem) {
                const img = new Image();
                img.crossOrigin = 'Anonymous'; // To avoid CORS issues
                img.src = activeItem.src;
                console.log('Image Source: ' + img.src);

                img.onload = () => {
                    try {
                        // Extract the dominant color and the palette
                        const dominantColor = colorThief.getColor(img);
                        const palette = colorThief.getPalette(img, 2); // Get 2 colors from the image
                        console.log('Dominant Color: ' + dominantColor);
                        console.log('Palette: ' + palette);

                        // Use the dominant color and a secondary color from the palette
                        const dominantColorRgb = `rgb(${dominantColor.join(',')})`;
                        const secondaryColor = palette[1] || dominantColor; // Fallback to dominant color if palette is too small
                        const secondaryColorRgb = `rgb(${secondaryColor.join(',')})`;

                        // Create a gradient using the dominant and secondary colors
                        const backgroundColor = `linear-gradient(to bottom, ${dominantColorRgb}, ${secondaryColorRgb})`;

                        sectionElement.style.transition = 'background 0.5s ease'; // Smooth transition
                        sectionElement.style.background = backgroundColor;
                        console.log('Section Element: ' + sectionElement);

                    } catch (error) {
                        console.error('Color extraction failed:', error);
                        sectionElement.style.background = 'linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(255, 255, 255, 0.3))';
                    }
                };

                img.onerror = () => {
                    console.error('Failed to load image for color extraction.');
                    sectionElement.style.background = 'linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(255, 255, 255, 0.3))';
                };
            }
        }

        // Update background color on carousel slide change
        updateBackgroundColor(); // Initial call
        carouselElement.addEventListener('slid.bs.carousel', updateBackgroundColor);
    });
</script> --}}
