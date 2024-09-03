<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4" style="font-weight: 900;">Most Popular Attractions</h2>
            </div>
        </div>
        <div class="row">
            @foreach($popularTrips as $trip)
                <div class="col-md-3 ftco-animate">
                    <div class="project-destination">
                        <a href="{{ url('/landing/destination/' . $trip['id']) }}" class="img" style="background-image: url({{ asset('storage/' . $trip['image']) }});">
                            <div class="text">
                                <h3 style="font-weight: 900; color: #f8fafc;">{{ $trip['name'] }}</h3>
                                <span>{{ $trip['count'] }} bookings</span>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
