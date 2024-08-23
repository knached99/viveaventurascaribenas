<x-authenticated-theme-layout>

    <!-- Testimonials Table -->
    @if (!$testimonials->isEmpty())
        <div class="w-full">
            <div class="card shadow-sm bg-white rounded" style="width: 100%; margin: 0;">
                <h5 class="m-3">Here are all of your testimonials</h5>

                @if (session('testimonial_deleted'))
                    <div class="alert alert-success" role="alert">
                        {{ session('testimonial_deleted') }}
                    </div>
                @endif

                <x-admincomponents.all-testimonials :testimonials="$testimonials" />
            </div>
        </div>
    @else
        <h3 class="text-secondary">No Testimonials Submitted Yet</h3>
    @endif

</x-authenticated-theme-layout>
