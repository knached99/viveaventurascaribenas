<x-authenticated-theme-layout>
    <div class="relative w-full mt-6 text-gray-700 bg-white shadow-md bg-clip-border rounded-xl ">
        <div class="p-6">

            <h5 class="block mb-2 font-sans text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                {{ $testimonial->name }}'s Testimonial
            </h5>

            @if (session('testimonial_approved'))
                <div class="flex items-center bg-emerald-500 text-white text-sm font-bold px-4 py-3" role="alert">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                    </svg>
                    <p>{{ session('testimonial_approved') }}</p>
                </div>
            @elseif(session('testimonial_approve_error'))
                <div class="flex items-center bg-red-500 text-white text-sm font-bold px-4 py-3" role="alert">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                    </svg>
                    <p>{{ session('testimonial_approve_error') }}</p>
                </div>
            @endif
            <h6>Status:
                @switch($testimonial->testimonial_approval_status)
                    @case('Pending')
                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-indigo-900 dark:text-indigo-300">{{ $testimonial->testimonial_approval_status }}</span>
                    @break

                    @case('Approved')
                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{ $testimonial->testimonial_approval_status }}</span>
                    @break

                    @case('Declined')
                        <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">{{ $testimonial->testimonial_approval_status }}</span>
                    @break
                @endswitch
            </h6>
            <p class="block font-sans text-xl antialiased font-light leading-relaxed text-inherit">
                “{{ $testimonial->testimonial }}”
            </p>
        </div>

        <div class="flex items-center justify-between p-6">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <i class='bx bx-map text-lg'></i>
                    <span class="ml-2">{{ $testimonial->trip ? $testimonial->trip->tripLocation : 'Location not available' }}</span>
                </div>
                <div class="flex items-center">
                    <i class='bx bx-calendar text-lg'></i>
                    <span class="ml-2">{{ date('F, Y', strtotime($testimonial->trip_date)) }}</span>
                </div>
            </div>

            @if (!in_array($testimonial->testimonial_approval_status, ['Approved', 'Declined']))
                <div class="flex space-x-4">
                    <form method="post" action="{{ route('admin.testimonial.approveTestimonial', ['testimonialID' => $testimonial->testimonialID]) }}">
                        @csrf
                        @method('PUT')
                        <button class="flex items-center gap-2 px-4 py-2 font-sans text-xs font-bold text-center text-gray-800 uppercase align-middle transition-all rounded-lg select-none disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none hover:bg-emerald-400 hover:text-white active:bg-emerald-500/20" type="submit">
                            Approve
                        </button>
                    </form>

                    <form method="post" action="{{ route('admin.testimonial.declineTestimonial', ['testimonialID' => $testimonial->testimonialID]) }}">
                        @csrf
                        @method('PUT')
                        <button class="flex items-center gap-2 px-4 py-2 font-sans text-xs font-bold text-center text-gray-800 uppercase align-middle transition-all rounded-lg select-none disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none hover:bg-red-400 hover:text-white active:bg-red-500/20" type="submit">
                            Decline
                        </button>
                    </form>
                </div>
            @endif

            <form method="post" action="{{ route('admin.testimonial.delete', ['testimonialID' => $testimonial->testimonialID]) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center gap-2 px-4 py-2 font-sans text-xs font-bold text-center text-gray-800 uppercase align-middle transition-all rounded-lg select-none disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none hover:bg-red-400 hover:text-white active:bg-red-500/20">
                    <i class='bx bx-trash-alt'></i>
                </button>
            </form>
        </div>
    </div>
</x-authenticated-theme-layout>
