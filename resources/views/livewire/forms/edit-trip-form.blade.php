@php
    use Carbon\Carbon;

    $startDate = Carbon::parse($trip->tripStartDate)->format('Y-m-d');
    $endDate = Carbon::parse($trip->tripEndDate)->format('Y-m-d');
@endphp

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <!-- Card Header -->
                <div
                    class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center rounded-top">
                    <h3 class="mb-0 fw-bold">Trip Information for {{ $trip->tripLocation }}</h3>
                </div>

                <form wire:submit.prevent="editTrip" class="p-4" enctype="multipart/form-data">
                    <!-- Editable Image -->
                    <div class="text-center mb-4">
                        <label for="tripPhoto" class="form-label fw-semibold d-block">
                            <img src="{{ asset('storage/' . $trip->tripPhoto) }}"
                                class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer hover:opacity-50 transition-opacity duration-300"
                                style="max-width: 300px; height: auto;" alt="{{ $trip->tripLocation }}" />

                        </label>
                        <input type="file" wire:model="tripPhoto" id="tripPhoto"
                            class="form-control-file d-none {{ $errors->has('tripPhoto') ? 'border-danger' : '' }}" />
                        <x-input-error :messages="$errors->get('tripPhoto')" class="mt-2" />
                    </div>

                    <!-- Editable Trip Description -->
                    <div class="mb-3">
                        <label for="tripLocation" class="form-label fw-semibold">Location:</label>
                        <input type="text" wire:model="tripLocation" id="tripLocation"
                            class="form-control rounded-3 p-2 {{ $errors->has('tripLocation') ? 'border-danger' : '' }}"
                            placeholder="Trip Location" value="{{ $trip->tripLocation }}" />
                        <x-input-error :messages="$errors->get('tripLocation')" class="mt-2" />
                    </div>

                    <div class="mb-3">
                        <label for="tripDescription" class="form-label fw-semibold">Trip Description:</label>
                        <textarea wire:model="tripDescription" id="tripDescription"
                            class="form-control rounded-3 {{ $errors->has('tripDescription') ? 'border-danger' : '' }}" rows="5"
                            placeholder="Enter trip description...">{{ $trip->tripDescription }}</textarea>
                        <x-input-error :messages="$errors->get('tripDescription')" class="mt-2" />
                    </div>

                    <div class="mb-3">
                        <label for="tripActivities" class="form-label fw-semibold">Trip Activities:</label>
                        <textarea wire:model="tripActivities" id="tripActivities"
                            class="form-control rounded-3 {{ $errors->has('tripActivities') ? 'border-danger' : '' }}" rows="5"
                            placeholder="Enter trip activities...">{{ $trip->tripActivities }}</textarea>
                        <x-input-error :messages="$errors->get('tripActivities')" class="mt-2" />
                    </div>

                    <div class="row">
                        <!-- Landscape -->
                        <div class="col-md-6 mb-3">
                            <label for="tripLandscape" class="form-label fw-semibold">Landscape:</label>
                            <select wire:model="tripLandscape" id="tripLandscape"
                                class="form-select rounded-3 {{ $errors->has('tripLandscape') ? 'border-danger' : '' }}">
                                <option disabled selected value="{{ $trip->tripLandscape }}">
                                    {{ $trip->tripLandscape }}</option>
                                <option value="Beach">Beach</option>
                                <option value="City">City</option>
                                <option value="Country Side">Country Side</option>
                                <option value="Forested">Forested</option>
                                <option value="Mountainous">Mountainous</option>
                            </select>
                            <x-input-error :messages="$errors->get('tripLandscape')" class="mt-2" />
                        </div>

                        <!-- Trip Availability -->
                        <div class="col-md-6 mb-3">
                            <label for="tripAvailability" class="form-label fw-semibold">Availability:</label>
                            <select wire:model="tripAvailability" id="tripAvailability"
                                class="form-select rounded-3 {{ $errors->has('tripAvailability') ? 'border-danger' : '' }}">
                                <option disabled selected value="{{ $trip->tripAvailability }}">
                                    {{ $trip->tripAvailability }}</option>
                                <option value="available">Available</option>
                                <option value="coming soon">Coming Soon</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                            <x-input-error :messages="$errors->get('tripAvailability')" class="mt-2" />
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-6 mb-3">
                            <label for="tripStartDate" class="form-label fw-semibold">Start Date:</label>
                            <input type="date" wire:model="tripStartDate" id="tripStartDate"
                                class="form-control rounded-3 {{ $errors->has('tripStartDate') ? 'border-danger' : '' }}"
                                value="{{ $startDate }}" />
                            <x-input-error :messages="$errors->get('tripStartDate')" class="mt-2" />
                        </div>

                        <!-- End Date -->
                        <div class="col-md-6 mb-3">
                            <label for="tripEndDate" class="form-label fw-semibold">End Date:</label>
                            <input type="date" wire:model="tripEndDate" id="tripEndDate"
                                class="form-control rounded-3 {{ $errors->has('tripEndDate') ? 'border-danger' : '' }}"
                                value="{{ $endDate }}" />
                            <x-input-error :messages="$errors->get('tripEndDate')" class="mt-2" />
                        </div>

                        <!-- Price -->
                        <div class="col-md-6 mb-3">
                            <label for="tripPrice" class="form-label fw-semibold">Price:</label>
                            <input type="text" wire:model="tripPrice" id="tripPrice"
                                class="form-control rounded-3 {{ $errors->has('tripPrice') ? 'border-danger' : '' }}"
                                value="{{ $trip->tripPrice }}" />
                            <x-input-error :messages="$errors->get('tripPrice')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="text-end m-3">
                        <button type="submit" class="btn btn-primary mt-3 px-4 py-2 rounded-pill"
                            wire:loading.remove>Save Changes</button>

                        <div class="spinner-border text-primary" role="status" wire:loading>
                            <span class="visually-hidden">Loading...</span>
                        </div>

                        @if ($success)
                            <div class="mb-4 text-success">
                                {{ $success }}
                            </div>
                        @elseif($error)
                            <div class="mb-4 text-danger">
                                {{ $error }}
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
