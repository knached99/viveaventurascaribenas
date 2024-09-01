@props(['trip'])

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
                <div class="card-header bg-light d-flex justify-content-between align-items-center border-bottom-0 rounded-top">
                    <div>
                        <h3 class="mb-0 text-dark fw-semibold">Trip Information for {{$trip->tripLocation}}</h3>
                    </div>
                   
                </div>




                <!-- Editable Image -->
                <div class="text-center p-4">
                    <label for="tripPhoto" class="form-label d-block mb-2">Trip Photo:</label>
                    <input type="file" wire:model="newTripPhoto" id="tripPhoto" class="form-control-file mb-3" />
                    <img src="{{ asset('storage/' . $trip->tripPhoto) }}" class="img-fluid img-thumbnail rounded" style="max-width: 300px; height: auto;" alt="{{ $trip->tripLocation }}" />
                </div>

                <!-- Editable Trip Description -->
                <div class="card-body p-4">
                <div class="mb-3">
                    <label for="tripLocation" class="form-label mb-0 me-2">Location:</label>
                    <input type="text" wire:model="tripLocation" id="tripLocation" class="form-control form-control-sm border-1 rounded-3 p-2" placeholder="Trip Location" value="{{$trip->tripLocation}}" />
                    </div>

                    <div class="mb-3">
                        <label for="tripDescription" class="form-label">Trip Description:</label>
                        <textarea wire:model="tripDescription" id="tripDescription" class="form-control" rows="5" placeholder="Enter trip description...">{{$trip->tripDescription}}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tripActivities" class="form-label">Trip Activities:</label>
                        <textarea id="tripActivities" class="form-control" rows="5" placeholder="Enter trip activities...">{{$trip->tripActivities}}</textarea>
                    </div>

                    <!-- Date Range and Price -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="tripLandscape" class="form-label">Landscape:</label>
                            <select id="tripLandscape" class="form-select">
                                <option disabled selected value="{{$trip->tripLandscape}}">{{$trip->tripLandscape}}</option>
                                <option value="Beach">Beach</option>
                                <option value="City">City</option>
                                <option value="Country Side">Country Side</option>
                                <option value="Forested">Forested</option>
                                <option value="Mountainous">Mountainous</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tripAvailability" class="form-label">Availability:</label>
                            <select id="tripAvailability" class="form-select">
                                <option disabled selected value="{{$trip->tripAvailability}}">{{$trip->tripAvailability}}</option>
                                <option value="available">Available</option>
                                <option value="coming soon">Coming Soon</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tripStartDate" class="form-label">Start Date:</label>
                            <input type="date" id="tripStartDate" class="form-control" value="{{ $startDate }}"/>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tripEndDate" class="form-label">End Date:</label>
                            <input type="date" id="tripEndDate" class="form-control" value="{{ $endDate }}"/>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tripPrice" class="form-label">Price:</label>
                            <input type="text" wire:model="tripPrice" id="tripPrice" class="form-control" step="0.01" value="{{$trip->tripPrice}}"/>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="card-footer bg-white d-flex justify-content-end">
                    <button wire:click="save" type="button" class="btn btn-primary m-3">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
    }

    .card-header {
        border-bottom: 1px solid #e9ecef;
    }

    .img-thumbnail {
        border: none;
    }
</style>
