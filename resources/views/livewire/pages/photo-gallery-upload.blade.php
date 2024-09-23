<?php

use App\Livewire\Forms\PhotoGalleryUpload;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.authenticated-theme')] class extends Component {

    public PhotoGalleryUpload $form;

    use WithFileUploads;

    public function uploadPhotosToGallery(): void {
        $this->form->uploadPhotosToGallery();
    }
}
?>

<div class="container-fluid p-4">
<h6 style="font-weight: 800;">Upload photos to the gallery</h6>
<p class="text-slate-500 font-medium">When you upload a photo or multiple photos, you will need to associate them with a trip you've uploaded.</p>

<div class="row">
<div class="col-12 col-xl-8 mb-4">
<div class="card shadow-sm border-0">
<div class="card-body">
 <form wire:submit.prevent="uploadPhotosToGallery" enctype="multipart/form-data">
<div class="mb-4">
<label for="photos" class="form-label">Select Photos</label>
<input type="file" id="photos" name="photos" wire:model="form.photos" class="form-control {{$errors->has('form.photos') ? 'is-invalid' : ''}}" multiple />
<x-input-error :messages="$errors->get('form.photos')" class="invalid-feedback" />
</div>

<div class="mb-4">
<label for="photoLabel" class="form-label">photo Label</label>
<input type="text" id="photoLabel" name="photoLabel" wire:model="form.photoLabel" class="form-control {{$errors->has('form.photoLabel') ? 'is-invalid' : ''}}" />
<x-input-error :messages="$errors->get('form.photoLabel')" class="invalid-feedback" />
</div>

<div class="mb-4">
<label for="photoDescription" class="form-label">Photo Description</label>
<textarea id="photoDescription" name="photoDescription" wire:model="form.photoDescription" class="form-control {{$errors->has('form.photoDescription') ? 'is-invalid' : ''}}"></textarea>
<x-input-error :messages="$errors->get('form.photoDescription')" class="invalid-feedback" />
</div>

<!-- Assocaite photo with selected trip -->

  <div class="d-flex align-items-center">
<!-- The submit button will be hidden while the form is submitting -->
<button type="submit" class="btn btn-primary me-3" wire:loading.remove
     wire:target="submitTripForm">Upload</button>

<!-- Show the loading spinner while the form is being submitted -->
<div wire:loading wire:target="submitTripForm">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Uploading...</span>
     </div>
</div>
</div>
</form>
</div>
</div>
</div>

</div>