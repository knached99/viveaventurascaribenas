<div class="w-full max-w-lg relative"> <!-- Make this relative to contain the absolute child -->

    <header class='bg-white font-sans min-h-[60px] px-10 py-3 tracking-wide z-50'>
        <div class="bg-gray-100 flex rounded-full w-full mt-3 mx-auto max-lg:mt-6">
            <form wire:submit.prevent="search" class="w-full" wire:loading.remove="searchQuery">
                <input type="search" placeholder="Search..." id="searchQuery" name="searchQuery" wire:model="searchQuery"
                    class="w-full bg-transparent text-gray-600 font-semibold text-[15px] px-6 py-2 rounded-full focus:ring-0 focus:outline-none" />
            </form>

            <div class="spinner-border" role="status" wire:loading wire:target="searchQuery">
                <span class="visually-hidden">Loading search results...</span>
            </div>
        </div>
    </header>


    <!-- Results Container -->
    @if (!empty($searchResults))

        <button type="button" wire:click="clearSearchResults"
            class="btn btn-link mt-2 text-blue-600 hover:underline">Clear</button>

        <ul
            class="list-group m-0 p-0 bg-white shadow-lg border border-gray-200 rounded-lg mt-2 absolute w-full max-h-[400px] overflow-y-auto z-40">
            <!-- Added absolute positioning -->
            @foreach ($searchResults as $result)
                @if (is_array($result) && isset($result['type']))
                    @if ($result['type'] === 'trip')
                        <a href="{{ route('admin.trip', ['tripID' => $result['tripID']]) }}"
                            class="text-decoration-none">
                            <li class="list-group-item p-2 border-b hover:bg-gray-100 cursor-pointer">
                                @php
                                    $tripImages = json_decode($result['tripPhoto'], true);
                                    $tripImage1 = $tripImages[0] ?? null;
                                @endphp
                                <img src="{{ $tripImage1 }}" alt="{{ $result['tripLocation'] }}" class="w-20 h-20" />
                                <h5 class="mb-1">{{ $result['tripLocation'] ?? 'Undefined Location' }} - Trip</h5>
                            </li>
                        </a>
                    @elseif($result['type'] === 'booking')
                        @php
                            $bookingImages = json_decode($result['trip']['tripPhoto'], true);
                            $bookingImage1 = $bookingImages[0] ?? null;
                        @endphp
                        <a href="{{ route('admin.booking', ['bookingID' => $result['bookingID']]) }}"
                            class="text-decoration-none">
                            <li class="list-group-item p-4 border-b hover:bg-gray-100 cursor-pointer flex items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ $bookingImage1 }}" alt="{{ $result['trip']['tripLocation'] }}"
                                        class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-md" />
                                </div>
                                <div class="ml-4">
                                    <h5 class="mb-1 font-semibold text-gray-800">
                                        {{ $result['name'] ?? 'Unnamed Booking' }}</h5>
                                    <p class="mb-1 text-gray-500">{{ $result['email'] ?? 'No Email' }} |
                                        {{ $result['phone_number'] ?? 'No Phone Number' }}</p>
                                    <p class="mb-0 text-gray-600">
                                        <span class="font-medium">Booked Location:</span>
                                        {{ $result['trip']['tripLocation'] ?? 'Undefined Location' }}
                                    </p>
                                </div>
                            </li>
                        </a>
                    @elseif($result['type'] === 'reservation')
                        @php
                            $reservationImages = json_decode($result['trip']['tripPhoto'], true);
                            $reservationImage1 = $reservationImages[0] ?? null;
                        @endphp
                        <a href="{{ route('admin.reservation', ['reservationID' => $result['reservationID']]) }}"
                            class="text-decoration-none">
                            <li class="list-group-item p-4 border-b hover:bg-gray-100 cursor-pointer flex items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ $reservationImage1 }}" alt="{{ $result['trip']['tripLocation'] }}"
                                        class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-md" />
                                </div>
                                <div class="ml-4">
                                    <h5 class="mb-1 font-semibold text-gray-800">
                                        {{ $result['name'] ?? 'Unnamed Reservation' }}</h5>
                                    <p class="mb-1 text-gray-500">{{ $result['email'] ?? 'No Email' }} |
                                        {{ $result['phone_number'] ?? 'No Phone Number' }}</p>
                                    <p class="mb-0 text-gray-600">
                                        <span class="font-medium">Reserved Location:</span>
                                        {{ $result['trip']['tripLocation'] ?? 'Undefined Location' }}
                                    </p>
                                </div>
                            </li>
                        </a>
                    @elseif($result['type'] === 'testimonial')
                        @php
                            $testimonialImages = json_decode($result['trip']['tripPhoto'], true);
                            $testimonialImage1 = $testimonialImages[0] ?? null;
                        @endphp

                        <a href="{{ route('admin.testimonial', ['testimonialID' => $result['testimonialID']]) }}"
                            class="text-decoration-none">
                            <li class="list-group-item p-4 border-b hover:bg-gray-100 cursor-pointer flex items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ $testimonialImage1 }}" alt="{{ $result['trip']['tripLocation'] }}"
                                        class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-md" />
                                </div>
                                <div class="ml-4">
                                    <h5 class="mb-1 font-semibold text-gray-800">
                                        {{ $result['name'] ?? 'Unnamed Reservation' }}</h5>
                                    <p class="mb-1 text-gray-500">{{ $result['name'] ?? 'Anonymous' }} |
                                        {{ $result['email'] ?? 'No Email' }}</p>
                                    <p class="mb-0 text-gray-600">
                                        <span class="font-bold block">Testimonial</span>
                                        <span class="text-clip">{{ $result['testimonial'] }}</span>
                                    </p>
                                </div>
                            </li>
                        </a>
                    @endif
                @endif
            @endforeach

        </ul>
    @elseif(isset($searchQuery) && $searchQuery !== '')
        <p class="p-2 text-center text-gray-600">No results found for "{{ $searchQuery }}"</p>
        <button type="button" wire:click="clearSearchResults"
            class="btn btn-link mt-2 text-blue-600 hover:underline">Clear</button>
    @endif

</div>
