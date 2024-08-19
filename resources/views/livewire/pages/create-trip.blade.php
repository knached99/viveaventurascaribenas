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
<input 
    type="text"
    class="form-control {{$errors->has('form.tripLocation') ? 'border border-danger' : ''}}"
    id="tripLocation"
    name="tripLocation"
    placeholder="(e.g.) San Juan, Costa Rica"
    autofocus 
    wire:model="form.tripLocation" />

    <x-input-error :messages="$errors->get('form.tripLocation')" class="mt-2" />
    </div>


    <div class="mb-6">
<label class="form-label">Trip Photo</label>
<input 
    type="file"
    class="form-control {{$errors->has('form.tripPhoto') ? 'border border-danger' : ''}}"
    id="tripPhoto"
    name="tripPhoto"
    autofocus 
    wire:model="form.tripPhoto" />

    <x-input-error :messages="$errors->get('form.tripPhoto')" class="mt-2" />
    </div>

    <div class="mb-6">
    <label class="form-label">Trip Landscape</label>

    <select 
     class="form-control {{$errors->has('tripLandscape') ? 'border border-danger' : ''}}"
     id="tripLandscape"
     name="tripLandscape"
     wire:model="form.tripLandscape"
     >
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

    <select class="form-control {{$errors->has('tripAvailability') ? 'border border-danger' : ''}}"
    id="tripAvailability"
    name="tripAvailability"
    wire:model="form.tripAvailability"
    >
    <option value="" disabled selected>Select Availability</option>
    <option value="available">Available</option>
    <option value="coming soon">Coming Soon</option>
    <option value="unavailable">Unavailable</option>
    </select>
    </div>

    <div class="mb-6">
    <label class="form-label">Trip Description</label>

    <textarea id="editor" name="tripDescription" placeholder="Enter description of this trip" wire:model="form.tripDescription"
    class="form-control {{$errors->has('form.tripDescription') ? 'border border-danger' : ''}}"
    ></textarea>
         <x-input-error :messages="$errors->get('form.tripDescription')" class="mt-2" />

    </div>

    <div class="mb-6">
    <label class="form-label">Trip Start Date</label>

    <input type="date" id="tripStartDate" name="tripStartDate" class="form-control {{$errors->has('tripStartDate') ? 'border border-danger' : ''}}"
    wire:model="form.tripStartDate"
    />
    <x-input-error :messages="$errors->get('form.tripStartDate')" class="mt-2" />

    </div>

       <div class="mb-6">
    <label class="form-label">Trip End Date</label>

    <input type="date" id="tripEndDate" name="tripEndDate" class="form-control {{$errors->has('tripEndDate') ? 'border border-danger' : ''}}"
    wire:model="form.tripEndDate"
    />
    <x-input-error :messages="$errors->get('form.tripEndDate')" class="mt-2" />

    </div>

    <div class="mb-6">
        <label class="form-label">Trip Price (Per Person)</label>

    <input type="text" id="tripPrice" name="tripPrice" class="form-control {{$errors->has('tripPrice') ? 'border border-danger' : ''}}" 
    wire:model="form.tripPrice" placeholder="$1.00"
    />
    <x-input-error :messages="$errors->get('form.tripPrice')" class="mt-2"/>
    </div>

    <div class="mb-6">
    <button type="submit" wire:loading.remove class="btn btn-primary">Create Trip</button>
    </div>
    
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
