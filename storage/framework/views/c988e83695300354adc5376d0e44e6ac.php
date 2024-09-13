<?php
    $heading = $trip->tripAvailability === 'unavailable' ? 'Trip unavailable!' : 'Finish booking your trip to '.$trip->tripLocation;
    $message = $trip->tripAvailability === 'unavailable' ? 'You cannot book this trip as it is currently unavailable.' : 'Fill out the form to complete booking your trip!';
    $tripPhotos = json_decode($trip->tripPhoto, true);
    $firstPhotoURL = !empty($tripPhotos) ? asset($tripPhotos[0]) : asset('assets/images/booking_page_bg.webp');
?>

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

<div id="booking" class="section" style="background-image: url(<?php echo e($firstPhotoURL); ?>); background-size: cover; background-position: center; background-repeat: no-repeat;">
    
    <div class="section-center py-5">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-lg-7 text-center text-white mb-4">
                    <div class="booking-cta">
                        <h1 class="display-4"><?php echo e($heading); ?></h1>
                        <p class="lead"><?php echo e($message); ?></p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <!-- Form Start -->
                    <?php if($trip->tripAvailability === 'unavailable'): ?>
                        <div class="alert alert-warning">
                            This trip is unavailable at the moment.
                        </div>
                    <?php else: ?>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('forms.booking-form', ['tripID' => $tripID]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1003655831-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <?php endif; ?>
                    <!-- Form End -->
                </div>
            </div>
        </div>
    </div>
</div>

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
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/booking/booking.blade.php ENDPATH**/ ?>