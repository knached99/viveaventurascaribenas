@php
    $photos = is_string($reservation->trip->tripPhoto) ? json_decode($reservation->trip->tripPhoto, true) : $reservation->trip->tripPhoto;
    $firstPhoto = !empty($photos) ? asset($photos[0]) : asset('assets/images/booking_page_bg.webp');
@endphp
<x-authenticated-theme-layout>
    <div class="relative w-full mt-6 text-gray-700 bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <h5 class="mb-4 text-3xl font-semibold text-gray-900">
                <i class='bx bxs-user'></i> {{ $reservation->name }}'s Reservation Information
            </h5>
            <!-- Booking Location -->
            <div class="mb-6">
                <h6 class="text-lg font-medium text-blue-gray-800">
                    <i class='bx bxs-map'></i> Reserved Location
                    <a class="btn btn-primary mb-5 float-end"
                        href="{{ route('admin.trip', ['tripID' => $reservation->trip->tripID]) }}">View
                        Trip
                        Info</a>
                </h6>
                <p class="text-base font-light leading-relaxed mb-3">{{ $reservation->trip->tripLocation }}</p>


                <div class="block">
                    <img src="{{ $firstPhoto }}" alt="Location Image"
                        class="w-full max-w-sm h-auto rounded-lg shadow-md object-cover" />
                </div>
            </div>



            <!-- Booking Time -->
            <div class="mb-6">
                <h6 class="text-lg font-medium text-blue-gray-800">
                    <i class='bx bxs-calendar'></i> Reserved At
                </h6>
                <p class="text-base font-light leading-relaxed">
                    {{ date('F jS, Y \a\t g:iA', strtotime($reservation->created_at)) }}
                </p>
            </div>

            {{-- <div class="row">
                <div class="col-md-6">
                    <!-- Payment Status -->
                    <div class="mb-6">
                        <h6 class="text-lg font-medium text-blue-gray-800">
                            <i class="fa-regular fa-circle-question"></i> Quick Note
                        </h6>
                        <p>Once the status of the trip is changed, this customer will be notified via email</p>
                    </div>
                </div> <!-- End Col -->


            </div>
            <!-- End Row --> --}}


            <!-- Contact Information -->
            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-envelope'></i> Email
                    </h6>
                    <p class="text-base font-light leading-relaxed mt-2">{{ $reservation->email }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-phone'></i> Phone
                    </h6>
                    <p class="text-base font-light leading-relaxed mt-2">{{ $reservation->phone_number }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm col-span-2">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class="fa-solid fa-location-pin"></i> Customer's Address
                    </h6>
                    <p class="text-base font-light leading-relaxed mt-2">
                        {{ $reservation->address_line_1 }}<br>
                        {{ $reservation->address_line_2 }}<br>
                        {{ $reservation->city }}, {{ $reservation->state }} {{ $reservation->zip_code }}
                    </p>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg shadow-sm col-span-2">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bx-calendar-heart'></i> Preferred Travel Dates
                    </h6>
                    <p class="text-base font-light leading-relaxed mt-2">
                        {{ date('F jS, Y', strtotime($reservation->preferred_start_date)) }} -
                        {{ date('F jS, Y', strtotime($reservation->preferred_end_date)) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Optional Buttons Section -->
        <div class="flex items-center justify-between p-6 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            <!-- Add buttons or additional actions here if needed -->
        </div>
    </div>
</x-authenticated-theme-layout>
