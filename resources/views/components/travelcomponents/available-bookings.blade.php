@props(['trips', 'mostPopularTripIds'])

<section class="ftco-section ftco-no-pt">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4" style="font-weight: 900;">Available Bookings</h2>
            </div>
        </div>
        <div class="row">
            @foreach ($trips as $trip)
                <div class="col-md-4 col-sm-6 ftco-animate">
                    <div class="project-wrap">
                        <a href="{{ route('landing.destination', ['tripID' => $trip->tripID]) }}" class="img" style="background-image: url({{ $trip->tripPhoto ? asset('storage/' . $trip->tripPhoto) : asset('assets/images/image_placeholder.jpg') }});">
                            @if(in_array($trip->tripID, $mostPopularTripIds))
                                <div class="popular-badge">
                                    <img src="{{ asset('assets/theme_assets/assets/img/popularBadge.webp') }}" alt="Popular" />
                                </div>
                            @endif
                        </a>
                        <div class="text p-4">
                            <span class="price">${{ number_format($trip->tripPrice, 2) }}/person</span>
                            <span class="days">{{ \Carbon\Carbon::parse($trip->tripStartDate)->diffInDays($trip->tripEndDate) }} Days</span>
                            <h3><a href="{{ route('landing.destination', ['tripID' => $trip->tripID]) }}">{{ $trip->tripLocation }}</a></h3>
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
                            <ul>
                                <li>
                                    <img src="{{ asset('assets/images/calendar.png') }}" style="width: 20px; height: 20px; margin: 5px;" />
                                    {{ date('F jS, Y', strtotime($trip->tripStartDate)) }} - {{ date('F jS, Y', strtotime($trip->tripEndDate)) }}
                                </li>
                                @switch($trip->tripLandscape)
                                    @case('Beach')
                                        <li><img src="{{ asset('assets/images/beach.png') }}" style="width: 40px; height: 40px; margin: 5px;" /> {{ $trip->tripLandscape }}</li>
                                        @break
                                    @case('City')
                                        <li><img src="{{ asset('assets/images/buildings.png') }}" style="width: 40px; height: 40px; margin: 5px;" />{{ $trip->tripLandscape }}</li>
                                        @break
                                    @case('Country Side')
                                        <li><img src="{{ asset('assets/images/farm.png') }}" style="width: 40px; height: 40px; margin: 5px;" />{{ $trip->tripLandscape }}</li>
                                        @break
                                    @case('Mountainous')
                                        <li><img src="{{ asset('assets/images/mountain.png') }}" style="width: 40px; height: 40px; margin: 5px;" />{{ $trip->tripLandscape }}</li>
                                        @break
                                    @case('Forested')
                                        <li><img src="{{ asset('assets/images/forest.png') }}" style="width: 40px; height: 40px; margin: 5px;" />{{ $trip->tripLandscape }}</li>
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