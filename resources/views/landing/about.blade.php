<x-travelcomponents.header />
<x-travelcomponents.navbar />

<section class="hero-wrap hero-wrap-2 js-fullheight"
    style="background-image: url({{ asset('assets/images/tropicalVacay.jpg') }});" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <h1 class="mb-3 bread" style="font-weight:900;">About Us</h1>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section services-section bg-light">
    <div class="container">
        <div class="row d-flex">
            <!-- Right column with text and heading -->
            <div class="col-12 col-md-6 order-md-last heading-section pl-md-5 ftco-animate">
                <h2 class="mb-4">Start your adventure with us!</h2>
                <p>
                    Welcome to Vive Aventuras Caribeñas, your gateway to unforgettable travel experiences in the Caribbean and beyond. 
                    We specialize in creating immersive, curated journeys that take you to stunning destinations, 
                    where every moment is filled with adventure, culture, and natural beauty. 
                    Our mission is to offer you experiences that leave a lasting impression, making each trip a memory you’ll cherish forever.
                </p>
            </div>

            <!-- Left column with services -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="flaticon-route"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Travel Arrangements</h3>
                                <p>At Vive Aventuras Caribeñas, we pride ourselves on offering safe and convenient travel arrangements designed to provide our travelers with unparalleled comfort and unforgettable experiences. 
                                From personalized itineraries to exclusive accommodations and private transportation, every detail is meticulously crafted to ensure a stress-free,
                                indulgent journey. Whether you're seeking adventure, relaxation, or cultural immersion,
                                our expert team ensures that each trip is tailored to your unique preferences, 
                                allowing you to focus on creating memories that will last a lifetime. 
                                With us, you're not just traveling—you're embarking on an extraordinary adventure.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="flaticon-paragliding"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Activities</h3>
                                <p>Immerse yourself with various activities that are available to you right from day one!
                                Allow yourself the opportunity to immerse yourself within a new adventure filled with culture, excitement, & life-long remembering moments. 
                                Whether that’s sightseeing, shopping, beach going, night life… Vive tu aventura!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="flaticon-tour-guide"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Transportation Services</h3>
                                <p>Feel confident in the measures we take to provide you with safety and convenience when it comes to exploring your chosen destination! 
                                With a simple request, enjoy access to beautiful beaches, restaurants, and much more</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New row for the image -->
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center">
                <div style="
                        background-image: url({{ asset('assets/images/zoni_beach.png') }}); 
                        background-size: cover; 
                        background-repeat: no-repeat; 
                        background-position: center; 
                        width: 100%; 
                        max-width: 800px; 
                        height: 300px;">
                </div>
            </div>
        </div>
    </div>
</section>


<section class="ftco-counter img" id="section-counter">
    <div class="container mb-5">
        <div class="row d-flex">
            <div class="col-md-6 d-flex">
                <div class="img d-flex align-self-stretch"
                    style="background-image:url({{ asset('assets/images/MapChart_Map.png') }}); background-size: contain; background-repeat: no-repeat; background-position: center; width: 100%;"></div>
            </div>
            <div class="col-md-6 pl-md-5 py-5">
                <div class="row justify-content-start pb-3">
                    <div class="col-md-12 heading-section ftco-animate">
                        <h2 class="mb-4">Why Choose Us?</h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <b>Curated Travel Experiences</b> We offer a fully custom trip that focuses on exclusive lodging accommodations 
                                & private transportation
                                 in the destination you choose to explore! 
                                We ensure a seamless and memorable experience for your adventure packed destination.
                            </li>



                            <li class="list-group-item">
                                <b>Exceptional value</b>
                                Our trips offer exceptional value, providing you with the best possible experience at your dream destination, 
                                without compromising on quality
                            </li>

                            <li class="list-group-item">
                                <b>Destination Expertise: </b>
                                We bring firsthand experience and deep knowledge to curate trips that are authentic, 
                                well-planned, and tailored to your adventure.
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center mb-4">
                            <div class="text">
                                <strong class="number" data-number="{{ $totalBookings && $totalBookings }}">0</strong>
                                <span>Successful Bookings</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center mb-4">
                            <div class="text">
                                <strong class="number" data-number="{{ $totalCustomers && $totalCustomers }}">0</strong>
                                <span>Happy Customers</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center mb-4">
                            <div class="text">
                                <strong class="number" data-number="{{ $totalTrips && $totalCustomers }}">0</strong>
                                <span>Places Explored</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- <section class="ftco-section testimony-section bg-bottom" style="background-image: url(images/bg_3.jpg);">
      <div class="container">
        <div class="row justify-content-center pb-4">
          <div class="col-md-7 text-center heading-section ftco-animate">
            <h2 class="mb-4">Tourist Feedback</h2>
          </div>
        </div>
        <div class="row ftco-animate">
          <div class="col-md-12">
            <div class="carousel-testimony owl-carousel ftco-owl">
              <div class="item">
                <div class="testimony-wrap py-4">
                  <div class="text">
                    <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                    <div class="d-flex align-items-center">
                    	<div class="user-img" style="background-image: url(images/person_1.jpg)"></div>
                    	<div class="pl-3">
		                    <p class="name">Roger Scott</p>
		                    <span class="position">Marketing Manager</span>
		                  </div>
	                  </div>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4">
                  <div class="text">
                    <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                    <div class="d-flex align-items-center">
                    	<div class="user-img" style="background-image: url(images/person_2.jpg)"></div>
                    	<div class="pl-3">
		                    <p class="name">Roger Scott</p>
		                    <span class="position">Marketing Manager</span>
		                  </div>
	                  </div>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4">
                  <div class="text">
                    <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                    <div class="d-flex align-items-center">
                    	<div class="user-img" style="background-image: url(images/person_3.jpg)"></div>
                    	<div class="pl-3">
		                    <p class="name">Roger Scott</p>
		                    <span class="position">Marketing Manager</span>
		                  </div>
	                  </div>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4">
                  <div class="text">
                    <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                    <div class="d-flex align-items-center">
                    	<div class="user-img" style="background-image: url(images/person_1.jpg)"></div>
                    	<div class="pl-3">
		                    <p class="name">Roger Scott</p>
		                    <span class="position">Marketing Manager</span>
		                  </div>
	                  </div>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4">
                  <div class="text">
                    <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                    <div class="d-flex align-items-center">
                    	<div class="user-img" style="background-image: url(images/person_2.jpg)"></div>
                    	<div class="pl-3">
		                    <p class="name">Roger Scott</p>
		                    <span class="position">Marketing Manager</span>
		                  </div>
	                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> --}}

<x-travelcomponents.footer />
