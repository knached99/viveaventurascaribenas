@php
    use Carbon\Carbon;
    use Stripe\StripeClient;

    $startDate = Carbon::parse($trip->tripStartDate)->format('Y-m-d');
    $endDate = Carbon::parse($trip->tripEndDate)->format('Y-m-d');
    $tripPhotos = json_decode($trip->tripPhoto, true);
    $acive = $trip->active;
    $couponID = $trip->stripe_coupon_id;

    $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

    $tripPrice = $trip->tripPrice;
    $newPrice = 0;

    if (!empty($trip->stripe_coupon_id)) {
        try {
            $coupon = $stripe->coupons->retrieve($couponID);
            if (isset($coupon) && $coupon->percent_off) {
                $discount = ($coupon->percent_off / 100) * $tripPrice;
                $newPrice = $tripPrice - $discount;
            }

            if (isset($coupon) && $coupon->amount_off) {
                $newPrice = $tripPrice - $coupon->amount_off;
            }
        } catch (\Exception $e) {
            \Log::error('Unable to retrieve coupon: ' . $e->getMessage());
        }
    } else {
        \Log::warning('No coupon ID found for trip: ' . $trip->tripID);
    }

@endphp

<div class="container mt-5">
    <div class="row justify-content-center">


        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <!-- Card Header -->
                <div
                    class="card-header bg-slate-200 text-white d-flex justify-content-between align-items-center rounded-top">
                    <h3 class="mb-0">Trip Information for {{ $trip->tripLocation }}</h3>
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif


                <form wire:submit.prevent="editTrip" class="p-4" enctype="multipart/form-data">
                    {{-- 
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}
                    <!-- Editable Images -->
                    <div class="text-center mb-4">
                        <label for="tripPhotos" class="form-label fw-semibold d-block mb-2">Trip Photos</label>
                        <div class="d-flex flex-wrap justify-content-center">
                            @if ($tripPhotos && count($tripPhotos) > 0)
                                @foreach ($tripPhotos as $index => $photo)
                                    <div class="position-relative m-2">
                                        @if (is_string($photo))
                                            <img src="{{ $photo }}"
                                                class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer"
                                                style="max-width: 150px; height: 150px;" alt="Trip Image"
                                                wire:click="selectImageToReplace({{ $index }})" />
                                        @elseif($photo instanceof \Livewire\TemporaryUploadedFile)
                                            <img src="{{ $photo->temporaryUrl() }}"
                                                class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer"
                                                style="max-width: 150px; height: 150px;" alt="Trip Image"
                                                wire:click="selectImageToReplace({{ $index }})" />
                                        @endif

                                        <button type="button" wire:click="removeImage({{ $index }})"
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-1 me-1">
                                            <i class='bx bx-trash-alt'></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <p>No images available.</p>
                            @endif
                        </div>

                        @if (!is_null($replaceIndex))
                            <div class="mb-3">
                                <input type="file" wire:model="tripPhotos.{{ $replaceIndex }}"
                                    class="form-control" />
                                @error('tripPhotos.' . $replaceIndex)
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div wire:loading wire:target="tripPhotos.{{ $replaceIndex }}">
                                    <span>Uploading...</span>
                                </div>
                            </div>

                            <button type="button" wire:loading.attr="disabled"
                                wire:target="tripPhotos.{{ $replaceIndex }}"
                                wire:click="replaceImage({{ $replaceIndex }})" class="btn btn-primary">
                                Replace Image
                            </button>

                            @if ($imageReplaceSuccess)
                                <div class="alert alert-success">
                                    {{ $imageReplaceSuccess }}
                                </div>
                            @endif

                            @if ($imageReplaceError)
                                <div class="alert alert-danger">
                                    {{ $imageReplaceError }}
                                </div>
                            @endif
                        @else
                            <div class="mb-3">
                                <input type="file" wire:model="tripPhotos"
                                    class="form-control  {{ $errors->has('tripPhotos.*') ? 'is-invalid' : '' }}"
                                    multiple />
                                {{-- <x-input-error :messages="$errors->get('tripPhotos.*')" class="invalid-feedback" /> --}}
                                <x-input-error :messages="$errors->get('tripPhotos')" class="invalid-feedback" />
                            </div>
                        @endif

                    </div>

                    <!-- Form Fields -->
                    <!-- Location -->
                    <div class="mb-3">
                        <label for="tripLocation" class="form-label">Trip Location</label>
                        <input type="text" id="tripLocation" wire:model="tripLocation"
                            class="form-control {{ $errors->has('tripLocation') ? 'is-invalid' : '' }}" />
                        <x-input-error :messages="$errors->get('tripLocation')" class="invalid-feedback" />
                    </div>

                    <!-- Description -->
                    <div class="mb-3" wire:ignore>
                        <label for="tripDescription" class="form-label">Trip Description</label>
                        <textarea id="tripDescription" name="tripDescription" wire:model="tripDescription"
                            class="form-control ckeditor {{ $errors->has('tripDescription') ? 'is-invalid' : '' }}" rows="4">{{ $this->tripDescription }}</textarea>
                        <x-input-error :messages="$errors->get('tripDescription')" class="invalid-feedback" />
                    </div>

                    <!-- Activities -->
                    <div class="mb-3" wire:ignore>
                        <label for="tripActivities" class="form-label">Trip Activities</label>
                        <textarea id="tripActivities" name="tripActivities" wire:model="tripActivities"
                            class="form-control ckeditor {{ $errors->has('tripActivities') ? 'is-invalid' : '' }}" rows="4">{{ $this->tripActivities }}</textarea>
                        <x-input-error :messages="$errors->get('tripActivities')" class="invalid-feedback" />
                    </div>

                    <!-- Dates -->
                    <div class="mb-3">
                        <label for="tripStartDate" class="form-label">Trip Start Date</label>
                        <input type="date" id="tripStartDate" wire:model="tripStartDate"
                            class="form-control {{ $errors->has('tripStartDate') ? 'is-invalid' : '' }}" />
                        <x-input-error :messages="$errors->get('tripStartDate')" class="invalid-feedback" />
                    </div>

                    <div class="mb-3">
                        <label for="tripEndDate" class="form-label">Trip End Date</label>
                        <input type="date" id="tripEndDate" wire:model="tripEndDate"
                            class="form-control {{ $errors->has('tripEndDate') ? 'is-invalid' : '' }}" />
                        <x-input-error :messages="$errors->get('tripEndDate')" class="invalid-feedback" />
                    </div>

                    <!-- Landscape -->
                    <div class="mb-4">
                        <label for="tripLandscape" class="form-label">Trip Landscape</label>
                        <select id="tripLandscape" wire:model="tripLandscape" multiple
                            class="form-select {{ $errors->has('tripLandscape') ? 'is-invalid' : '' }}">
                            <option value="" disabled>Select Landscape</option>
                            <option value="Beach" {{ in_array('Beach', $tripLandscape) ? 'selected' : '' }}>Beach
                            </option>
                            <option value="City" {{ in_array('City', $tripLandscape) ? 'selected' : '' }}>City
                            </option>
                            <option value="Country Side"
                                {{ in_array('Country Side', $tripLandscape) ? 'selected' : '' }}>Country Side</option>
                            <option value="Forested" {{ in_array('Forested', $tripLandscape) ? 'selected' : '' }}>
                                Forested</option>
                            <option value="Mountainous"
                                {{ in_array('Mountainous', $tripLandscape) ? 'selected' : '' }}>Mountainous</option>
                        </select>
                        <x-input-error :messages="$errors->get('tripLandscape')" class="invalid-feedback" />
                    </div>

                    <!-- Availability -->
                    <div class="mb-3">
                        <label for="tripAvailability" class="form-label">Trip Availability</label>
                        <select id="tripAvailability" wire:model="tripAvailability"
                            class="form-select {{ $errors->has('tripAvailability' ? 'is-invalid' : '') }}">
                            <option value="" disabled>Select Availability</option>
                            <option value="available">Available</option>
                            <option value="coming soon">Coming Soon</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                        <x-input-error :messages="$errors->get('tripAvailability')" class="invalid-feedback" />
                    </div>

                    <!-- Trip Price -->
                    <div class="mb-3">
                        <label for="tripPrice" class="form-label">Trip Price</label>
                        <input id="tripPrice" wire:model="tripPrice"
                            class="form-control {{ $errors->has('tripPrice') ? 'is-invalid' : '' }}"
                            placeholder="$1.00" />
                        <x-input-error :messages="$errors->get('tripPrice')" class="invalid-feedback" />
                    </div>




                    <div class="mb-4">
                        <label for="tripCosts" class="form-label">Trip Costs</label>
                        @foreach ($tripCosts as $index => $cost)
                            @php
                                $index = (int) $index; // Ensure $index is an integer
                            @endphp
                            <div class="input-group mb-2">
                                <input type="text" placeholder="Cost Name" class="form-control"
                                    wire:model="tripCosts.{{ $index }}.name" aria-label="Cost Name">

                                <input type="number" placeholder="Cost Amount" class="form-control"
                                    wire:model="tripCosts.{{ $index }}.amount" aria-label="Cost Amount">

                                <button type="button" class="btn btn-danger"
                                    wire:click="removeCost({{ $index }})">Remove</button>
                            </div>
                        @endforeach


                        <button type="button" class="btn btn-outline-success mt-4" wire:click="addCost">Add
                            Cost</button>

                        <x-input-error :messages="$errors->get('tripCosts')" class="invalid-feedback" />
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-bold">Number of available slots <span
                                class="text-indigo-500">(this number will go down as
                                bookings/reservations are made)</span></label>
                        <input type="text" placeholder="Enter number of available slots" wire:model="num_trips"
                            name="num_trips"
                            class="form-control {{ $errors->has('num_trips') ? 'is-invalid' : '' }}" />
                        <x-input-error :messages="$errors->get('num_trips')" class="invalid-feedback" />
                    </div>

                    <!-- Active or Inactive -->
                    <div class="mb-4">
                        <span
                            class="text-secondary fw-bold">{{ $active ? 'This trip is accessible publicly' : 'This trip is inactive and not accessible publicly' }}</span>

                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="active"
                                name="active" wire:model="active">
                            <label class="form-check-label"
                                for="active">{{ $active ? 'Active' : 'Inactive' }}</label>
                        </div>
                    </div>


                    <!-- Summary Section -->
                    <div class="mb-4">
                        <h4 class="mb-3">Financial Summary</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total Net Cost:</strong>
                            <span>${{ number_format($totalNetCost, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Gross Profit:</strong>
                            <span>${{ number_format($grossProfit, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Net Profit:</strong>
                            <span class="{{ $netProfit < 0 ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($netProfit, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>

                    <!-- Success and Error Messages -->
                    @if ($success)
                        <div class="alert alert-success">
                            {{ $success }}
                        </div>
                    @endif

                    @if ($error)
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @if (empty($couponID))
            <!-- Discount -->
            <div class="col">
                <div class="card shadow-sm border-0 rounded-lg m-3 p-3">
                    <h3 class="mb-0">Discount this Trip</h3>
                    <p class="text-slate-700 mt-3">If you notice sales aren't going well for this trip, you may opt to
                        provide
                        a discount to attract customers</p>
                    <form wire:submit.prevent="createDiscount">
                        <div class="form-group">
                            <label class="form-label">Select Discount Type</label>
                            <select class="form-control {{ $errors->has('discountType') ? 'is-invalid' : '' }}"
                                id="discountType" wire:model="discountType">
                                <option value="percentage">percentage</option>
                                <option value="amount">amount</option>
                            </select>
                            <x-input-error :messages="$errors->get('discountType')" class="invalid-feedback" />
                        </div>


                        <div class="form-group">
                            <label class="form-label">Discount Value</label>
                            <input type="number" wire:model="discountValue" id="discountValue"
                                class="form-control {{ $errors->has('discountValue') ? 'is-invalid' : '' }}" />
                            <x-input-error :messages="$errors->get('discountValue')" class="invalid-feedback" />
                        </div>

                        <div class="form-group">
                            <input type="hidden" wire:model="promoCode" id="promoCode" />
                            <!-- Placeholder for promo code display -->
                            <span id="promoCodeDisplay" class="inline-block m-2 text-black font-bold"></span>
                            <!-- Button for generating the promo code -->
                            <button type="button" onclick="generatePromoCode()" class="block m-2 btn btn-secondary"
                                id="promoCodeGenButton">
                                Generate Promo Code (optional)
                            </button>





                            <button wire:click="createDiscount" class="btn btn-primary ml-4 mt-2">Create
                                Discount</button>
                            @if ($discountCreateSuccess)
                                <span class="text-emerald-500">{{ $discountCreateSuccess }}</span>
                            @elseif($discountCreateError)
                                <span class="text-red-500">{{ $discountCreateError }}</span>
                            @endif
                    </form>
                </div>
            </div>
            <!-- / Discount -->
        @else
            <div class="col">
                <div class="card shadow-sm border-0 rounded-lg m-3 p-3">
                    <h5>Discount applied to this trip</h5>
                    <button type="submit" class="m-3" wire:click="removeDiscount({{ $couponID }})">Remove
                        Discount</button>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <i class='bx bx-purchase-tag'></i>
                            {{ $coupon->percent_off ? 'Percentage Off' : ($coupon->amount_off ? 'Amount Off' : '') }}
                            <span class="block text-emerald-500 font-semibold">
                                {{ $coupon->percent_off ? $coupon->percent_off . '%' : ($coupon->amount_off ? '$' . $coupon->amount_off : '') }}
                            </span>
                        </li>
                        <li class="list-group-item"><i class='bx bx-dollar-circle'></i> Price after discount:
                            ${{ number_format($newPrice, 2) }}</li>
                        <li class="list-group-item"><i class='bx bx-calendar'></i> Redeem By:
                            {{ $coupon->redeem_by ?? 'N/A' }}</li>
                        <li class="list-group-item"><i class='bx bx-calendar'></i> Duration In Months:
                            {{ $coupon->duration_in_months }}</li>
                    </ul </div>
                </div>

        @endif
    </div>
</div>

<script>
    function generatePromoCode() {
        let actionButton = document.getElementById('promoCodeGenButton');
        let promoHiddenInput = document.getElementById("promoCode");
        let promoCodeDisplay = document.getElementById('promoCodeDisplay');
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charLength = characters.length;
        const length = 8; // Fixed length of the promo code is 8 characters
        let counter = 0;

        while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charLength));
            counter += 1;
        }

        promoHiddenInput.value = result;

        actionButton.style.visibility = "hidden";

        promoCodeDisplay.textContent = 'Promo Code: ' + result;
    }
</script>
