<?php

use App\Livewire\Forms\PhotoGalleryUpload;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\TripsModel;
new #[Layout('layouts.authenticated-theme')] class extends Component {
    public PhotoGalleryUpload $form;

    public array $trips = [];

    // public string $success = '';
    // public string $error = '';

    use WithFileUploads;

    public function uploadPhotosToGallery(): void
    {
        $this->form->uploadPhotosToGallery();
    }

    public function mount()
    {
        $this->trips = TripsModel::select('tripID', 'tripLocation')->get()->toArray();
    }
};
?>

<div class="container-fluid p-4">
    <h6 style="font-weight: 800;">Upload photos to the gallery</h6>
    <p class="text-slate-500 font-medium">When you upload a photo or multiple photos, you will need to associate them
        with a trip you've uploaded.</p>

    <div class="row">
        <div class="col-12 col-xl-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form wire:submit.prevent="uploadPhotosToGallery" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="photos" class="form-label">Select Photos (max 5 images)</label>
                            <input type="file" id="photos" name="photos" wire:model="form.photos"
                                class="form-control {{ $errors->has('form.photos') ? 'is-invalid' : '' }}" multiple />
                            <x-input-error :messages="$errors->get('form.photos')" class="invalid-feedback" />
                        </div>

                        <div class="mb-4">
                            <label for="photoLabel" class="form-label">photo Label</label>
                            <input type="text" id="photoLabel" name="photoLabel" wire:model="form.photoLabel"
                                class="form-control {{ $errors->has('form.photoLabel') ? 'is-invalid' : '' }}" />
                            <x-input-error :messages="$errors->get('form.photoLabel')" class="invalid-feedback" />
                        </div>

                        <div class="mb-4">
                            <label for="photoDescription" class="form-label">Photo Description</label>
                            <textarea id="photoDescription" name="photoDescription" wire:model="form.photoDescription"
                                class="form-control {{ $errors->has('form.photoDescription') ? 'is-invalid' : '' }}"></textarea>
                            <x-input-error :messages="$errors->get('form.photoDescription')" class="invalid-feedback" />
                        </div>

                        <!-- Assocaite photo with selected trip -->

                        <div class="mb-4">
                            @if (empty($trips))
                                <span class="text-slate-600"><a href="{{ route('admin.create-trip') }}">Create a
                                        trip</a> to associate this upload</span>
                            @else
                                <label for="tripID" class="form-label">Associate photos with a trip</label>

                                <select class="form-control p-3 {{ $errors->has('form.tripID') ? 'is-invalid' : '' }}"
                                    name="tripID" wire:model="form.tripID">
                                    <option value="" disabled selected>Select a Trip</option>
                                    @foreach ($trips as $trip)
                                        <option value="{{ $trip['tripID'] }}">{{ $trip['tripLocation'] }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('form.tripID')" class="invalid-feedback" />
                            @endif
                        </div>



                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-primary me-3" wire:loading.remove
                                wire:target="uploadToGallery">Upload</button>

                            <div wire:loading wire:target="uploadToGallery">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Uploading...</span>
                                </div>
                            </div>

                        </div>

                        @if ($form->success)
                            <div class="alert alert-success">
                                {{ $form->success }}
                                <a href="{{ route('admin.photo-gallery') }}">See Gallery</a>
                            </div>
                        @elseif($form->error)
                            <div class="alert alert-danger">
                                {{ $form->error }}
                            </div>
                        @endif
                </div>
                </form>
            </div>
        </div>
    </div>

</div>
