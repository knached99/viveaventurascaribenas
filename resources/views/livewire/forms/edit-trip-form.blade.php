@php
    use Carbon\Carbon;

    $startDate = Carbon::parse($trip->tripStartDate)->format('Y-m-d');
    $endDate = Carbon::parse($trip->tripEndDate)->format('Y-m-d');
    $tripPhotos = json_decode($trip->tripPhoto, true);
@endphp

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <!-- Card Header -->
                <div
                    class="card-header bg-slate-200 text-white d-flex justify-content-between align-items-center rounded-top">
                    <h3 class="mb-0">Trip Information for {{ $trip->tripLocation }}</h3>
                </div>

                <form wire:submit.prevent="editTrip" class="p-4" enctype="multipart/form-data">
                    <!-- Editable Images -->
                    <div class="text-center mb-4">
                        <label for="tripPhotos" class="form-label fw-semibold d-block mb-2">Trip Photos</label>
                        <div class="d-flex flex-wrap justify-content-center">
                            @if ($tripPhotos && count($tripPhotos) > 0)
                                @foreach ($tripPhotos as $index => $photo)
                                    <div class="position-relative m-2">
                                        @if (is_string($photo))
                                            <img src="{{ $photo }}"
                                                class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer"
                                                style="max-width: 150px; height: 150px;" alt="Trip Image"
                                                wire:click="selectImageToReplace({{ $index }})" />
                                        @elseif($photo instanceof \Livewire\TemporaryUploadedFile)
                                            <img src="{{ $photo->temporaryUrl() }}"
                                                class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer"
                                                style="max-width: 150px; height: 150px;" alt="Trip Image"
                                                wire:click="selectImageToReplace({{ $index }})" />
                                        @endif

                                        <button type="button" wire:click="removeImage({{ $index }})"
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-1 me-1">
                                            <i class='bx bx-trash-alt'></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <p>No images available.</p>
                            @endif
                        </div>

                        @if (!is_null($replaceIndex))
                            <div class="mb-3">
                                <input type="file" wire:model="tripPhotos.{{ $replaceIndex }}"
                                    class="form-control" />
                                @error('tripPhotos.' . $replaceIndex)
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div wire:loading wire:target="tripPhotos.{{ $replaceIndex }}">
                                    <span>Uploading...</span>
                                </div>
                            </div>

                            <button type="button" wire:loading.attr="disabled"
                                wire:target="tripPhotos.{{ $replaceIndex }}"
                                wire:click="replaceImage({{ $replaceIndex }})" class="btn btn-primary">
                                Replace Image
                            </button>
                        @else
                            <div class="mb-3">
                                <input type="file" wire:model="tripPhotos" class="form-control" multiple />
                                @error('tripPhotos.*')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

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
                        <label for="tripDescription" class="form-label">Trip Description</label>
                        <textarea id="tripDescription" name="tripDescription" wire:model="tripDescription" class="form-control ckeditor"
                            rows="4">{{ $this->tripDescription }}</textarea>
                        @error('tripDescription')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Activities -->
                    <div class="mb-3">
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
                    <div class="mb-4">
                        <label for="tripLandscape" class="form-label">Trip Landscape</label>
                        <select id="tripLandscape" wire:model="tripLandscape" class="form-select">
                            <option value="" disabled>Select Landscape</option>
                            <option value="Beach">Beach</option>
                            <option value="City">City</option>
                            <option value="Country Side">Country Side</option>
                            <option value="Forested">Forested</option>
                            <option value="Mountainous">Mountainous</option>
                        </select>
                        @error('tripLandscape')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Availability -->
                    <div class="mb-3">
                        <label for="tripAvailability" class="form-label">Trip Availability</label>
                        <select id="tripAvailability" wire:model="tripAvailability" class="form-select">
                            <option value="" disabled>Select Availability</option>
                            <option value="available">Available</option>
                            <option value="coming_soon">Coming Soon</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                        @error('tripAvailability')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Trip Price -->
                    <div class="mb-3">
                        <label for="tripPrice" class="form-label">Trip Price</label>
                        <input id="tripPrice" wire:model="tripPrice" class="form-control" placeholder="$1.00" />
                        @error('tripPrice')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                     <!-- Trip Costs Section -->
                            {{-- <div class="mb-4">
                                <h5 class="mb-3">Trip Costs</h5>
                                @forelse ($tripCosts as $index => $cost)
                                    <div class="input-group mb-2">
                                        <input type="text" placeholder="Cost Name" class="form-control" wire:model.defer="tripCosts.{{ $index }}.name">
                                        <input type="number" placeholder="Cost Amount" class="form-control" wire:model.defer="tripCosts.{{ $index }}.amount">
                                        <button type="button" class="btn btn-danger" wire:click="removeCost({{ $index }})">
                                            Remove
                                        </button>
                                    </div>
                                @empty
                                    <div class="input-group mb-2">
                                        <input type="text" placeholder="Cost Name" class="form-control" wire:model.defer="tripCosts.0.name">
                                        <input type="number" placeholder="Cost Amount" class="form-control" wire:model.defer="tripCosts.0.amount">
                                        <button type="button" class="btn btn-danger" wire:click="removeCost(0)">
                                            Remove
                                        </button>
                                    </div>
                                @endforelse
                                <button type="button" class="btn btn-success" wire:click="addCost">
                                    Add Cost
                                </button>
                            </div> --}}


                        <div class="mb-4">
                            <label for="tripCosts" class="form-label">Trip Costs</label>
                            @foreach ($tripCosts as $index => $cost)
                                @php
                                    $index = (int) $index; // Ensure $index is an integer
                                @endphp
                                <div class="input-group mb-2">
                                    <input type="text" placeholder="Cost Name" class="form-control"
                                        wire:model="tripCosts.{{ $index }}.name" aria-label="Cost Name">

                                    <input type="number" placeholder="Cost Amount" class="form-control"
                                        wire:model="tripCosts.{{ $index }}.amount"
                                        aria-label="Cost Amount">

                                    <button type="button" class="btn btn-danger"
                                        wire:click="removeCost({{ $index }})">Remove</button>
                                </div>
                            @endforeach


                            <button type="button" class="btn btn-success" wire:click="addCost">Add Cost</button>

                            <x-input-error :messages="$errors->get('form.tripCosts')" class="invalid-feedback" />
                        </div>


                    <!-- Summary Section -->
                    <div class="mb-4">
                        <h4 class="mb-3">Financial Summary</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total Net Cost:</strong>
                            <span>${{ number_format($totalNetCost, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Gross Profit:</strong>
                            <span>${{ number_format($grossProfit, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Net Profit:</strong>
                            <span class="{{ $netProfit < 0 ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($netProfit, 2) }}
                            </span>
                        </div>
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
