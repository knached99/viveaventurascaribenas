  <x-theme-layout>
  <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <x-admincomponents.sidebar/>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

           <livewire:layout.nav />


          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-xxl-8 mb-6 order-0">
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
              
                    </div>
                  </div>
                </div>
            
               <!-- TRIPS -->
               @if(!$trips->isEmpty())
               <div class="card shadow-sm bg-white rounded">
                <h5 class="m-3">Displays the first 5 available bookings.</h5>

               <x-admincomponents.all-trips :trips="$trips"/>
               </div>
               @endif 
               <!-- End Trips -->
       
              </div>
        
            </div>
            <!-- / Content -->

      

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

 
  </x-theme-layout>