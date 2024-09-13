<?php if (isset($component)) { $__componentOriginal56ddcc04428819a89a8ebdc544c9ab71 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal56ddcc04428819a89a8ebdc544c9ab71 = $attributes; } ?>
<?php $component = App\View\Components\Travelcomponents\Header::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('travelcomponents.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Travelcomponents\Header::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal56ddcc04428819a89a8ebdc544c9ab71)): ?>
<?php $attributes = $__attributesOriginal56ddcc04428819a89a8ebdc544c9ab71; ?>
<?php unset($__attributesOriginal56ddcc04428819a89a8ebdc544c9ab71); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal56ddcc04428819a89a8ebdc544c9ab71)): ?>
<?php $component = $__componentOriginal56ddcc04428819a89a8ebdc544c9ab71; ?>
<?php unset($__componentOriginal56ddcc04428819a89a8ebdc544c9ab71); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginalf09c4a22700b1a3156f4560fb6684300 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf09c4a22700b1a3156f4560fb6684300 = $attributes; } ?>
<?php $component = App\View\Components\Travelcomponents\Navbar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('travelcomponents.navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Travelcomponents\Navbar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf09c4a22700b1a3156f4560fb6684300)): ?>
<?php $attributes = $__attributesOriginalf09c4a22700b1a3156f4560fb6684300; ?>
<?php unset($__attributesOriginalf09c4a22700b1a3156f4560fb6684300); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf09c4a22700b1a3156f4560fb6684300)): ?>
<?php $component = $__componentOriginalf09c4a22700b1a3156f4560fb6684300; ?>
<?php unset($__componentOriginalf09c4a22700b1a3156f4560fb6684300); ?>
<?php endif; ?>

<div class="hero-wrap js-fullheight" style="background-image: url('<?php echo e(asset('assets/images/contactImage.jpg')); ?>');"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center"
            data-scrollax-parent="true">
            <div class="col-md-9 text text-center ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
                
                <p class="caps" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Travel to the any
                    corner of the world, without going around in circles</p>
                <h1 style="font-weight: 900;" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Make Your
                    Tour
                    Amazing With Us</h1>
            </div>
        </div>
    </div>
</div>



<section class="ftco-section services-section bg-light">
    <div class="container">
        <div class="row d-flex">
            <div class="col-md-6 order-md-last heading-section pl-md-5 ftco-animate">
                <h2 class="mb-4">It's time to start your adventure</h2>
                <p>Welcome to Vive Aventuras Caribeñas, your go-to travel agency for exciting
                    and affordable pre-packaged trips to
                    beautiful destinations in the Caribbean and beyond.
                    Our mission is to provide you with unforgettable travel experiences at prices that won’t break the
                    bank.</p>
                <p><a href="<?php echo e(route('destinations')); ?>" class="btn btn-primary py-3 px-4">Book a Travel</a></p>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="flaticon-paragliding"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Activities</h3>
                                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="flaticon-route"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Travel Arrangements</h3>
                                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="flaticon-tour-guide"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Private Guide</h3>
                                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="flaticon-map"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Location Manager</h3>
                                <p>A small river named Duden flows by their place and supplies it with the necessary</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-counter img" id="section-counter">
    <div class="container">
        <div class="row d-flex">
            <div class="col-md-6 d-flex">
                <div class="img d-flex align-self-stretch"
                    style="background-image:url(<?php echo e(asset('assets/images/tropicalParaglider.avif')); ?>);"></div>
            </div>
            <div class="col-md-6 pl-md-5 py-5">
                <div class="row justify-content-start pb-3">
                    <div class="col-md-12 heading-section ftco-animate">
                        <h2 class="mb-4">Make Your Tour Memorable and Safe With Us</h2>
                        <p>At Vive Aventuras Caribeñas, we prioritize your safety and enjoyment above all else. Our
                            experienced guides and carefully curated itineraries ensure that every moment of your tour
                            is not only memorable but also safe, allowing you to fully immerse yourself in the adventure
                            without any worries.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center mb-4">
                            <div class="text">
                                <strong class="number" data-number="300">0</strong>
                                <span>Successful Tours</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center mb-4">
                            <div class="text">
                                <strong class="number" data-number="24000">0</strong>
                                <span>Happy Tourist</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center mb-4">
                            <div class="text">
                                <strong class="number" data-number="200">0</strong>
                                <span>Place Explored</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Most Popular Attractions -->
