<x-authenticated-theme-layout>
    <div class="row g-0">
        <!-- Profile Update -->
        <div class="col-12 mb-6 order-0">
            <div class="card">
                <div class="d-flex align-items-start row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <livewire:profile.update-profile-information-form />
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-6">
                            <img src="{{ asset('assets/theme_assets/assets/img/illustrations/man-with-laptop.png') }}"
                                height="175" class="scaleX-n1-rtl" alt="View Badge User" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Update -->
        <div class="col-12 mb-6 order-2">
            <div class="card">
                <div class="d-flex align-items-start row">
                    <div class="col-lg-12">
                        <div class="card-body">
                            {{-- <div class="d-flex align-items-center justify-content-between"> --}}
                            <livewire:profile.update-password-form />
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stripe Sales Tax S4ettings -->
        {{-- Uncomment if needed --}}
        {{-- <div class="col-12 mb-6 order-2">
            <div class="card">
                <div class="d-flex align-items-start row">
                    <div class="col-lg-12">
                        <div class="card-body">
                            <!-- Configure Sales Tax Origin -->
                            <livewire:profile.update-tax-settings />
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>
</x-authenticated-theme-layout>
