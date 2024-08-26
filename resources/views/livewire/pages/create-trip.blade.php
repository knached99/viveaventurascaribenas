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
        // $this->validate();

        $this->form->submitTripForm();

        // Instead of redirecting, rely on Livewire's reactive properties
    }
}; ?>


@if ($form->status)
    <div class="mb-4 text-success">
        {{ $form->status }}
        <a href="{{ route('admin.all-trips') }}" class="btn btn-primary m-3 block">See Trips</a>
    </div>
@elseif($form->error)
    <div class="mb-4 text-danger">
        {{ $form->error }}
    </div>
@endif
<div class="row">

    <div class="col-xxl-8 mb-6 order-0">
        <div class="card">
            <div class="d-flex align-items-start row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <form wire:submit.prevent="submitTripForm" class="m-4" enctype="multipart/form-data">
                            <div class="mb-6">
                                <label class="form-label">Trip Location</label>
                                <input type="text"
                                    class="form-control {{ $errors->has('form.tripLocation') ? 'border border-danger' : '' }}"
                                    id="tripLocation" name="tripLocation" placeholder="(e.g.) San Juan, Costa Rica"
                                    autofocus wire:model="form.tripLocation" />

                                <x-input-error :messages="$errors->get('form.tripLocation')" class="mt-2" />
                            </div>


                            <div class="mb-6">
                                <label class="form-label">Trip Photo</label>
                                <input type="file"
                                    class="form-control {{ $errors->has('form.tripPhoto') ? 'border border-danger' : '' }}"
                                    id="tripPhoto" name="tripPhoto" autofocus wire:model="form.tripPhoto" wire:loading.remove/>

                                  

                                <x-input-error :messages="$errors->get('form.tripPhoto')" class="mt-2" />
                                  <div class="spinner-border text-primary" role="status" wire:loading wire:target.except="form.tripPhoto">
                                    <span class="visually-hidden">Loading...</span>
                                    </div>
                                @if ($form->tripPhoto)
                                    
                                    <img src="{{ $form->tripPhoto->temporaryUrl() }}" class="img-responsive m-3"
                                        style="width: 300px; height: 300px; border-radius: 15px;" />
                                @endif
                            </div>

                            <div class="mb-6">
                                <label class="form-label">Trip Landscape</label>

                                <select
                                    class="form-control {{ $errors->has('form.tripLandscape') ? 'border border-danger' : '' }}"
                                    id="tripLandscape" name="form.tripLandscape" wire:model="form.tripLandscape">
                                    <option value="" selected disabled>Select Landscape</option>
                                    <option value="Beach">Beach</option>
                                    <option value="City">City</option>
                                    <option value="Country Side">Country Side</option>
                                    <option value="Forested">Forested</option>
                                    <option value="Mountainous">Mountainous</option>
                                </select>
                                <x-input-error :messages="$errors->get('form.tripLandscape')" class="mt-2" />

                            </div>

                            <div class="mb-2">

                                <select
                                    class="form-control {{ $errors->has('form.tripAvailability') ? 'border border-danger' : '' }}"
                                    id="tripAvailability" name="form.tripAvailability" wire:model="form.tripAvailability">
                                    <option value="" disabled selected>Select Availability</option>
                                    <option value="available">Available</option>
                                    <option value="coming soon">Coming Soon</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                             <x-input-error :messages="$errors->get('form.tripAvailability')" class="mt-2" />

                            </div>

                            <div class="mb-6">
                                <label class="form-label">Trip Description</label>

                                <textarea name="tripDescription" placeholder="Enter description of this trip"
                                    wire:model="form.tripDescription"
                                    class="form-control {{ $errors->has('form.tripDescription') ? 'border border-danger' : '' }}" rows="4" cols="8"></textarea>
                                <x-input-error :messages="$errors->get('form.tripDescription')" class="mt-2" />

                            </div>

                            <div class="mb-6">
                            <label class="form-label">Trip Activities</label>
                            <textarea 
                            name="tripActivities" 
                            placeholder="Enter trip activities of this trip" 
                            wire:model="form.tripActivities"
                            class="form-control {{ $errors->has('form.tripActivities') ? 'border border-danger' : ''}}"
                            rows="4"
                            cols="8"
                            id="quill">
                            </textarea>
                            <x-input-error :messages="$errors->get('form.tripActivities')" class="mt-2"/>


                            </div>

                            <div class="mb-6">
                                <label class="form-label">Trip Start Date</label>

                                <input type="date" id="tripStartDate" name="tripStartDate"
                                    class="form-control {{ $errors->has('form.tripStartDate') ? 'border border-danger' : '' }}"
                                    wire:model="form.tripStartDate" />
                                <x-input-error :messages="$errors->get('form.tripStartDate')" class="mt-2" />

                            </div>

                            <div class="mb-6">
                                <label class="form-label">Trip End Date</label>

                                <input type="date" id="tripEndDate" name="tripEndDate"
                                    class="form-control {{ $errors->has('form.tripEndDate') ? 'border border-danger' : '' }}"
                                    wire:model="form.tripEndDate" />
                                <x-input-error :messages="$errors->get('form.tripEndDate')" class="mt-2" />

                            </div>

                            <div class="mb-6">
                                <label class="form-label">Trip Price (Per Person)</label>

                                <input type="text" id="tripPrice" name="tripPrice"
                                    class="form-control {{ $errors->has('form.tripPrice') ? 'border border-danger' : '' }}"
                                    wire:model="form.tripPrice" placeholder="$1.00" />
                                <x-input-error :messages="$errors->get('form.tripPrice')" class="mt-2" />
                            </div>

                            <div class="mb-6">
                                <button type="submit" wire:loading.remove class="btn btn-primary">Create Trip</button>
                            </div>

                            <div class="spinner-border text-primary" role="status" wire:Loading>
                                    <span class="visually-hidden">Loading...</span>
                                    </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
