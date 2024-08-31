<x-travelcomponents.header />
<x-travelcomponents.navbar />
<div id="booking" class="section">
    <div class="section-center">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-md-push-5">
                    <div class="booking-cta">
                        <h1>Finish booking your trip</h1>
                        <p style="font-size: 30px;">Fill out the form to complete booking your trip!
                        </p>

                        {{-- <div class="card bg-white shadow-lg rounded">
                        <h4 class="text-dark">Trip Selected</h4>
                        <ul class="list-group list-group-flush">
                        <li class="list-group-item"><img src="{{asset('storage/'.$trip->tripPhoto)}}" class="img-thumbnail"/></li>
                        <li class="list-group-item" style="font-weight: bolder;">{{$trip->tripLocation}}</li>
                        <li class="list-group-item text-truncate" style="font-weight: bolder;">{{$trip->tripDescription}}</li>
                        <li class="list-group-item">{{date('F jS, Y', strtotime($trip->tripStartDate))}} - {{date('F jS, Y', strtotime($trip->tripEndDate))}}</li>
                        <li class="list-group-item">{{$trip->tripLandscape}}</li>
                        </ul>
                        </div> --}}
                    </div>
                </div>
                <div class="col-md-4 col-md-pull-7">
                    <!-- Form Start -->
                    <livewire:forms.booking-form :tripID="$tripID" />
                    <!-- Form End -->
                </div>
            </div>
        </div>
    </div>
</div>
<x-travelcomponents.footer />
