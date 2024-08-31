<x-authenticated-theme-layout>
    <div class="row">
        <div class="card-body">
            <div class="col-sm-7">        
                <div class="m-3">
                    <a class="btn btn-primary text-white w-100 w-sm-50" href="{{ route('admin.create-trip') }}">
                        Create Trip
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Trips Table -->
    @if(!$trips->isEmpty())
        <div class="card shadow-sm bg-white rounded">
            <h5 class="m-3">Here are all of your bookings</h5>

            @if(session('trip_deleted'))
                <div class="alert alert-success" role="alert">
                    {{session('trip_deleted')}}
                </div>
            @endif 

            <div class="table-responsive">
                <x-admincomponents.all-trips :trips="$trips"/>
            </div>
        </div>
    @else
        <h3 class="text-secondary">No Available Trips. Go ahead and create one now</h3>
    @endif
    <!-- End Trips Table -->
</x-authenticated-theme-layout>
