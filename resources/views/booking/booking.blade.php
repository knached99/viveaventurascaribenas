<x-travelcomponents.header />
{{-- <x-travelcomponents.navbar /> --}}
<div id="booking" class="section">
    <div class="section-center">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-md-push-5">
                    <div class="booking-cta">
                        <h1>Finish booking your trip</h1>
                        <p style="font-size: 30px;">Fill out the form to complete booking your trip!
                        </p>
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
@livewireScripts
{{-- <x-travelcomponents.footer /> --}}
