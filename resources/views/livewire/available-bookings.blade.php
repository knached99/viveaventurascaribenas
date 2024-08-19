@php
    use Carbon\Carbon;
@endphp

<section class="ftco-section ftco-no-pt">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4">Available Bookings</h2>
            </div>
        </div>
        <div class="row">
            @foreach($trips as $trip)
              
                <div class="col-md-4 ftco-animate">
                    <div class="project-wrap">

                        <a href="#" class="img"
                            style="background-image: url({{$trip->tripPhoto ? asset('storage/'.$trip->tripPhoto)  : asset('assets/images/image_placeholder.jpg') }});">
                            </a>
                        <div class="text p-4">
                            <span class="price">${{ number_format($trip->tripPrice, 2) }}/person</span>
                            <span class="days"> Days Tour</span>
                            <h3><a href="#">{{ $trip->tripLocation }}</a></h3>
                            <p class="location"><span class="ion-ios-map"></span> {{ $trip->tripLocation }}</p>
                            <ul>
                            <li><img src="{{asset('assets/images/calendar.png')}}" style="width: 20px; height: 20px; margin: 5px;"/> 
                            {{date('F jS, Y', strtotime($trip->tripStartDate))}} - {{date('F jS, Y', strtotime($trip->tripEndDate))}} 
                            </li>

                                @switch($trip->tripLandscape)
                                    @case('Beach')
                                        <li><img src="{{asset('assets/images/beach.png')}}" style="width: 40px; height: 40px; margin: 5px;"/> {{ $trip->tripLandscape }}</li>
                                        @break
                                    @case('City')
                                        <li><img src="{{asset('assets/images/buildings.png')}}" style="width: 40px; height: 40px; margin: 5px;" />{{$trip->tripLandscape}}</li>
                                        @break 
                                    @case('Country Side')
                                     <li><img src="{{asset('assets/images/farm.png')}}" style="width: 40px; height: 40px; margin: 5px;" />{{$trip->tripLandscape}}</li>

                                        @break  
                                    @case('Mountainous')
                                     <li><img src="{{asset('assets/images/mountain.png')}}" style="width: 40px; height: 40px; margin: 5px;" />{{$trip->tripLandscape}}</li>

                                        @break 
                                    @case('Forested')
                                      <li><img src="{{asset('assets/images/forest.png')}}" style="width: 40px; height: 40px; margin: 5px;" />{{$trip->tripLandscape}}</li>

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
