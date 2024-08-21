<x-authenticated-theme-layout>
 <div class="row">
                {{-- <div class="col-xxl-8 mb-6 order-0">
                  <div class="card">
                    <div class="d-flex align-items-start row">
                      <div class="col-sm-7">
                        <div class="card-body">
                         
                           <div class="m-3">
                          <a class="btn btn-primary text-white w-full" href="{{route('admin.create-trip')}}">
                          Create Trip 
                          </a>
                          </div>

                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-6">
                          <img
                            src="{{asset('assets/theme_assets/assets/img/illustrations/man-with-laptop.png')}}"
                            height="175"
                            class="scaleX-n1-rtl"
                            alt="View Badge User" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div> --}}
            
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