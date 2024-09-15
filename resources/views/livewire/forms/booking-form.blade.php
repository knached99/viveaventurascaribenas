<div class="booking-form">
    <form wire:submit.prevent="bookTrip" class="p-4 rounded bg-white">
        <!-- Step 1 -->
        @if ($currentStep === 1)
            <div class="step">
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input name="name" id="name" autofocus wire:model="name" class="form-control" type="text"
                        placeholder="First name & last name"
                        style="border-color: {{ $errors->has('name') ? '#dc2626' : '#4f46e5' }};">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input name="email" id="email" wire:model="email" class="form-control" type="email"
                        placeholder="Email" style="border-color: {{ $errors->has('email') ? '#dc2626' : '#4f46e5' }};">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input id="phone_number" name="phone_number" wire:model="phone_number" class="form-control"
                        type="text" placeholder="xxx-xxx-xxxx"
                        style="border-color: {{ $errors->has('phone_number') ? '#dc2626' : '#4f46e5' }};">
                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-danger" />
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="next__button" style="border-radius: 0;"
                        wire:click="nextStep">Next</button>
                </div>
            </div>
        @endif

        <!-- Step 2 -->
        @if ($currentStep === 2)
            <div class="step">
                <div class="form-group mb-3">
                    <label for="address_line_1" class="form-label">Street Address</label>
                    <input id="address_line_1" name="address_line_1" wire:model="address_line_1" class="form-control"
                        type="text" placeholder="Street Address"
                        style="border-color: {{ $errors->has('address_line_1') ? '#dc2626' : '#4f46e5' }};">
                    <x-input-error :messages="$errors->get('address_line_1')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <label for="address_line_2" class="form-label">Street Address 2</label>
                    <input id="address_line_2" name="address_line_2" wire:model="address_line_2" class="form-control"
                        type="text" placeholder="Street Address 2"
                        style="border-color: {{ $errors->has('address_line_2') ? '#dc2626' : '#4f46e5' }};">
                    <x-input-error :messages="$errors->get('address_line_2')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <label for="city" class="form-label">City</label>
                    <input id="city" name="city" wire:model="city" class="form-control" type="text"
                        placeholder="City" style="border-color: {{ $errors->has('city') ? '#dc2626' : '#4f46e5' }};">
                    <x-input-error :messages="$errors->get('city')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <label for="state" class="form-label">State</label>
                    <select name="state" id="state" wire:model="state" class="form-control"
                        style="border-color: {{ $errors->has('state') ? '#dc2626' : '#4f46e5' }};">
                        <option value="" disabled>Select a State</option>
                        @foreach ($states as $state)
                            <option value="{{ $state['code'] }}">{{ $state['name'] }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('state')" class="mt-2 text-danger" />
                </div>

                <div class="form-group mb-3">
                    <label for="zipcode" class="form-label">Zipcode</label>
                    <input id="zipcode" name="zipcode" wire:model="zipcode" class="form-control" type="text"
                        placeholder="Zipcode"
                        style="border-color: {{ $errors->has('zipcode') ? '#dc2626' : '#4f46e5' }};">
                    <x-input-error :messages="$errors->get('zipcode')" class="mt-2 text-danger" />
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="previous__button" wire:click="previousStep">Previous</button>
                    <button type="button" class="next__button" wire:click="nextStep">Next</button>
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
                    <button type="button" class="previous__button" wire:click="previousStep">Previous</button>
                    <button class="btn btn-success" wire:loading.remove type="submit">Book</button>
                </div>

                <div class="spinner-border text-primary mt-3" role="status" wire:loading></div>
            </div>
        @endif
    </form>
</div>
