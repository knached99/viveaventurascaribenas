<x-travelcomponents.header />

<x-travelcomponents.navbar />

<section class="hero-wrap hero-wrap-2 js-fullheight"
    style="background-image: url('{{ asset('assets/images/tourists.jpg') }}');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <h1 class="mb-3 bread" style="font-weight: 900;">Contact Us</h1>
                <p style="font-weight: 500; font-size: 18px;">Contact us with any questions you might have. We will
                    respond back to you within
                    24-48 hours</p>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section ftco-no-pb contact-section">
    <div class="container">
        <div class="row d-flex contact-info">
            <div class="col-md-3 d-flex">
                <div class="align-self-stretch box p-4 text-center">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <span class="icon-map-signs"></span>
                    </div>
                    <h3 class="mb-2">Address</h3>
                    <p>198 West 21th Street, Suite 721 New York NY 10016</p>
                </div>
            </div>
            <div class="col-md-3 d-flex">
                <div class="align-self-stretch box p-4 text-center">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <span class="icon-phone2"></span>
                    </div>
                    <h3 class="mb-2">Contact Number</h3>
                    <p><a href="tel://1234567920">+ 1235 2355 98</a></p>
                </div>
            </div>
            <div class="col-md-3 d-flex">
                <div class="align-self-stretch box p-4 text-center">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <span class="icon-paper-plane"></span>
                    </div>
                    <h3 class="mb-2">Email Address</h3>
                    <p><a href="mailto:info@yoursite.com">info@yoursite.com</a></p>
                </div>
            </div>
            <div class="col-md-3 d-flex">
                <div class="align-self-stretch box p-4 text-center">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <span class="icon-globe"></span>
                    </div>
                    <h3 class="mb-2">Website</h3>
                    <p><a href="#">yoursite.com</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section contact-section">
    <div class="container">
        <div class="row block-9">
            <div class="col-md-6 order-md-last d-flex">
                <!-- Form Goes Here -->
                <livewire:pages.contact-us />
            </div>

            <div class="col-md-6 d-flex">
                {{-- <div id="map" class="bg-white"></div> --}}
                <x-travelcomponents.google-map-embed />

            </div>
        </div>
    </div>
</section>

<x-travelcomponents.footer />