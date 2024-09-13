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

<section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url(<?php echo e(asset('assets/images/bg_1.jpg')); ?>);"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <h1 class="mb-3 bread" style="font-weight:900;">Places to Travel</h1>
                <p class="breadcrumbs"><span class="mr-2"><a href="/">Home <i
                                class="ion-ios-arrow-forward"></i></a></span> <span>Destinations <i
                            class="ion-ios-arrow-forward"></i></span></p>
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
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/landing/destinations.blade.php ENDPATH**/ ?>