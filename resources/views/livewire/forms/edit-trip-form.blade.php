@php
    use Carbon\Carbon;

    $startDate = Carbon::parse($trip->tripStartDate)->format('Y-m-d');
    $endDate = Carbon::parse($trip->tripEndDate)->format('Y-m-d');
    $tripPhotos = json_decode($trip->tripPhoto, true);
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
                    <!-- Editable Images -->
                    <div class="text-center mb-4">
                        <label for="tripPhotos" class="form-label fw-semibold d-block">
                            <div class="d-flex flex-wrap justify-content-center">
                                <!-- Check if there are any trip photos -->
                                @if ($tripPhotos && count($tripPhotos) > 0)
                                    <!-- Loop through each trip photo and display with delete or replace option -->
                                    @foreach ($tripPhotos as $index => $photo)
                                        <div class="position-relative m-2">
                                            @if (is_string($photo))
                                                <!-- Display existing photo (URL stored in the database) -->
                                                <img src="{{ $photo }}"
                                                    class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer hover:opacity-50 transition-opacity duration-300"
                                                    style="max-width: 200px; height: 200px;" alt="Trip Image"
                                                    wire:click="selectImageToReplace({{ $index }})" />
                                            @elseif($photo instanceof \Livewire\TemporaryUploadedFile)
                                                <!-- Display new uploaded photo -->
                                                <img src="{{ $photo->temporaryUrl() }}"
                                                    class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer hover:opacity-50 transition-opacity duration-300"
                                                    style="max-width: 200px; height: 200px;" alt="Trip Image"
                                                    wire:click="selectImageToReplace({{ $index }})" />
                                            @endif

                                            <!-- Delete button to remove image -->
                                            <button type="button" wire:click="removeImage({{ $index }})"
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-1 me-1">
                                                <i class='bx bx-trash-alt'></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Default message when no images are available -->
                                    <p>No images available.</p>
                                @endif
                            </div>
                        </label>

                        @if (!is_null($replaceIndex))
                            <div class="mb-3">
                                <input type="file" wire:model="tripPhotos.{{ $replaceIndex }}"
                                    class="form-control" />

                                @error('tripPhotos.' . $replaceIndex)
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <!-- Display loading message or spinner when uploading -->
                                <div wire:loading wire:target="tripPhotos.{{ $replaceIndex }}">
                                    Uploading...
                                </div>
                            </div>

                            <!-- Disable button when loading -->
                            <button type="button" wire:loading.attr="disabled"
                                wire:target="tripPhotos.{{ $replaceIndex }}"
                                wire:click="replaceImage({{ $replaceIndex }})" class="btn btn-primary">
                                Replace Image
                            </button>
                        @else
                            <!-- Button to add new image -->
                            <div class="mb-3">
                                <input type="file" wire:model="tripPhotos" class="form-control" multiple />
                                @error('tripPhotos.*')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <!-- Success and Error Messages -->
                        @if ($imageReplaceSuccess)
                            <div class="alert alert-success">
                                {{ $imageReplaceSuccess }}
                            </div>
                        @endif

                        @if ($imageReplaceError)
                            <div class="alert alert-danger">
                                {{ $imageReplaceError }}
                            </div>
                        @endif

                    </div>

                    <!-- Form Fields -->
                    <!-- Location -->
                    <div class="mb-3">
                        <label for="tripLocation" class="form-label">Trip Location</label>
                        <input type="text" id="tripLocation" wire:model="tripLocation" class="form-control" />
                        @error('tripLocation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        {{-- @livewire('admincomponents.quill', ['value' => $this->tripDescription]) --}}

                        <label for="tripDescription" class="form-label">Trip Description</label>
                        <textarea id="tripDescription" name="tripDescription" wire:model="tripDescription" class="form-control ckeditor"
                            rows="4">{{ $this->tripDescription }}</textarea>
                        @error('tripDescription')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Activities -->
                    <div class="mb-3">
                        {{-- @livewire('admincomponents.quill', ['value' => $this->tripActivities]) --}}
                        <label for="tripActivities" class="form-label">Trip Activities</label>
                        <textarea id="tripActivities" name="tripActivities" wire:model="tripActivities" class="form-control ckeditor"
                            rows="4">{{ $this->tripActivities }}</textarea>
                        @error('tripActivities')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                    <!-- Dates -->
                    <div class="mb-3">
                        <label for="tripStartDate" class="form-label">Trip Start Date</label>
                        <input type="date" id="tripStartDate" wire:model="tripStartDate" class="form-control" />
                        @error('tripStartDate')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tripEndDate" class="form-label">Trip End Date</label>
                        <input type="date" id="tripEndDate" wire:model="tripEndDate" class="form-control" />
                        @error('tripEndDate')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Landscape -->
                    <div class="mb-3">
                        <label for="tripLandscape" class="form-label">Trip Landscape</label>
                        <input type="text" id="tripLandscape" wire:model="tripLandscape" class="form-control" />
                        @error('tripLandscape')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Availability -->
                    <div class="mb-3">
                        <label for="tripAvailability" class="form-label">Trip Availability</label>
                        <select id="tripAvailability" wire:model="tripAvailability" class="form-select">
                            <option value="">Select Availability</option>
                            <option value="available">Available</option>
                            <option value="coming_soon">Coming Soon</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                        @error('tripAvailability')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>

                    <!-- Success and Error Messages -->
                    @if ($success)
                        <div class="alert alert-success">
                            {{ $success }}
                        </div>
                    @endif

                    @if ($error)
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
