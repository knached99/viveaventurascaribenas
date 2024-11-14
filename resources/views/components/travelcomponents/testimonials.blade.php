@props(['testimonials'])
<main>
    <h2 class="text-dark" style="font-weight: 900;">Travel Stories from Our Adventurers</h2>
    <h4>Discover firsthand accounts of unforgettable travel experiences and explore the adventures that have shaped our
        travelers' lives.</h4>
    <div class="slider">
        <div class="slide-row" id="slide-row">
            @foreach ($testimonials as $testimony)
                <div class="slide-col">
                    <div class="content">
                        <blockquote class="testimonial-text">
                            <p><i class='bx bxs-quote-alt-left'></i>{{ $testimony->testimonial }}<i
                                    class='bx bxs-quote-alt-right'></i></p>
                        </blockquote>
                        <h2>{{ $testimony->name }}</h2>
                        <div class="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="bx bxs-star star-icon {{ $i <= $testimony->trip_rating ? 'text-warning' : 'text-secondary' }}"></i>
                            @endfor
                        </div>
                        <div class="inline-block">

                            <span class="m-3 text-secondary"><i class='bx bx-map' style="font-size: 30px;"></i>
                                <a style="border-bottom: 1px solid blue;"
                                    href="{{ route('destination', ['slug' => $testimony->trip->slug]) }}">
                                    {{ $testimony->trip->tripLocation }}</span>
                            </a>
                            <span class="m-3 text-secondary"><i class='bx bx-calendar'
                                    style="font-size: 30px;"></i>{{ date('F jS, Y', strtotime($testimony->created_at)) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="nav-btn prev-btn"><i class="fa-solid fa-left-long"></i></button>
        <button class="nav-btn next-btn"><i class="fa-solid fa-right-long"></i></button>
    </div>
    <div class="indicator">
        @for ($i = 0; $i < count($testimonials); $i++)
            <span class="btn {{ $i === 0 ? 'active' : '' }}"></span>
        @endfor
    </div>
</main>
