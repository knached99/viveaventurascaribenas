<footer class="ftco-footer bg-bottom" style="background-image: url({{ asset('assets/images/footer-bg.jpg') }});">
    <div class="container">
        <div class="row mb-5">
            {{-- <div class="col-md">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Vacation</h2>
                    <p>Beyond the Ordinary. Into the Extraordinary.</p>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                        <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                    </ul>
                </div>
            </div> --}}
            <div class="col-md">
                <div class="ftco-footer-widget mb-4 ml-md-5">
                    <h2 class="ftco-heading-2">Information</h2>
                    <ul class="list-unstyled">
                        <li><a href="{{route('vacation-agreement')}}" class="py-2 d-block">Vacation Agreement</a></li>
                        <li><a href="{{ route('privacy') }}" class="py-2 d-block">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}" class="py-2 d-block">Terms and Conditions</a>
                        </li>
                        <li><a href="tel:+1(607)-373-2208" class="py-2 d-block">Call Us</a></li>
                    </ul>
                </div>
            </div>
      
            <div class="col-md">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Have Any Questions?</h2>
                    <div class="block-23 mb-3">
                        <ul>
                            <li><span class="icon icon-map-marker"></span><span class="text">New York, USA</span>
                            </li>
                          
                            <li><a href="{{ route('contact') }}"><span class="icon icon-envelope"></span><span
                                        class="text">Contact Us</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">

                <p style="visibility: hidden;">
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script> All rights reserved | This template is made with <i
                        class="icon-heart color-danger" aria-hidden="true"></i> by <a href="https://colorlib.com"
                        target="_blank">Colorlib</a>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Icons are not owned by {{ config('app.name') }} The 404 illustration is owned by <a
                        href="https://www.vecteezy.com/vector-art/26776449-asian-man-looking-on-plane-error-404-flash-message-empty-state-ui-design-page-not-found-popup-cartoon-image-vector-flat-illustration-concept-on-white-background"
                        _target="blank" rel="noreferrer noopener ">vecteezy</a>

                    The 503 illustration is owned by <a
                        href="https://www.freepik.com/premium-vector/travel-around-world-online-journey-couple-is-planning-their-trip-choosing-best-route-travel-agency-tour-abroad-color-vector-illustration-flat-style_61502026.htm"
                        _target="blank" rel="noreferrer noopener">Freepik</a>
                </p>
            </div>
        </div>
    </div>
</footer>



<!-- loader -->
<div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
        <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
        <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4"
            stroke-miterlimit="10" stroke="#F96D00" />
    </svg></div>


<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-migrate-3.0.1.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="{{ asset('assets/js/jquery.easing.1.3.js') }}"></script>
<script src="{{ asset('assets/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.stellar.min.js') }}"></script>
<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script src="{{ asset('assets/js/jquery.animateNumber.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/js/scrollax.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/carousel.js') }}"></script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btns = document.querySelectorAll(".btn");
        const slideRow = document.getElementById("slide-row");
        const prevBtn = document.querySelector(".prev-btn");
        const nextBtn = document.querySelector(".next-btn");
        let currentIndex = 0;
        const totalSlides = btns.length;

        function updateSlide() {
            const slideWidth = document.querySelector(".slide-col").offsetWidth;
            slideRow.style.transform = `translateX(${-currentIndex * slideWidth}px)`;

            btns.forEach((btn, index) => {
                btn.classList.toggle("active", index === currentIndex);
            });
        }

        function goToSlide(index) {
            currentIndex = (index + totalSlides) % totalSlides;
            updateSlide();
        }

        btns.forEach((btn, index) => {
            btn.addEventListener("click", () => {
                goToSlide(index);
            });
        });

        prevBtn.addEventListener("click", () => {
            goToSlide(currentIndex - 1);
        });

        nextBtn.addEventListener("click", () => {
            goToSlide(currentIndex + 1);
        });

        window.addEventListener("resize", () => {
            updateSlide();
        });

        // Initialize slider position
        updateSlide();
    });
</script>
@if (\Route::currentRouteName() === '/' || \Route::currentRouteName() === 'destination')
    <!-- Slider on booking images -->
    <script>
        var swiper = new Swiper('.swiper-container', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            slidesPerView: 1,
            spaceBetween: 10,
        });
    </script>
@endif

@if (\Route::currentRouteName() === '/' || \Route::currentRouteName() === 'destination')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const carouselElement = document.querySelector("#testimonialsCarousel");
            const prevBtn = document.querySelector(".carousel-control-prev");
            const nextBtn = document.querySelector(".carousel-control-next");

            // Initialize Bootstrap carousel if it exists
            if (carouselElement) {
                const carousel = new bootstrap.Carousel(carouselElement);

                // Attach functionality to custom carousel control buttons
                prevBtn.addEventListener("click", () => {
                    carousel.prev(); // Move to the previous slide
                });

                nextBtn.addEventListener("click", () => {
                    carousel.next(); // Move to the next slide
                });
            }
        });
    </script>


    <!-- Color Extraction Algorithm using ColorTheif -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.2/color-thief.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const colorThief = new ColorThief();
        const carouselElement = document.getElementById('carouselExample');
        const sectionElement = document.querySelector('.trip-section');

        function updateBackgroundColor() {
            const activeItem = carouselElement.querySelector('.carousel-item .active img');
            if (activeItem) {
                const img = new Image();
                img.crossOrigin = 'Anonymous'; // To avoid CORS issues
                img.src = activeItem.src;

                img.onload = () => {
                    // Extract dominant and second dominant colors
                    const dominantColor = colorThief.getColor(img);
                    const palette = colorThief.getPalette(img, 6);
                    const secondColor = palette[1] || dominantColor;

                    // Create gradient background
                    const dominantColorRgb = `rgb(${dominantColor.join(',')})`;
                    const fadeColor = `rgba(${secondColor.join(',')}, 0.3)`;
                    const backgroundColor = `linear-gradient(to bottom, ${dominantColorRgb}, ${fadeColor})`;

                    // Apply background color with smooth transition
                    sectionElement.style.transition = 'background 0.5s ease';
                    sectionElement.style.background = backgroundColor;
                };

                img.onerror = () => {
                    console.error('Failed to load image for color extraction.');
                    sectionElement.style.background = 'linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(255, 255, 255, 0.3))';
                };
            }
        }

        updateBackgroundColor(); // Initial call
        carouselElement.addEventListener('slid.bs.carousel', updateBackgroundColor);
    });
</script> --}}
@endif

@livewireScripts

</body>

</html>
