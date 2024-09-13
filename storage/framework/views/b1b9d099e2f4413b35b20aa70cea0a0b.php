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
    <div class="row">
        <div class="card-body">
            <div class="col-sm-7">        
                <div class="m-3">
                    <a class="btn btn-primary text-white w-100 w-sm-50" href="<?php echo e(route('admin.create-trip')); ?>">
                        Create Trip
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Trips Table -->
    <?php if(!$trips->isEmpty()): ?>
        <div class="card shadow-sm bg-white rounded">
            <h5 class="m-3">Here are all of your bookings</h5>

            <?php if(session('trip_deleted')): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo e(session('trip_deleted')); ?>

                </div>
            <?php endif; ?> 

            <div class="table-responsive">
                <?php if (isset($component)) { $__componentOriginalcd55a0abb060df4b79f4ed49b3f01f8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd55a0abb060df4b79f4ed49b3f01f8e = $attributes; } ?>
<?php $component = App\View\Components\Admincomponents\AllTrips::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admincomponents.all-trips'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Admincomponents\AllTrips::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['trips' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($trips)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcd55a0abb060df4b79f4ed49b3f01f8e)): ?>
<?php $attributes = $__attributesOriginalcd55a0abb060df4b79f4ed49b3f01f8e; ?>
<?php unset($__attributesOriginalcd55a0abb060df4b79f4ed49b3f01f8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd55a0abb060df4b79f4ed49b3f01f8e)): ?>
<?php $component = $__componentOriginalcd55a0abb060df4b79f4ed49b3f01f8e; ?>
<?php unset($__componentOriginalcd55a0abb060df4b79f4ed49b3f01f8e); ?>
<?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <h3 class="text-secondary">No Available Trips. Go ahead and create one now</h3>
    <?php endif; ?>
    <!-- End Trips Table -->
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
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/admin/all-trips.blade.php ENDPATH**/ ?>