<div class="booking-form">
    <form wire:submit.prevent="bookTrip" class="p-4 rounded bg-white">
        <!-- Step 1 -->
        @if ($currentStep === 1)
            <div class="step">
                <div class="form-group mb-3">
                    <input name="name" id="name" autofocus wire:model="name" class="form-control" type="text"
                        placeholder="First name & last name"
                        style="{{ $errors->has('name') ? 'border:1px solid #dc2626' : '' }}" />
                    {{-- style="border-color: {{ $errors->has('name') ? '#dc2626' : '#4f46e5' }};"> --}}
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <input name="email" id="email" wire:model="email" class="form-control" type="email"
                        placeholder="Email" style="{{ $errors->has('email') ? 'border:1px solid #dc2626' : '' }}" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <input id="phone_number" name="phone_number" wire:model="phone_number" class="form-control"
                        type="text" placeholder="xxx-xxx-xxxx"
                        style="{{ $errors->has('phone_number') ? 'border:1px solid #dc2626' : '' }}" />

                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-danger" />
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="next-btn" style="border-radius: 0;"
                        wire:click="nextStep">Next</button>
                </div>
            </div>
        @endif

        <!-- Step 2 -->
        @if ($currentStep === 2)
            <div class="step">
                <div class="form-group mb-3">
                    <input id="address_line_1" name="address_line_1" wire:model="address_line_1" class="form-control"
                        type="text" placeholder="Street Address"
                        style="{{ $errors->has('address_line_1') ? 'border:1px solid #dc2626' : '' }}" />

                    <x-input-error :messages="$errors->get('address_line_1')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <input id="address_line_2" name="address_line_2" wire:model="address_line_2" class="form-control"
                        type="text" placeholder="Suite / P.O. Box"
                        style="{{ $errors->has('address_line_2') ? 'border:1px solid #dc2626' : '' }}" />

                    <x-input-error :messages="$errors->get('address_line_2')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <input id="city" name="city" wire:model="city" class="form-control" type="text"
                        placeholder="City" style="{{ $errors->has('city') ? 'border:1px solid #dc2626' : '' }}" />

                    <x-input-error :messages="$errors->get('city')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <select name="state" id="state" wire:model="state" class="form-control"
                        style="{{ $errors->has('state') ? 'border:1px solid #dc2626' : '' }}" />

                    <option value="" disabled>State</option>
                    @foreach ($states as $state)
                        <option value="{{ $state['code'] }}">{{ $state['name'] }}</option>
                    @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('state')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <input id="zipcode" name="zipcode" wire:model="zipcode" class="form-control" type="text"
                        placeholder="Zipcode" style="{{ $errors->has('zipcode') ? 'border:1px solid #dc2626' : '' }}" />

                    <x-input-error :messages="$errors->get('zipcode')" class="mt-2 text-danger" />
                </div>

                <span class="fw-bold mt-3">Choose how you'd like to pay for this booking</span>

                <div class="form-check form-check-inline mb-3 mt-3">
                    <input class="form-check-input" wire:model="payment_option" type="radio" name="payment_option"
                        id="payment_option" value="installments">
                    <label class="form-check-label" for="payment_option">Installments</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment_option" id="payment_option"
                        value="pay_in_full">
                    <label class="form-check-label" for="payment_option">Pay In Full</label>
                </div>
                <x-input-error :messages="$errors->get('payment_option')" class="mt-2 text-danger" />


                <div class="d-flex justify-content-between">
                    <button type="button" class="previous-btn" wire:click="previousStep">Previous</button>
                    <button type="button" class="next-btn" wire:click="nextStep">Next</button>
                </div>
            </div>
        @endif

        <!-- Final Step -->
        @if ($currentStep === 3)
            @if ($error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endif
            <div class="step text-center">
                <div class="form-btn">
                    <button type="button" class="previous-btn" wire:click="previousStep">Previous</button>
                    <button class="submit-btn" wire:loading.remove type="submit">Book</button>
                </div>

                <div class="spinner-border text-primary mt-3" role="status" wire:loading></div>
            </div>
        @endif
    </form>
</div>
