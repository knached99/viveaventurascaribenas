<x-travelcomponents.header />

<x-travelcomponents.navbar />

<div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('assets/images/contactImage.jpg') }}');"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center"
            data-scrollax-parent="true">
            <div class="col-md-9 text text-center ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
                {{-- <a href="https://vimeo.com/45830194"
                    class="icon-video popup-vimeo d-flex align-items-center justify-content-center mb-4">
                    <span class="ion-ios-play"></span>
                </a> --}}
                <p class="caps" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Beyond the Ordinary, Into the Extraordinary </p>
                <h1 style="font-weight: 900;" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Make Your
                    Adventure
                    Amazing With Us</h1>
            </div>
        </div>
    </div>
</div>

{{-- <section class="ftco-section ftco-no-pb ftco-no-pt">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="search-wrap-1 ftco-animate p-4">
                    <form action="#" class="search-property-1">
                        <div class="row">
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Destination</label>
                                    <div class="form-field">
                                        <div class="icon"><span class="ion-ios-search"></span></div>
                                        <input type="text" class="form-control" placeholder="Search place">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Check-in date</label>
                                    <div class="form-field">
                                        <div class="icon"><span class="ion-ios-calendar"></span></div>
                                        <input type="text" class="form-control checkin_date"
                                            placeholder="Check In Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Check-out date</label>
                                    <div class="form-field">
                                        <div class="icon"><span class="ion-ios-calendar"></span></div>
                                        <input type="text" class="form-control checkout_date"
                                            placeholder="Check Out Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Price Limit</label>
                                    <div class="form-field">
                                        <div class="select-wrap">
                                            <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                            <select name="" id="" class="form-control">
                                                <option value="">$5,000</option>
                                                <option value="">$10,000</option>
                                                <option value="">$50,000</option>
                                                <option value="">$100,000</option>
                                                <option value="">$200,000</option>
                                                <option value="">$300,000</option>
                                                <option value="">$400,000</option>
                                                <option value="">$500,000</option>
                                                <option value="">$600,000</option>
                                                <option value="">$700,000</option>
                                                <option value="">$800,000</option>
                                                <option value="">$900,000</option>
                                                <option value="">$1,000,000</option>
                                                <option value="">$2,000,000</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg align-self-end">
                                <div class="form-group">
                                    <div class="form-field">
                                        <input type="submit" value="Search" class="form-control btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section> --}}

<section class="ftco-section services-section bg-light">
    <div class="container">
        <div class="row d-flex">
            <!-- First column (Heading and description) -->
            <div class="col-12 col-md-6 order-md-last heading-section pl-md-5 ftco-animate">
                <h2 class="mb-4">It's time to start your adventure</h2>
                <p>Welcome to Vive Aventuras Caribeñas, your gateway to unforgettable travel experiences in the Caribbean and beyond. 
                   We specialize in creating immersive, curated journeys that take you to stunning destinations, 
                   where every moment is filled with adventure, culture, and natural beauty. 
                   Our mission is to offer you experiences that leave a lasting impression, making each trip a memory you’ll cherish forever.</p>
                <p><a href="{{ route('destinations') }}" class="btn btn-primary py-3 px-4">Book a Travel</a></p>

                <div 
                    style="background-image:url({{asset('assets/images/old_san_juan.png')}}); background-size: cover; background-repeat: no-repeat; background-position: center; width: 100%; height: 300px; max-height: 400px;">
                </div>
            </div>

            <!-- Second column (Services) -->
            <div class="col-12 col-md-6">
                <div class="row">
                    <!-- Travel Arrangements Service -->
                    <div class="col-12 col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="flaticon-route"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Travel Arrangements</h3>
                                <p>At Vive Aventuras Caribeñas, we pride ourselves on offering safe and convenient travel arrangements designed to 
                                   provide our travelers with unparalleled comfort and unforgettable experiences. 
                                   From personalized itineraries to exclusive accommodations and private transportation, 
                                   every detail is meticulously crafted to ensure a stress-free, indulgent journey.
                                   Whether you're seeking adventure, relaxation, or cultural immersion, 
                                   our expert team ensures that each trip is tailored to your unique preferences, 
                                   allowing you to focus on creating memories that will last a lifetime. 
                                   With us, you're not just traveling—you're embarking on an extraordinary adventure.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Activities Service -->
                    <div class="col-12 col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="flaticon-paragliding"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Activities</h3>
                                <p>We provide you with a diverse array of activities, perfect for every type of traveler. 
                                   Whether you're seeking adventure, relaxation, or cultural exploration, 
                                   you'll find something to suit your interests at any of our destinations.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Transportation Services -->
                    <div class="col-12 col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="flaticon-tour-guide"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Transportation Services</h3>
                                <p>Feel confident in the measures we take to provide you with safety and convenience 
                                   when it comes to exploring your chosen destination! 
                                   With a simple request, enjoy access to beautiful beaches, restaurants, and much more.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-counter img" id="section-counter">
    <div class="container">
        <div class="row d-flex">
            <div class="col-md-6 d-flex">
          <div class="img d-flex align-self-stretch"
            style="background-image: url({{ asset('assets/images/homeBg.png') }}); 
                    margin-top: 250px; 
                    background-size: contain; 
                    background-repeat: no-repeat; 
                    background-position: center; 
                    width: 1000px; 
                    height: 500px;"> <!-- Set an appropriate height -->
        </div>



            </div>
            <div class="col-md-6 pl-md-5 py-5">
                <div class="row justify-content-start pb-3">
                    <div class="col-md-12 heading-section ftco-animate">
                        <h2 class="mb-4">Make Your Experience Memorable and Safe With Us</h2>
                        <p>At Vive Aventuras Caribeñas, we’re dedicated to helping you embrace new adventures with excitement and ease. 
                        Our experts will thoughtfully craft itineraries designed to offer you a journey filled with discovery, fun, and unique experiences.
                         Every moment is an opportunity to explore something new, immerse yourself in vibrant cultures, 
                         and create lasting memories—all while enjoying the thrill of the unknown without a care in the world. 
                         Let us handle the details, so you can focus on the adventure ahead!</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center mb-4">
                            <div class="text">
                                <strong class="number" data-number="{{ $totalBookings }}">0</strong>
                                <span>Successful Bookings</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center mb-4">
                            <div class="text">
                                <strong class="number" data-number="{{ $totalCustomers }}">0</strong>
                                <span>Happy Customers</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center mb-4">
                            <div class="text">
                                <strong class="number" data-number="{{ $totalTrips }}">0</strong>
                                <span>Place Explored</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Most Popular Attractions -->
@if (!empty($popularTrips))
    <x-travelcomponents.most-popular-attractions :popularTrips="$popularTrips" />
@else
@endif
<!-- / Most Popular Attractions -->

<!-- Available Bookings Component -->
@if (!$trips->isEmpty())
    <x-travelcomponents.available-bookings :trips="$trips" :mostPopularTripId="$mostPopularTripId" />
@else
@endif
<!-- End Available Bookings Component -->


<!-- Start Testimonials -->
@if (!$testimonials->isEmpty())
    <x-travelcomponents.testimonials :testimonials="$testimonials" />
    <!-- End Testimonials -->
@else
@endif


<livewire:forms.testimonial-form />
<!-- Testimonial Submission -->

<!-- Photo Galleries Component -->
@if (!$photos->isEmpty())
    <x-travelcomponents.photo-gallery :photos="$photos" />
@else
@endif
<x-travelcomponents.footer />
