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
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="message-box _success">
                <i class="fa fa-check-circle" aria-hidden="true"></i>
                <h3>Thank you, <?php echo e($customerName); ?>! </h3>
                <p>Your payment was successful. We've sent the confirmation details to
                    <?php echo e($customerEmail ?? 'your email'); ?> <br>
                </p>
            </div>
        </div>
    </div>
    <hr>



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
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/booking/success.blade.php ENDPATH**/ ?>