<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['trips', 'mostPopularTripId']));

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

foreach (array_filter((['trips', 'mostPopularTripId']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<section class="ftco-section ftco-no-pt">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4" style="font-weight: 900;">Available Bookings</h2>
            </div>
        </div>
        <div class="row">
            <?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Decode tripPhoto if it exists
                    $tripPhotos = isset($trip->tripPhoto) ? json_decode($trip->tripPhoto, true) : [];
                ?>
                <div class="col-md-4 col-sm-6 ftco-animate">
                    <div class="project-wrap card">
                        <div id="carouselExampleControls<?php echo e($loop->index); ?>" class="carousel slide" data-bs-interval="false">
                            <div class="carousel-inner fixed-carousel-height">
                                <?php if(!empty($tripPhotos)): ?>
                                    <?php $__currentLoopData = $tripPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                            <img src="<?php echo e($photo); ?>" class="d-block w-100 card-img-top"
                                                alt="Photo">
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="carousel-item active">
                                        <img src="<?php echo e(asset('assets/images/image_placeholder.jpg')); ?>"
                                            class="d-block w-100 card-img-top" alt="Placeholder">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleControls<?php echo e($loop->index); ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleControls<?php echo e($loop->index); ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <?php if($trip->tripID == $mostPopularTripId): ?>
                                <div class="popular-badge">
                                    <img src="<?php echo e(asset('assets/theme_assets/assets/img/popularBadge.webp')); ?>"
                                        alt="Popular" />
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="text p-4 card-body">
                            <span class="price">$<?php echo e(number_format($trip->tripPrice, 2)); ?>/person</span>
                            <span
                                class="days"><?php echo e(\Carbon\Carbon::parse($trip->tripStartDate)->diffInDays($trip->tripEndDate)); ?>

                                Days</span>
                            <h3><a
                                    href="<?php echo e(route('landing.destination', ['tripID' => $trip->tripID])); ?>"><?php echo e($trip->tripLocation); ?></a>
                            </h3>
                            <?php switch($trip->tripAvailability):
                                case ('available'): ?>
                                    <span class="success-badge"><?php echo e($trip->tripAvailability); ?></span>
                                <?php break; ?>

                                <?php case ('coming soon'): ?>
                                    <span class="warning-badge"><?php echo e($trip->tripAvailability); ?></span>
                                <?php break; ?>

                                <?php case ('unavailable'): ?>
                                    <span class="danger-badge"><?php echo e($trip->tripAvailability); ?></span>
                                <?php break; ?>
                            <?php endswitch; ?>
                            <ul>
                                <li>
                                    <img src="<?php echo e(asset('assets/images/calendar.png')); ?>"
                                        style="width: 20px; height: 20px; margin: 5px;" />
                                    <?php echo e(date('F jS, Y', strtotime($trip->tripStartDate))); ?> -
                                    <?php echo e(date('F jS, Y', strtotime($trip->tripEndDate))); ?>

                                </li>
                                <?php switch($trip->tripLandscape):
                                    case ('Beach'): ?>
                                        <li><img src="<?php echo e(asset('assets/images/beach.png')); ?>"
                                                style="width: 40px; height: 40px; margin: 5px;" /> <?php echo e($trip->tripLandscape); ?>

                                        </li>
                                    <?php break; ?>

                                    <?php case ('City'): ?>
                                        <li><img src="<?php echo e(asset('assets/images/buildings.png')); ?>"
                                                style="width: 40px; height: 40px; margin: 5px;" /><?php echo e($trip->tripLandscape); ?>

                                        </li>
                                    <?php break; ?>

                                    <?php case ('Country Side'): ?>
                                        <li><img src="<?php echo e(asset('assets/images/farm.png')); ?>"
                                                style="width: 40px; height: 40px; margin: 5px;" /><?php echo e($trip->tripLandscape); ?>

                                        </li>
                                    <?php break; ?>

                                    <?php case ('Mountainous'): ?>
                                        <li><img src="<?php echo e(asset('assets/images/mountain.png')); ?>"
                                                style="width: 40px; height: 40px; margin: 5px;" /><?php echo e($trip->tripLandscape); ?>

                                        </li>
                                    <?php break; ?>

                                    <?php case ('Forested'): ?>
                                        <li><img src="<?php echo e(asset('assets/images/forest.png')); ?>"
                                                style="width: 40px; height: 40px; margin: 5px;" /><?php echo e($trip->tripLandscape); ?>

                                        </li>
                                    <?php break; ?>
                                <?php endswitch; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/components/travelcomponents/available-bookings.blade.php ENDPATH**/ ?>