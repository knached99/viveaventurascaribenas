<x-authenticated-theme-layout>
    <div class="relative w-full mt-6 text-gray-700 bg-white shadow-md bg-clip-border rounded-xl ">
        <div class="p-6">

            <h5
                class="block mb-2 font-sans text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                {{ $testimonial->name }}'s Testimonial
            </h5>

            @if (session('testimonial_approved'))
                <div class="flex items-center bg-emerald-500 text-white text-sm font-bold px-4 py-3" role="alert">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path
                            d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                    </svg>
                    <p>{{ session('testimonial_approved') }}</p>
                </div>
            @elseif(session('testimonial_approve_error'))
                <div class="flex items-center bg-red-500 text-white text-sm font-bold px-4 py-3" role="alert">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path
                            d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                    </svg>
                    <p>{{ session('testimonial_approve_error') }}</p>
                </div>
            @endif
            <h6>Status:
                @switch($testimonial->testimonial_approval_status)
                    @case('Pending')
                        <span
                            class="bg-indigo-100 text-indigo-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-indigo-900 dark:text-indigo-300">{{ $testimonial->testimonial_approval_status }}</span>
                    @break

                    @case('Approved')
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{ $testimonial->testimonial_approval_status }}</span>
                    @break

                    @case('Declined')
                        <span
                            class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">{{ $testimonial->testimonial_approval_status }}</span>
                    @break
                @endswitch
            </h6>
            <p class="block font-sans text-xl antialiased font-light leading-relaxed text-inherit">
                “{{ $testimonial->testimonial }}”
            </p>
        </div>
        <div class="row">
            <div class="col-md-6 m-3">
                <i class='bx bx-map'></i> {{ $testimonial->trip_details }}
            </div>

            <div class="col-md-6 m-3">
                <i class='bx bx-calendar'></i> {{ date('F, Y', strtotime($testimonial->trip_date)) }}
            </div>
        </div>
        <div class="p-6 pt-0">
            @if (!in_array($testimonial->testimonial_approval_status, ['Approved', 'Declined']))
                <form method="post"
                    action="{{ route('admin.testimonial.approveTestimonial', ['testimonialID' => $testimonial->testimonialID]) }}">
                    @csrf
                    @method('PUT')

                    <!-- Flex container to align buttons horizontally -->
                    <div class="flex space-x-4">
                        <button
                            class="flex items-center gap-2 px-4 py-2 font-sans text-xs font-bold text-center text-gray-800 uppercase align-middle transition-all rounded-lg select-none disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none hover:bg-emerald-400 hover:text-white active:bg-emerald-500/20"
                            type="submit">
                            Approve
                        </button>
                </form>

                <form method="post"
                    action="{{ route('admin.testimonial.declineTestimonial', ['testimonialID' => $testimonial->testimonialID]) }}">
                    @csrf
                    @method('PUT')
                    <button
                        class="flex items-center gap-2 px-4 py-2 font-sans text-xs font-bold text-center text-gray-800 uppercase align-middle transition-all rounded-lg select-none disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none hover:bg-red-400 hover:text-white active:bg-red-500/20"
                        type="submit">
                        Decline
                    </button>
                </form>
            @endif

            <form method="post"
                action="{{ route('admin.testimonial.delete', ['testimonialID' => $testimonial->testimonialID]) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 font-sans text-xs font-bold text-center text-gray-800 uppercase align-middle transition-all rounded-lg select-none disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none hover:bg-red-400 hover:text-white active:bg-red-500/20">
                    <i class='bx bx-trash-alt '></i>
                </button>
        </div>

    </div>

    </div>

    {{-- <section class="relative isolate overflow-hidden bg-white px-6 py-24 sm:py-32 lg:px-8">
  <div class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.indigo.100),white)] opacity-20"></div>
  <div class="absolute inset-y-0 right-1/2 -z-10 mr-16 w-[200%] origin-bottom-left skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:mr-28 lg:mr-0 xl:mr-16 xl:origin-center"></div>
  <div class="mx-auto max-w-2xl lg:max-w-4xl">
    <h2 class="text-2xl fw-bold">{{$testimonial->name}}'s Testimonial</h2>
    <figure class="mt-10">
      <blockquote class="text-center text-xl font-semibold leading-8 text-gray-900 sm:text-2xl sm:leading-9">
        <p>“{{$testimonial->testimonial}}”</p>
      </blockquote>
      <div class="mt-10">
        <div class="mt-4 flex items-center justify-center space-x-3 text-base">
          <div class="font-semibold text-gray-900">Destination/Trip-Package: {{$testimonial->trip_details}}</div>
          
          <div class="text-gray-600">Trip Date: {{date('F, Y', strtotime($testimonial->trip_date))}}</div>
        </div>
      </div>
    </figure>
  </div>
</section> --}}
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
</x-authenticated-theme-layout>
