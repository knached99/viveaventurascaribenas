@props(['trip'])

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-4">
            <div class="card shadow-sm border-0">
                <!-- Card Header -->
                <h3 class="m-3">Trip Information For</h3>
                <div class="card-header d-flex align-items-center justify-content-between bg-gradient-primary text-dark rounded-top">
                    <input type="text" wire:model="tripLocation" class="form-control-plaintext text-dark fw-bold border border-primary p-2 rounded"/>
                </div>

                <!-- Editable Image -->
                <div class="text-center p-3">
                    <label for="tripPhoto" class="form-label">Trip Photo:</label>
                    <input type="file" wire:model="newTripPhoto" id="tripPhoto" class="form-control-file mb-2" />
                    {{-- <img src="{{ asset('storage/' . $trip->tripPhoto) }}" class="img-thumbnail rounded" style="width: 300px; height: 300px;" alt="{{ $trip->tripLocation }}" /> --}}
                </div>

                <!-- Editable Trip Description -->
                <div class="card-body p-4">
                    <textarea wire:model="tripDescription" class="form-control mb-3" rows="5" style="resize: none;" placeholder="Enter trip description..."></textarea>

                    <!-- Date Range and Price -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <label for="tripStartDate" class="form-label">Start Date:</label>
                            <input type="date" wire:model="tripStartDate" id="tripStartDate" class="form-control"/>
                        </div>
                        <div>
                            <label for="tripEndDate" class="form-label">End Date:</label>
                            <input type="date" wire:model="tripEndDate" id="tripEndDate" class="form-control"/>
                        </div>
                        <div>
                            <label for="tripPrice" class="form-label">Price:</label>
                            <input type="text" wire:model="tripPrice" id="tripPrice" class="form-control text-success" step="0.01" />
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="card-footer bg-light d-flex justify-content-end">
                    <button wire:click="save" type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

@if (session()->has('message'))
    <div class="alert alert-success mt-3">
        {{ session('message') }}
    </div>
@endif
