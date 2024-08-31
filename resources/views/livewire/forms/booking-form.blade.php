<div class="booking-form">
    <form wire:submit.prevent="bookTrip">
        <!-- Step 1 -->
        @if ($currentStep === 1)
            <div class="step">
                <div class="m-3">
                    <span class="form-label">Your Name</span>
                    <input name="name" id="name" autofocus wire:model="name" class="form-control" type="text"
                        placeholder="First name & last name"
                        style="border: 1px solid {{ $errors->has('name') ? '#dc2626;' : '#4f46e5;' }};">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                </div>

                <div class="m-3">
                    <span class="form-label">Email</span>
                    <input name="email" id="email" wire:model="email" class="form-control" type="text"
                        placeholder="Email"
                        style="border: 1px solid {{ $errors->has('email') ? '#dc2626;' : '#4f46e5;' }}">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>

                <div class="m-3">
                    <span class="form-label">Phone Number</span>
                    <input id="phone_number" name="phone_number" wire:model="phone_number" class="form-control"
                        type="text" placeholder="xxx-xxx-xxxx"
                        style="border: 1px solid {{ $errors->has('phone_number') ? '#dc2626;' : '#4f46e5;' }}">
                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-danger" />
                </div>

                <div class="form-btn">
                    <button type="button" class="btn btn-primary" wire:click="nextStep"
                        :disabled="!$this - > isValidStep(1)">Next</button>
                </div>
            </div>
        @endif

        <!-- Step 2 -->
        @if ($currentStep === 2)
            <div class="step">
                <div class="m-3">
                    <span class="form-label">Street Address</span>
                    <input id="address_line_1" name="address_line_1" wire:model="address_line_1" class="form-control"
                        type="text" placeholder="Street Address"
                        style="border: 1px solid {{ $errors->has('address_line_1') ? '#dc2626;' : '#4f46e5;' }}">
                    <x-input-error :messages="$errors->get('address_line_1')" class="mt-2 text-danger" />
                </div>

                <div class="m-3">
                    <span class="form-label">Street Address 2</span>
                    <input id="address_line_2" name="address_line_2" wire:model="address_line_2" class="form-control"
                        type="text" placeholder="Street Address 2"
                        style="border: 1px solid {{ $errors->has('address_line_2') ? '#dc2626;' : '#4f46e5;' }}">
                    <x-input-error :messages="$errors->get('address_line_2')" class="mt-2 text-danger" />
                </div>

                <div class="m-3">
                    <span class="form-label">City</span>
                    <input id="city" name="city" wire:model="city" class="form-control" type="text"
                        placeholder="City"
                        style="border: 1px solid {{ $errors->has('city') ? '#dc2626;' : '#4f46e5;' }}">
                    <x-input-error :messages="$errors->get('city')" class="mt-2 text-danger" />
                </div>

                <div class="m-3">
                    <span class="form-label">State</span>
                    <select name="state" id="state" wire:model="state" class="form-control"
                        style="border: 1px solid {{ $errors->has('state') ? '#dc2626;' : '#4f46e5;' }}">
                        <option value="" disabled>Select a State</option>
                        @foreach ($states as $state)
                            <option value="{{ $state['code'] }}">{{ $state['name'] }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('state')" class="mt-2 text-danger" />
                </div>

                <div class="m-3">
                    <span class="form-label">Zipcode</span>
                    <input id="zipcode" name="zipcode" wire:model="zipcode" class="form-control" type="text"
                        placeholder="Zipcode"
                        style="border: 1px solid {{ $errors->has('zipcode') ? '#dc2626;' : '#4f46e5;' }}">
                    <x-input-error :messages="$errors->get('zipcode')" class="mt-2 text-danger" />
                </div>

                <div class="form-btn">
                    <button type="button" class="btn btn-primary" wire:click="previousStep">Previous</button>
                    <button type="button" class="btn btn-primary" wire:click="nextStep"
                        :disabled="!$this - > isValidStep(2)">Next</button>
                </div>
            </div>
        @endif

        <!-- Final Step -->
        @if ($currentStep === 3)
            <div class="step">
                <div class="form-btn">
                    <button class="submit-btn" wire:loading.remove type="submit">Book</button>
                </div>

                <div class="spinner-border text-primary" role="status" wire:loading>
                </div>
            </div>
        @endif
    </form>
</div>
