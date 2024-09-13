
    <?php if (isset($component)) { $__componentOriginal4f7fc9343c4d60d581e41513358e7237 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4f7fc9343c4d60d581e41513358e7237 = $attributes; } ?>
<?php $component = App\View\Components\Admincomponents\Header::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admincomponents.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Admincomponents\Header::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4f7fc9343c4d60d581e41513358e7237)): ?>
<?php $attributes = $__attributesOriginal4f7fc9343c4d60d581e41513358e7237; ?>
<?php unset($__attributesOriginal4f7fc9343c4d60d581e41513358e7237); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4f7fc9343c4d60d581e41513358e7237)): ?>
<?php $component = $__componentOriginal4f7fc9343c4d60d581e41513358e7237; ?>
<?php unset($__componentOriginal4f7fc9343c4d60d581e41513358e7237); ?>
<?php endif; ?>


        <div>
        
            <?php echo e($slot); ?>

        </div>
   
    <?php if (isset($component)) { $__componentOriginal70870facdc67497e8de086c2e03f2848 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal70870facdc67497e8de086c2e03f2848 = $attributes; } ?>
<?php $component = App\View\Components\Admincomponents\Footer::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admincomponents.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Admincomponents\Footer::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal70870facdc67497e8de086c2e03f2848)): ?>
<?php $attributes = $__attributesOriginal70870facdc67497e8de086c2e03f2848; ?>
<?php unset($__attributesOriginal70870facdc67497e8de086c2e03f2848); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal70870facdc67497e8de086c2e03f2848)): ?>
<?php $component = $__componentOriginal70870facdc67497e8de086c2e03f2848; ?>
<?php unset($__componentOriginal70870facdc67497e8de086c2e03f2848); ?>
<?php endif; ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/layouts/theme.blade.php ENDPATH**/ ?>