<section class="ftco-section py-5">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ">
                <h2 class="mb-4">Share Your Travel Experience with Us!</h2>
                <p class="text-secondary">Your feedback helps us improve and inspires other travelers. We'd love to hear
                    about your journey!</p>
                <div>
                    @if ($status)
                        <div class="mb-4 alert alert-success" role="alert">
                            {{ $status }}
                        </div>
                    @elseif($error)
                        <div class="mb-4 alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @endif
                </div>

            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="blog-entry p-4  shadow-md rounded bg-light">
                        <form class="row g-3" wire:submit.prevent="submitTestimonialForm">
                            <x-honeypot livewire-model="extraFields" />
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
                            <div class="col-12">
                                <div class="form-group">
                                    <select
                                    class="form-control {{ $errors->has('tripID') ? 'border border-danger' : '' }}"
                                        wire:model="tripID">
                                        <option value="" disabled selected>Where did you travel with us?</option>
                                        @foreach ($trips as $trip)
                                            <option value="{{ $trip['tripID'] }}"> {{ $trip['tripLocation'] }}</option>
                                        @endforeach
                                    </select>

                                    {{-- <input class="form-control {{$errors->has('trip_location') ? 'border border-danger' : ''}}" wire:model="trip_location" placeholder="Where did you travel with us? (Destination)"> --}}
                                    @error('tripID')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                              <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Travel Date</label>
                                <input wire:model="trip_date" type="month" max="{{date('Y-m')}}" value="{{date('Y-m')}}" class="form-control {{$errors->has('trip_date') ? 'border border-danger' : ''}}">
                                @error('trip_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                            <div class="col-12">
                                <div
                                    class="form-group {{ $errors->has('trip_rating') ? 'border border-danger' : '' }}">
                                    <label class="form-label">Rate Your Experience (1-5 Stars)</label>
                                    <div class="rating">
                                        <input wire:model="trip_rating" type="radio" name="rating" value="5"
                                            id="5">
                                        <label for="5">☆</label>
                                        <input wire:model="trip_rating" type="radio" name="rating" value="4"
                                            id="4">
                                        <label for="4">☆</label>
                                        <input wire:model="trip_rating" type="radio" name="rating" value="3"
                                            id="3">
                                        <label for="3">☆</label>
                                        <input wire:model="trip_rating" type="radio" name="rating" value="2"
                                            id="2">
                                        <label for="2">☆</label>
                                        <input wire:model="trip_rating" type="radio" name="rating" value="1"
                                            id="1">
                                        <label for="1">☆</label>

                                        @error('trip_rating')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea wire:model="testimonial" class="form-control {{ $errors->has('testimonial') ? 'border border-danger' : '' }}"
                                        rows="7" placeholder="Tell us about your experience (What made your trip special?)"></textarea>
                                    @error('testimonial')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div
                                    class="form-group d-flex align-items-center {{ $errors->has('consent') ? 'border border-danger' : '' }}">
                                    <input wire:model="consent" type="checkbox" class="form-check-input me-2"
                                        id="consent" name="consent">
                                    <label class="form-label mb-0 m-3" for="consent">I consent to my testimonial being
                                        used on the website</label>
                                    @error('consent')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100 py-3"
                                        wire:loading.remove>Submit Testimonial</button>
                                    <div class="spinner-border text-primary" role="status" wire:loading></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</section>
