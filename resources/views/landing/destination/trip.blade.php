<x-travelcomponents.header/>

<x-travelcomponents.navbar/>

<section class="trip-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="trip-image" style="background-image: url('{{ $trip->tripPhoto ? asset('storage/' . $trip->tripPhoto) : asset('assets/images/image_placeholder.jpg') }}');">
                </div>
                <div class="trip-details">
                    <h2>{{ $trip->tripLocation }}</h2>
                    
                  <!-- Average Star Rating -->
                    <div class="star-rating mb-3">
                        @php
                            $fullStars = floor($averageTestimonialRating);
                            $halfStar = ($averageTestimonialRating - $fullStars) >= 0.5;
                            $emptyStars = 5 - ($fullStars + ($halfStar ? 1 : 0));
                        @endphp

                        @for ($i = 1; $i <= $fullStars; $i++)
                            <i class="bx bxs-star star-icon text-warning"></i>
                        @endfor

                        @if ($halfStar)
                            <i class="bx bxs-star-half star-icon text-warning"></i>
                        @endif

                        @for ($i = 1; $i <= $emptyStars; $i++)
                            <i class="bx bxs-star star-icon text-secondary"></i>
                        @endfor

                        <span class="text-muted">({{ number_format($averageTestimonialRating, 1) }} / 5.0)</span>
                    </div>
                  <!-- End Average Star Rating -->
                    <span class="trip-price">${{ number_format($trip->tripPrice, 2) }} /person</span>
                    <p class="trip-duration">{{ \Carbon\Carbon::parse($trip->tripStartDate)->diffInDays($trip->tripEndDate) }} Days Tour</p>
                    <p class="trip-availability">
                     @switch($trip->tripAvailability)
                     @case('available')
                     <span class="success-badge">{{$trip->tripAvailability}}</span> 
                     @break 
                     @case('coming soon')
                     <span class="warning-badge">{{$trip->tripAvailability}}</span>
                     @break 
                     @case('unavailable')
                     <span class="danger-badge">{{$trip->tripAvailability}}</span>
                     @break 
                     @endswitch 
                    </p>
                    <p class="trip-description">{{ $trip->tripDescription }}</p>
                    <ul class="trip-info">
                        <li><img src="{{ asset('assets/images/calendar.png') }}" class="icon" />
                            {{ date('F jS, Y', strtotime($trip->tripStartDate)) }} - {{ date('F jS, Y', strtotime($trip->tripEndDate)) }}
                        </li>
                        @switch($trip->tripLandscape)
                            @case('Beach')
                                <li><img src="{{ asset('assets/images/beach.png') }}" class="icon" /> {{ $trip->tripLandscape }}</li>
                                @break
                            @case('City')
                                <li><img src="{{ asset('assets/images/buildings.png') }}" class="icon" /> {{ $trip->tripLandscape }}</li>
                                @break
                            @case('Country Side')
                                <li><img src="{{ asset('assets/images/farm.png') }}" class="icon" /> {{ $trip->tripLandscape }}</li>
                                @break
                            @case('Mountainous')
                                <li><img src="{{ asset('assets/images/mountain.png') }}" class="icon" /> {{ $trip->tripLandscape }}</li>
                                @break
                            @case('Forested')
                                <li><img src="{{ asset('assets/images/forest.png') }}" class="icon" /> {{ $trip->tripLandscape }}</li>
                                @break
                        @endswitch
                    </ul>
                </div>

              <!-- Testimonials Slider -->
                <div class="testimonials-slider mt-4">
                    <h3>What other travellers have to say</h3>
                    <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($testimonials as $key => $testimonial)
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <div class="card testimonial-card">
                                        <div class="card-body">
                                            <p class="card-text">“{{ $testimonial->testimonial }}”</p>
                                            <h5 class="card-title">{{ $testimonial->name }}</h5>
                                            <div class="star-rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="bx bxs-star star-icon {{ $i <= $testimonial->trip_rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                @endfor
                                            </div>
                                            <p class="text-muted"><i class='bx bx-calendar' style="font-size: 30px;"></i> {{ date('F jS, Y', strtotime($testimonial->created_at)) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Custom buttons -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                            <span class="visually-hidden"><i class='bx bx-left-arrow-alt' ></i></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                            <span class="visually-hidden"><i class='bx bx-right-arrow-alt'></i></span>
                        </button>
                    </div>
                </div>
                <!-- End Testimonials Slider -->


            </div>
            <div class="col-md-4">
                <!-- Booking Widget -->
                <div class="booking-widget">
                    <h3>Book this Trip</h3>
                    <form action="#" method="POST">
                        <!-- Booking form fields go here -->
                        <button type="submit" class="btn btn-primary">Book Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<x-travelcomponents.footer/>
