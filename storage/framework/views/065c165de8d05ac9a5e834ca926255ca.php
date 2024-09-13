<?php if (isset($component)) { $__componentOriginaldb6c893c91deabe715f4e95492242b1a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldb6c893c91deabe715f4e95492242b1a = $attributes; } ?>
<?php $component = App\View\Components\AuthenticatedThemeLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('authenticated-theme-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AuthenticatedThemeLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('forms.edit-trip-form', ['trip' => $trip,'totalNetCost' => $totalNetCost,'grossProfit' => $grossProfit,'netProfit' => $netProfit]);

$__html = app('livewire')->mount($__name, $__params, 'lw-2600013904-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldb6c893c91deabe715f4e95492242b1a)): ?>
<?php $attributes = $__attributesOriginaldb6c893c91deabe715f4e95492242b1a; ?>
<?php unset($__attributesOriginaldb6c893c91deabe715f4e95492242b1a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldb6c893c91deabe715f4e95492242b1a)): ?>
<?php $component = $__componentOriginaldb6c893c91deabe715f4e95492242b1a; ?>
<?php unset($__componentOriginaldb6c893c91deabe715f4e95492242b1a); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/admin/trip.blade.php ENDPATH**/ ?>