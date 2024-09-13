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
            <div class="message-box _failed">
                <h3>Hey There <?php echo e($name); ?>! </h3>
                <p>
                    <?php if(\Request::query('name')): ?>
                        It looks like you’ve decided not to proceed with your booking at this time. No worries—plans
                        change
                        and we completely understand.

                        If you have any questions or need further assistance, please don't hesitate to contact us. We’re
                        here to help with any future travel plans or queries you might have.

                        Thank you for visiting us, and we hope to see you again soon!

                        Best regards,
                        <b><?php echo e(config('app.name')); ?></b>
                        <a href="<?php echo e(route('contact')); ?>" style="text-decoration: underline;">Contact us</a>
                    <?php else: ?>
                        You have already booked the trip! Please check your email for more details.
                    <?php endif; ?>

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
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/booking/cancel.blade.php ENDPATH**/ ?>