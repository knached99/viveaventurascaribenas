@props(['isHomePage'])

<section class="ftco-section py-5">
    <div class="container">

        <div class="row justify-content-center">
            <div class="blog-entry p-4 shadow-md rounded bg-light">

                <form class="row g-3" wire:submit.prevent="submitTestimonialForm">
                    <x-honeypot livewire-model="extraFields" />

                    <!-- Name Input -->
                    <div class="col-12">
                        <div class="form-group">
                            <input type="text" wire:model="name" name="name"
                                class="form-control {{ $errors->has('name') ? 'border border-danger' : '' }}"
                                placeholder="First Name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div class="col-12">
                        <div class="form-group">
                            <input type="email" wire:model="email"
                                class="form-control {{ $errors->has('email') ? 'border border-danger' : '' }}"
                                placeholder="Email (required for follow-up only)">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Trip Selection -->
                    @if ($isHomePage)
                        <div class="col-12">
                            <div class="form-group">
                                <select class="form-control {{ $errors->has('tripID') ? 'border border-danger' : '' }}"
                                    wire:model="tripID">
                                    <option value="" disabled selected>Where did you travel with us?</option>
                                    @forelse ($trips as $trip)
                                        <option value="{{ $trip['tripID'] }}">{{ $trip['tripLocation'] }}</option>
                                    @empty
                                        <option value="" disabled>No trips available</option>
                                    @endforelse
                                </select>
                                @error('tripID')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @else
                        <input type="hidden" wire:model="tripID" value="{{ $tripID }}" />
                    @endif
                    <!-- Trip Selection -->



                    <!-- Travel Date -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Travel Date</label>
                            <input wire:model="trip_date" type="month" max="{{ date('Y-m') }}"
                                value="{{ date('Y-m') }}"
                                class="form-control {{ $errors->has('trip_date') ? 'border border-danger' : '' }}">
                            @error('trip_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Rate Your Experience (1-5 Stars)</label>
                            <div class="rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input wire:model="trip_rating" type="radio" name="rating"
                                        value="{{ $i }}" id="{{ $i }}">
                                    <label for="{{ $i }}">☆</label>
                                @endfor
                            </div>
                            @error('trip_rating')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Testimonial -->
                    <div class="col-12">
                        <div class="form-group">
                            <textarea wire:model="testimonial" class="form-control {{ $errors->has('testimonial') ? 'border border-danger' : '' }}"
                                rows="7" placeholder="Tell us about your experience (What made your trip special?)"></textarea>
                            @error('testimonial')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Consent -->
                    <div class="col-12">
                        <div class="form-group d-flex align-items-center">
                            <input wire:model="consent" type="checkbox" class="form-check-input me-2" id="consent"
                                name="consent">
                            <label class="form-label mb-0 m-3" for="consent">I consent to my testimonial being used
                                on the
                                website</label>
                            @error('consent')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100 py-3" wire:loading.remove>Submit
                            Testimonial</button>
                        <div class="spinner-border text-primary" role="status" wire:loading></div>
                    </div>

                    <!-- Status Messages -->
                    <div>
                        @if ($status)
                            <div class="mb-4 alert alert-success" role="alert">{{ $status }}</div>
                        @elseif ($error)
                            <div class="mb-4 alert alert-danger" role="alert">{{ $error }}</div>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
