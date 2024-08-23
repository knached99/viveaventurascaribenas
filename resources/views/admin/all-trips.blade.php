<x-authenticated-theme-layout>
 <div class="row">
  <div class="card-body">
    <div class="col-sm-7">        
     <div class="m-3">
                       <a class="btn btn-primary text-white w-1/2" href="{{ route('admin.create-trip') }}">
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

               <x-admincomponents.all-trips :trips="$trips"/>
               </div>
               @else
               <h3 class="text-secondary">No Available Trips. Go ahead and create one now</h3>
               @endif
               <!-- End Trips -->
                <!--/ Trips Table -->
       
              </div>
        
</x-authenticated-theme-layout>