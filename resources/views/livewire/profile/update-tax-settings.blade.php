@php
    $street_address_1 = $stripeTaxSettings['head_office']['address']['line1'] ?? '';
    $street_address_2 = $stripeTaxSettings['head_office']['address']['line2'] ?? '';
    $city = $stripeTaxSettings['head_office']['address']['city'] ?? '';
    $zip_code = $stripeTaxSettings['head_office']['address']['postal_code'] ?? '';
    $state = $stripeTaxSettings['head_office']['address']['state'] ?? '';

@endphp


<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Your Tax Settings') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('This information is required in order for Stripe to automatically apply tax rates to customer transactions') }}
        </p>
    </header>

    <form wire:submit="updateStripTaxSettings" class="mt-6 space-y-6">
        <div>
            <x-input-label for="line1" :value="__('Business Street Address')" />
            <x-text-input wire:model="line1" id="line1" name="line1" type="text"
                class="mt-1 block w-full {{ $errors->has('line1') ? 'border border-danger' : '' }}" />
            <x-input-error :messages="$errors->get('line1')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="line2" :value="__('Business Suite / P.O. Box')" />
            <x-text-input wire:model="line2" id="line2" name="line2" type="text"
                class="mt-1 block w-full {{ $errors->has('line2') ? 'border border-danger' : '' }}" />
            <x-input-error :messages="$errors->get('line2')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="city" :value="__('City')" />
            <x-text-input wire:model="city" id="city" name="city" type="text"
                class="mt-1 block w-full {{ $errors->has('city') ? 'border border-danger' : '' }}" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>


        <div>
            <x-input-label for="state" :value="__('State')" />
            <select id="state" wire:model="state" name="state"
                class="mt-1 block w-full {{ $errors->has('state') ? 'border border-danger' : '' }}">
                <option value={{ $state }}>Select State</option> <!-- Default option -->
                @foreach ($states as $stateOption)
                    <!-- Avoid overwriting $state -->
                    <option value="{{ $stateOption['code'] }}" {{ $stateOption['code'] == $state ? 'selected' : '' }}>
                        {{ $stateOption['name'] }}
                    </option>
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('state')" class="mt-2" />

        </div>

        <div>
            <x-input-label for="postal_code" :value="__('Zip Code')" />
            <x-text-input wire:model="postal_code" id="postal_code" name="postal_code" type="text"
                class="mt-1 block w-full {{ $errors->has('postal_code') ? 'border border-danger' : '' }}" />
            <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if ($error)
                <span class="block sm:inline text-red-500">{{ $error }}</span>
            @elseif($success)
                <span class="block sm:inline text-emerald-500">{{ $success }}</span>
            @endif

        </div>
    </form>
</section>
