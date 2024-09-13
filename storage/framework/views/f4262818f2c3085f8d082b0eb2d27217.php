  <?php if (isset($component)) { $__componentOriginalad6b5500a6f33bde38d64c096c381e41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad6b5500a6f33bde38d64c096c381e41 = $attributes; } ?>
<?php $component = App\View\Components\ThemeLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ThemeLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
  <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php if (isset($component)) { $__componentOriginalc24ff14429f1e84f7d7e53d8a2242dea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc24ff14429f1e84f7d7e53d8a2242dea = $attributes; } ?>
<?php $component = App\View\Components\Admincomponents\Sidebar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admincomponents.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Admincomponents\Sidebar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc24ff14429f1e84f7d7e53d8a2242dea)): ?>
<?php $attributes = $__attributesOriginalc24ff14429f1e84f7d7e53d8a2242dea; ?>
<?php unset($__attributesOriginalc24ff14429f1e84f7d7e53d8a2242dea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc24ff14429f1e84f7d7e53d8a2242dea)): ?>
<?php $component = $__componentOriginalc24ff14429f1e84f7d7e53d8a2242dea; ?>
<?php unset($__componentOriginalc24ff14429f1e84f7d7e53d8a2242dea); ?>
<?php endif; ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

           <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('layout.nav', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2576350539-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>


          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
             <?php echo e($slot); ?>

            </div>
            <!-- / Content -->

           <!-- FOOTER --> 
           
           
    <!-- / Layout wrapper -->

  
   <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad6b5500a6f33bde38d64c096c381e41)): ?>
<?php $attributes = $__attributesOriginalad6b5500a6f33bde38d64c096c381e41; ?>
<?php unset($__attributesOriginalad6b5500a6f33bde38d64c096c381e41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad6b5500a6f33bde38d64c096c381e41)): ?>
<?php $component = $__componentOriginalad6b5500a6f33bde38d64c096c381e41; ?>
<?php unset($__componentOriginalad6b5500a6f33bde38d64c096c381e41); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/layouts/authenticated-theme.blade.php ENDPATH**/ ?>