<?php if(!empty($popularTrips)): ?>
    <?php if (isset($component)) { $__componentOriginal0627daa701b6cee6f782a338ef3c823f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0627daa701b6cee6f782a338ef3c823f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.travelcomponents.most-popular-attractions','data' => ['popularTrips' => $popularTrips]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('travelcomponents.most-popular-attractions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['popularTrips' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($popularTrips)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0627daa701b6cee6f782a338ef3c823f)): ?>
<?php $attributes = $__attributesOriginal0627daa701b6cee6f782a338ef3c823f; ?>
<?php unset($__attributesOriginal0627daa701b6cee6f782a338ef3c823f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0627daa701b6cee6f782a338ef3c823f)): ?>
<?php $component = $__componentOriginal0627daa701b6cee6f782a338ef3c823f; ?>
<?php unset($__componentOriginal0627daa701b6cee6f782a338ef3c823f); ?>
<?php endif; ?>
<?php else: ?>
<?php endif; ?>
<!-- / Most Popular Attractions -->

<!-- Available Bookings Component -->
<?php if(!empty($trips) || !empty($mostPopularTripId)): ?>
    <?php if (isset($component)) { $__componentOriginal4325d5933371eb6b320fc88c0339fc4d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4325d5933371eb6b320fc88c0339fc4d = $attributes; } ?>
<?php $component = App\View\Components\Travelcomponents\AvailableBookings::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('travelcomponents.available-bookings'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Travelcomponents\AvailableBookings::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['trips' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($trips),'mostPopularTripId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mostPopularTripId)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4325d5933371eb6b320fc88c0339fc4d)): ?>
<?php $attributes = $__attributesOriginal4325d5933371eb6b320fc88c0339fc4d; ?>
<?php unset($__attributesOriginal4325d5933371eb6b320fc88c0339fc4d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4325d5933371eb6b320fc88c0339fc4d)): ?>
<?php $component = $__componentOriginal4325d5933371eb6b320fc88c0339fc4d; ?>
<?php unset($__componentOriginal4325d5933371eb6b320fc88c0339fc4d); ?>
<?php endif; ?>
<?php else: ?>
<?php endif; ?>
<!-- End Available Bookings Component -->


<!-- Start Testimonials -->
<?php if(!empty($testimonials)): ?>
    <?php if (isset($component)) { $__componentOriginal3b8a327a38443bf8c19446d49d3927b0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b8a327a38443bf8c19446d49d3927b0 = $attributes; } ?>
<?php $component = App\View\Components\Travelcomponents\Testimonials::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('travelcomponents.testimonials'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Travelcomponents\Testimonials::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['testimonials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($testimonials)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b8a327a38443bf8c19446d49d3927b0)): ?>
<?php $attributes = $__attributesOriginal3b8a327a38443bf8c19446d49d3927b0; ?>
<?php unset($__attributesOriginal3b8a327a38443bf8c19446d49d3927b0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b8a327a38443bf8c19446d49d3927b0)): ?>
<?php $component = $__componentOriginal3b8a327a38443bf8c19446d49d3927b0; ?>
<?php unset($__componentOriginal3b8a327a38443bf8c19446d49d3927b0); ?>
<?php endif; ?>
<?php else: ?>
<?php endif; ?>
<!-- End Testimonials -->

<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('forms.testimonial-form', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-641951433-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<!-- Testimonial Submission -->
<?php if (isset($component)) { $__componentOriginalee874b062dd641847c776b25c7235ce3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee874b062dd641847c776b25c7235ce3 = $attributes; } ?>
<?php $component = App\View\Components\Travelcomponents\Footer::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('travelcomponents.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Travelcomponents\Footer::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee874b062dd641847c776b25c7235ce3)): ?>
<?php $attributes = $__attributesOriginalee874b062dd641847c776b25c7235ce3; ?>
<?php unset($__attributesOriginalee874b062dd641847c776b25c7235ce3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee874b062dd641847c776b25c7235ce3)): ?>
<?php $component = $__componentOriginalee874b062dd641847c776b25c7235ce3; ?>
<?php unset($__componentOriginalee874b062dd641847c776b25c7235ce3); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/landing/home.blade.php ENDPATH**/ ?>