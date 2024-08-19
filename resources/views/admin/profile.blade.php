  <x-authenticated-theme-layout>
 <div class="row">
                <div class="col-xxl-8 mb-6 order-0">
                  <div class="card">
                    <div class="d-flex align-items-start row">
                      <div class="col-sm-7">
                        <div class="card-body">
                         
                          <livewire:profile.update-profile-information-form/>

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
                </div>
            
                <!-- Password Update -->
                <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
                  <div class="card">
                    <div class="row row-bordered g-0">
                      <div class="col-lg-8">
                        <div class="card-header d-flex align-items-center justify-content-between">
                          
                        <livewire:profile.update-password-form />
                        </div>
                      </div>
                   
                    </div>
                  </div>
                </div>
                <!--/ Password Update -->
       
              </div>
        
  </x-authenticated-theme-layout>