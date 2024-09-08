<?php

use App\Livewire\Forms\TripForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.authenticated-theme')] class extends Component {
    public TripForm $form;
    use WithFileUploads;

    public function submitTripForm(): void
    {
        $this->form->submitTripForm();
    }
};
?>

<div class="container-fluid p-4">
    @if ($form->status)
        <div class="alert alert-success d-flex justify-content-between align-items-center mb-4">
            {{ $form->status }}
            <a href="{{ route('admin.all-trips') }}" class="btn btn-primary">See Trips</a>
        </div>
    @elseif($form->error)
        <div class="alert alert-danger mb-4">
            {{ $form->error }}
        </div>
    @endif

    <div class="row">
        <div class="col-12 col-xl-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form wire:submit.prevent="submitTripForm" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="tripLocation" class="form-label">Trip Location</label>
                            <input type="text" id="tripLocation" name="tripLocation"
                                class="form-control {{ $errors->has('form.tripLocation') ? 'is-invalid' : '' }}"
                                placeholder="(e.g.) San Juan, Costa Rica" wire:model="form.tripLocation" autofocus />
                            <x-input-error :messages="$errors->get('form.tripLocation')" class="invalid-feedback" />
                        </div>

                       <div class="mb-4">
                        <label for="tripPhoto" class="form-label">Trip Photos</label>
                        <input type="file" id="tripPhoto" name="tripPhoto"
                            class="form-control {{ $errors->has('form.tripPhoto') ? 'is-invalid' : '' }}"
                            wire:model="form.tripPhoto" multiple />
                        <x-input-error :messages="$errors->get('form.tripPhoto')" class="invalid-feedback" />
                        <div wire:loading wire:target="form.tripPhoto" class="mt-2">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        @if ($form->tripPhoto)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($form->tripPhoto as $photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="img-fluid rounded" style="max-width: 200px; height: 200px;" />
                            @endforeach
                        </div>
                    @endif

                    </div>


                        <div class="mb-4">
                            <label for="tripLandscape" class="form-label">Trip Landscape</label>
                            <select id="tripLandscape" name="form.tripLandscape"
                                class="form-select {{ $errors->has('form.tripLandscape') ? 'is-invalid' : '' }}"
                                wire:model="form.tripLandscape">
                                <option value="" selected disabled>Select Landscape</option>
                                <option value="Beach">Beach</option>
                                <option value="City">City</option>
                                <option value="Country Side">Country Side</option>
                                <option value="Forested">Forested</option>
                                <option value="Mountainous">Mountainous</option>
                            </select>
                            <x-input-error :messages="$errors->get('form.tripLandscape')" class="invalid-feedback" />
                        </div>

                        <div class="mb-4">
                            <label for="tripAvailability" class="form-label">Trip Availability</label>
                            <select id="tripAvailability" name="form.tripAvailability"
                                class="form-select {{ $errors->has('form.tripAvailability') ? 'is-invalid' : '' }}"
                                wire:model="form.tripAvailability">
                                <option value="" disabled selected>Select Availability</option>
                                <option value="available">Available</option>
                                <option value="coming soon">Coming Soon</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                            <x-input-error :messages="$errors->get('form.tripAvailability')" class="invalid-feedback" />
                        </div>

                        <div class="mb-4">
                            <label for="tripDescription" class="form-label">Trip Description</label>
                            <textarea id="tripDescription" name="tripDescription" placeholder="Enter description of this trip"
                                wire:model="form.tripDescription"
                                class="form-control editor {{ $errors->has('form.tripDescription') ? 'is-invalid' : '' }}" rows="4"></textarea>
                            <x-input-error :messages="$errors->get('form.tripDescription')" class="invalid-feedback" />
                        </div>

                        <div class="mb-4">
                            <label for="tripActivities" class="form-label">Trip Activities</label>
                            <textarea id="tripActivities" name="tripActivities" placeholder="Enter trip activities" wire:model="form.tripActivities"
                                class="form-control editor {{ $errors->has('form.tripActivities') ? 'is-invalid' : '' }}" rows="4"></textarea>
                            <x-input-error :messages="$errors->get('form.tripActivities')" class="invalid-feedback" />
                        </div>

                        <div class="mb-4">
                            <label for="tripStartDate" class="form-label">Trip Start Date</label>
                            <input type="date" id="tripStartDate" name="tripStartDate"
                                class="form-control {{ $errors->has('form.tripStartDate') ? 'is-invalid' : '' }}"
                                wire:model="form.tripStartDate" />
                            <x-input-error :messages="$errors->get('form.tripStartDate')" class="invalid-feedback" />
                        </div>

                        <div class="mb-4">
                            <label for="tripEndDate" class="form-label">Trip End Date</label>
                            <input type="date" id="tripEndDate" name="tripEndDate"
                                class="form-control {{ $errors->has('form.tripEndDate') ? 'is-invalid' : '' }}"
                                wire:model="form.tripEndDate" />
                            <x-input-error :messages="$errors->get('form.tripEndDate')" class="invalid-feedback" />
                        </div>

                        <div class="mb-4">
                            <label for="tripPrice" class="form-label">Trip Price (Per Person)</label>
                            <input type="text" id="tripPrice" name="tripPrice"
                                class="form-control {{ $errors->has('form.tripPrice') ? 'is-invalid' : '' }}"
                                wire:model="form.tripPrice" placeholder="$1.00" />
                            <x-input-error :messages="$errors->get('form.tripPrice')" class="invalid-feedback" />
                        </div>

                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-primary me-3" wire:loading.remove>Create
                                Trip</button>
                            <div wire:loading wire:target="submitTripForm">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
