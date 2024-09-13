<?php

use App\Livewire\Forms\TripForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.authenticated-theme')] class extends Component {
    public TripForm $form;
    use WithFileUploads;



    public function addCost(): void
    {
        // Explicitly set the next index based on the current number of costs
        $index = count($this->form->tripCosts);
        $this->form->tripCosts[$index] = ['name' => '', 'amount' => 0];
    }

    public function removeCost(int $index): void
    {
        unset($this->form->tripCosts[$index]);

        // Reset array keys to avoid gaps and string keys
        $this->form->tripCosts = array_values($this->form->tripCosts);
    }

    public function submitTripForm(): void
    {
        $this->form->submitTripForm();
    }
};
?>

<div class="container-fluid p-4">
    <h6 style="font-weight: 800;">
        When you create your trip, the changes are automatically reflected on the <a href="/" target="_blank">home</a> and <a
            href="{{ route('destinations') }}" target="_blank">destinations</a> pages
    </h6>
    <p class="text-slate-500 font-medium">You may also add your expenses accrued for this trip which will be used to
        calculate the net cost.
        <span class="text-indigo-600 block">That information will not be displayed to your users</span>
    </p>
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
                                        <img src="{{ $photo->temporaryUrl() }}" class="img-fluid rounded"
                                            style="max-width: 200px; height: 200px;" />
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
                                class="form-control ckeditor {{ $errors->has('form.tripDescription') ? 'is-invalid' : '' }}" rows="4"></textarea>
                            <x-input-error :messages="$errors->get('form.tripDescription')" class="invalid-feedback" />
                        </div>

                        <div class="mb-4">
                            <label for="tripActivities" class="form-label">Trip Activities</label>
                            <textarea id="tripActivities" name="tripActivities" placeholder="Enter trip activities" wire:model="form.tripActivities"
                                class="form-control ckeditor {{ $errors->has('form.tripActivities') ? 'is-invalid' : '' }}" rows="4"></textarea>
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

                        <div class="mb-4">
                            <label for="tripCosts" class="form-label">Trip Costs</label>

                            @foreach ($form->tripCosts as $index => $cost)
                                @php
                                    $index = (int) $index; // Ensure $index is an integer
                                @endphp
                                <div class="input-group mb-2">
                                    <input type="text" placeholder="Cost Name" class="form-control"
                                        wire:model="form.tripCosts.{{ $index }}.name" aria-label="Cost Name">

                                    <input type="number" placeholder="Cost Amount" class="form-control"
                                        wire:model="form.tripCosts.{{ $index }}.amount"
                                        aria-label="Cost Amount">

                                    <button type="button" class="btn btn-danger"
                                        wire:click="removeCost({{ $index }})">Remove</button>
                                </div>
                            @endforeach
                           


                            <button type="button" class="btn btn-success" wire:click="addCost">Add Cost</button>

                            <x-input-error :messages="$errors->get('form.tripCosts')" class="invalid-feedback" />
                        </div>

                        <!-- Slots Available -->
                        <div class="mb-4">
                        <input type="text" name="num_trips" placeholder="Enter number of available slots" class="form-control {{ $errors->has('form.num_trips') ? 'is-invalid' : '' }}" wire:model="form.num_trips" />
                            <x-input-error :messages="$errors->get('form.num_trips')" class="invalid-feedback" />
                        </div>

                       <!-- Active or Inactive --> 
                        <div class="mb-4">
                            <span class="text-secondary">This trip will be visible publicly only when it is switched to <b>active</b></span>

                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" wire:model="form.active">
                                <label class="form-check-label" for="active">{{ $active ? 'Active' : 'Inactive' }}</label>
                            </div>
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
