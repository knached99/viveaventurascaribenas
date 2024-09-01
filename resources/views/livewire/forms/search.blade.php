@php 
$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'))
@endphp 

<div class="position-relative">
    <form wire:submit.prevent="search">
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center position-relative">
                <i class="bx bx-search bx-md"></i>
                    <input id="searchQuery" name="searchQuery" wire:model.live.throttle.200ms="searchQuery" type="text"
                    class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
                    aria-label="Search..." />


                <!-- Autocomplete Results Container -->
                <div class="autocomplete-results position-absolute top-100 start-0 w-100 bg-white rounded shadow-lg mt-1 max-height-200 overflow-auto"
                    style="width: 100%;">
                    @if (!empty($searchResults))
                        <ul class="list-group m-0 p-0">
                            @foreach ($searchResults as $result)
                                @if(isset($result['tripID']))
                                    <!-- Trip Result -->
                                    <a href="{{ route('admin.trip', ['tripID' => $result['tripID'] ?? '']) }}" class="text-decoration-none">
                                        <li class="list-group-item p-2 border-bottom hover:bg-light cursor-pointer d-flex align-items-center">
                                            <div class="me-2">
                                                <h5 class="mb-1">{{ $result['tripLocation'] ?? 'No Location' }}</h5>
                                                <small class="text-muted text-truncate">{{ $result['tripDescription'] ?? 'No Description' }}</small>
                                            </div>
                                            @if(!empty($result['tripPhoto']))
                                                <img src="{{ asset('storage/'.$result['tripPhoto']) }}" 
                                                     class="img-thumbnail rounded" 
                                                     style="width: 50px; height: 50px;" />
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded"
                                                     style="width: 50px; height: 50px;">
                                                    No Image
                                                </div>
                                            @endif
                                        </li>
                                    </a>

                                @elseif(isset($result['bookingID']))
                                <!-- Booking Result -->
                                <a href="{{ route('admin.booking', ['bookingID' => $result['bookingID'] ?? '']) }}" class="text-decoration-none">
                                    <li class="list-group-item p-2 border-bottom hover:bg-light cursor-pointer">
                                        <h5 class="mb-1">{{$result['name']}}</h5>
                                        <p class="mb-0 text-muted">{{$result['email']}} | {{$result['phone_number']}}</p>
                                        <p class="mb-0 text-muted">Booked location: 
                                        
                                        @php 
                                        try {
                                            $product = $stripe->products->retrieve($result['stripe_product_id']);
                                            $location = $product->name;
                                        } catch (\Exception $e) {
                                            $location = 'Unknown location';
                                        }
                                        @endphp
                                        {{$location}}
                                        </p>
                                    </li>
                                </a>
                            


                                @elseif(isset($result['testimonialID'])) 
                                    <!-- Testimonial Result -->
                                    <a href="{{ route('admin.testimonial', ['testimonialID' => $result['testimonialID'] ?? '']) }}" class="text-decoration-none">
                                        <li class="list-group-item p-2 border-bottom hover:bg-light cursor-pointer">
                                            <h5 class="mb-1">{{ $result['name'] ?? 'Anonymous' }}</h5>
                                            <p class="mb-0 text-muted">{{ \Str::limit($result['testimonial'] ?? 'No Testimonial', 100) }}</p>
                                        </li>
                                    </a>
                               
                                @endif
                            @endforeach
                        </ul>
                        <button type="button" wire:click="clearSearchResults" class="btn btn-link mt-2">Clear</button>
                    @elseif(isset($searchQuery) && $searchQuery !== '')
                        <p class="p-2 text-center">No results found for "{{ $searchQuery }}"</p>
                        <button type="button" wire:click="clearSearchResults" class="btn btn-link mt-2">Clear</button>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>