<x-travelcomponents.header />

<x-travelcomponents.navbar />

<section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url({{ asset('assets/images/playa_del_carmen.avif') }});"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <h1 class="mb-3 bread" style="font-weight:900;">Places to Travel</h1>
                <p class="breadcrumbs"><span class="mr-2"><a href="/">Home <i
                                class="ion-ios-arrow-forward"></i></a></span> <span>Destinations <i
                            class="ion-ios-arrow-forward"></i></span></p>
            </div>
        </div>
    </div>
</section>



@if (!empty($trips) || !empty($mostPopularTripId))
    <x-travelcomponents.available-bookings :trips="$trips" :mostPopularTripId="$mostPopularTripId" />
@else
@endif
<x-travelcomponents.footer />
