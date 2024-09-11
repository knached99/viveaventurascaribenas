<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['popularTrips']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['popularTrips']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4" style="font-weight: 900;">Most Popular Attractions</h2>
            </div>
        </div>
        <div class="row">
            <?php $__currentLoopData = $popularTrips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php

                    $tripPhotos = isset($trip['image']) ? json_decode($trip['image'], true) : [];
                ?>

                <div class="col-md-3 ftco-animate">
                    <div class="project-destination">
                        <a href="<?php echo e(url('/landing/destination/' . $trip['id'])); ?>" class="img"
                            style="background-image: url(<?php echo e(!empty($tripPhotos) ? $tripPhotos[0] : asset('assets/images/image_placeholder.jpg')); ?>);">
                            <div class="text">
                                <h3 style="font-weight: 900; color: #f8fafc;"><?php echo e($trip['name']); ?></h3>
                                <span><?php echo e($trip['count']); ?> bookings</span>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php /**PATH /Applications/MAMP/htdocs/viveaventurascaribenas/resources/views/components/travelcomponents/most-popular-attractions.blade.php ENDPATH**/ ?>