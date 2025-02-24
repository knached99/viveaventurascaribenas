<div class="w-full max-w-lg relative"> <!-- Make this relative to contain the absolute child -->

    <header class='font-sans min-h-[60px] px-10 py-3 tracking-wide z-50'>
        <div class="bg-gray-100 flex rounded-full w-full mt-3 mx-auto max-lg:mt-6">

            <form wire:submit.prevent="search" class="w-full" wire:loading.remove="searchQuery">
                <input type="search" placeholder="Search..." id="searchQuery" name="searchQuery" wire:model="searchQuery"
                    class="w-full bg-transparent text-gray-600 font-semibold text-[15px] px-6 py-2 rounded-full focus:ring-0 focus:outline-none" />
            </form>

            <div role="status" wire:loading wire:target="searchQuery">
                <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <!-- Displayed on screen readers -->
                <span class="sr-only">loading search results...</span>
            </div>
        </div>
    </header>


    <!-- Results Container -->
    @if (!empty($searchResults))



        <ul
            class="list-group m-0 p-0 bg-white shadow-lg border border-gray-200 rounded-lg mt-2 absolute w-full max-h-[400px] overflow-y-auto z-40">
            <!-- z-50 ensures the span remains on top of other
                elements within the scrollable container. -->
            <span class="block m-3 top-0 sticky z-50 bg-white p-3 float-start">Found <b>{{ count($searchResults) }}</b>
                search
                result(s) for
                "<b>{{ $searchQuery }}</b>"

                <button type="button" wire:click="clearSearchResults"
                    class="float-start block btn btn-link mt-2 text-blue-600 hover:underline">Clear Results</button>
            </span>

            <!-- Added absolute positioning -->
            @foreach ($searchResults as $result)
                @if (is_array($result) && isset($result['type']))
                    @php
                        // We need to determine the image source based on the result type
                        // $tripImages = isset($result['trip'])
                        //     ? json_decode($result['trip']['tripPhoto'], true)
                        //     : json_decode($result['tripPhoto'], true);
                        // $imageSrc = $tripImages[0] ?? asset('assets/images/image_placeholder.jpg');

                        $tripImages = isset($result['trip'])
                            ? (is_string($result['trip']['tripPhoto'])
                                ? json_decode($result['trip']['tripPhoto'], true)
                                : $result['trip']['tripPhoto'])
                            : (is_string($result['tripPhoto'])
                                ? json_decode($result['tripPhoto'], true)
                                : $result['tripPhoto']);

                        $imageSrc =
                            is_array($tripImages) && !empty($tripImages)
                                ? $tripImages[0]
                                : asset('assets/images/image_placeholder.jpg');

                        // Set location for alt text
                        $location =
                            $result['type'] === 'trip'
                                ? $result['tripLocation']
                                : $result['trip']['tripLocation'] ?? 'Undefined Location';
                    @endphp

                    @if ($result['type'] === 'trip')
                        <a href="{{ route('admin.trip', ['tripID' => ltrim($result['tripID'], '#trip_id_')]) }}"
                            class="text-decoration-none">
                            <li class="list-group-item p-2 border-b hover:bg-gray-100 cursor-pointer">
                                <img src="{{ $imageSrc }}"
                                    alt="{{ $location }}"class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-md rounded" />
                                <h5 class="mb-1">{{ $location }} - Trip</h5>
                            </li>
                        </a>
                    @elseif($result['type'] === 'booking')
                        <a href="{{ route('admin.booking', ['bookingID' => $result['bookingID']]) }}"
                            class="text-decoration-none">
                            <li class="list-group-item p-4 border-b hover:bg-gray-100 cursor-pointer flex items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ $imageSrc }}" alt="{{ $location }}"
                                        class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-md rounded" />
                                </div>
                                <div class="ml-4">
                                    <h5 class="mb-1 font-semibold text-gray-800">
                                        {{ $result['name'] ?? 'Unnamed Booking' }}</h5>
                                    <p class="mb-1 text-gray-500">{{ $result['email'] ?? 'No Email' }} |
                                        {{ $result['phone_number'] ?? 'No Phone Number' }}</p>
                                    <p class="mb-0 text-gray-600">
                                        <span class="font-medium">Booked Location:</span> {{ $location }}
                                    </p>
                                </div>
                            </li>
                        </a>
                    @elseif($result['type'] === 'reservation')
                        <a href="{{ route('admin.reservation', ['reservationID' => $result['reservationID']]) }}"
                            class="text-decoration-none">
                            <li class="list-group-item p-4 border-b hover:bg-gray-100 cursor-pointer flex items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ $imageSrc }}" alt="{{ $location }}"
                                        class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-md rounded" />
                                </div>
                                <div class="ml-4">
                                    <h5 class="mb-1 font-semibold text-gray-800">
                                        {{ $result['name'] ?? 'Unnamed Reservation' }}</h5>
                                    <p class="mb-1 text-gray-500">{{ $result['email'] ?? 'No Email' }} |
                                        {{ $result['phone_number'] ?? 'No Phone Number' }}</p>
                                    <p class="mb-0 text-gray-600">
                                        <span class="font-medium">Reserved Location:</span> {{ $location }}
                                    </p>
                                </div>
                            </li>
                        </a>
                    @elseif($result['type'] === 'testimonial')
                        <a href="{{ route('admin.testimonial', ['testimonialID' => $result['testimonialID']]) }}"
                            class="text-decoration-none">
                            <li class="list-group-item p-4 border-b hover:bg-gray-100 cursor-pointer flex items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ $imageSrc }}" alt="{{ $location }}"
                                        class="w-16 h-16 rounded-lg object-cover border border-gray-200 shadow-md rounded" />
                                </div>
                                <div class="ml-4">
                                    <h5 class="mb-1 font-semibold text-gray-800">
                                        {{ $result['name'] ?? 'Unnamed Reservation' }}</h5>
                                    <p class="mb-1 text-gray-500">{{ $result['name'] ?? 'Anonymous' }} |
                                        {{ $result['email'] ?? 'No Email' }}</p>
                                    <p class="mb-0 text-gray-600">
                                        <span class="font-bold block">Testimonial</span>
                                        <span class="text-clip italic">"{{ $result['testimonial'] }}"</span>
                                    </p>
                                </div>
                            </li>
                        </a>
                    @endif
                @endif
            @endforeach


        </ul>
    @elseif(isset($searchQuery) && $searchQuery !== '')
        <ul
            class="list-group m-0 p-0 bg-white shadow-lg border border-gray-200 rounded-lg mt-2 absolute w-full max-h-[400px] overflow-y-auto z-40">
            <!-- z-50 ensures the span remains on top of other
        elements within the scrollable container. -->
            <li class="list-group-item p-4 border-b hover:bg-gray-100 cursor-pointer flex items-center">
                No results found for "<b>{{ $searchQuery }}</b>"
            </li>

            <!-- Suggestion Prompt -->
            @if (empty($searchResults) && $suggestion)
                <li class="list-group-item p-4 border-b hover:bg-gray-100 cursor-pointer flex items-center">
                    Did you mean:

                    <span class="text-indigo-500 font-semibold">{{ $suggestion }}</span>?

                    If so, try your search again with this suggestion
                </li>
            @endif
            <!-- / Suggestion Prompt -->

            <span class="block m-3 top-0 sticky z-50 bg-white p-3 float-start">

                <button type="button" wire:click="clearSearchResults"
                    class="float-start block btn btn-link mt-2 text-blue-600 hover:underline">Clear Results</button>
            </span>
        </ul>

    @endif

</div>
