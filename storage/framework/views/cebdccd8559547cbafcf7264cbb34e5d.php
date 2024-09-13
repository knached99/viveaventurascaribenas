<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['trips']));

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

foreach (array_filter((['trips']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<table class="table dataTable">
    <thead>
        <tr>
            <th scope="col">Image</th>
            <th scope="col">Location</th>
            <th scope="col">Landscape</th>
            <th scope="col">Availability</th>
            <th scope="col">Start Date</th>
            <th scope="col">End Date</th>
            <th scope="col">Number of Days</th>
            <th scope="col">Price (per person)</th>
            <th scope="col">Created At</th>
            <th scope="col">Updated At</th>
            <th scope="col">View</th>
            <th scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $tripPhotos = json_decode($trip->tripPhoto, true);
            ?>
            <tr>
                <td>
                    <?php if($tripPhotos && is_array($tripPhotos) && count($tripPhotos) > 1): ?>
                        <div id="carousel-<?php echo e($trip->tripID); ?>" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-inner">
                                <?php $__currentLoopData = $tripPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                        <img src="<?php echo e($photo); ?>" class="d-block w-100" alt="Photo">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?php echo e($trip->tripID); ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?php echo e($trip->tripID); ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    <?php elseif($tripPhotos && is_array($tripPhotos) && count($tripPhotos) === 1): ?>
                        <img src="<?php echo e($tripPhotos[0]); ?>" class="img-thumbnail rounded" style="width: 100px; height: 100px;" />
                    <?php else: ?>
                        <img src="<?php echo e(asset('assets/images/image_placeholder.jpg')); ?>" class="img-thumbnail rounded" style="width: 100px; height: 100px;" />
                    <?php endif; ?>
                </td>
                <td><?php echo e($trip->tripLocation); ?></td>
                <?php switch($trip->tripLandscape):
                    case ('Beach'): ?>
                        <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="<?php echo e($trip->tripLandscape); ?>"><img src="<?php echo e(asset('assets/images/beach.png')); ?>" style="width: 40px; height: 40px; margin: 5px;" /></td>
                    <?php break; ?>
                    <?php case ('City'): ?>
                        <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="<?php echo e($trip->tripLandscape); ?>"><img src="<?php echo e(asset('assets/images/buildings.png')); ?>" style="width: 40px; height: 40px; margin: 5px;" /></td>
                    <?php break; ?>
                    <?php case ('Country Side'): ?>
                        <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="<?php echo e($trip->tripLandscape); ?>"><img src="<?php echo e(asset('assets/images/farm.png')); ?>" style="width: 40px; height: 40px; margin: 5px;" /></td>
                    <?php break; ?>
                    <?php case ('Mountainous'): ?>
                        <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="<?php echo e($trip->tripLandscape); ?>"><img src="<?php echo e(asset('assets/images/mountain.png')); ?>" style="width: 40px; height: 40px; margin: 5px;" /></td>
                    <?php break; ?>
                    <?php case ('Forested'): ?>
                        <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="<?php echo e($trip->tripLandscape); ?>"><img src="<?php echo e(asset('assets/images/forest.png')); ?>" style="width: 40px; height: 40px; margin: 5px;" /></td>
                    <?php break; ?>
                <?php endswitch; ?>
                <?php switch($trip->tripAvailability):
                    case ('available'): ?>
                        <td class="m-3 text-white badge rounded-pill bg-success"><?php echo e($trip->tripAvailability); ?></td>
                    <?php break; ?>
                    <?php case ('coming soon'): ?>
                        <td class="m-3 text-white badge rounded-pill bg-warning"><?php echo e($trip->tripAvailability); ?></td>
                    <?php break; ?>
                    <?php case ('unavailable'): ?>
                        <td class="m-3 text-white badge rounded-pill bg-danger"><?php echo e($trip->tripAvailability); ?></td>
                    <?php break; ?>
                <?php endswitch; ?>
                <td><?php echo e(date('F jS, Y', strtotime($trip->tripStartDate))); ?></td>
                <td><?php echo e(date('F jS, Y', strtotime($trip->tripEndDate))); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($trip->tripStartDate)->diffInDays($trip->tripEndDate)); ?></td>
                <td>$<?php echo e(number_format($trip->tripPrice, 2)); ?></td>
                <td><?php echo e(date('F jS, Y', strtotime($trip->created_at))); ?></td>
                <td><?php echo e(date('F jS, Y', strtotime($trip->updated_at))); ?></td>
                <td>
                    <a href="<?php echo e(route('admin.trip', ['tripID' => $trip->tripID])); ?>" class="text-decoration-underline">
                        View
                    </a>
                </td>
                <td>
                    <form method="post" action="<?php echo e(route('admin.trip.delete', ['tripID' => $trip->tripID])); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger text-white">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/components/admincomponents/all-trips.blade.php ENDPATH**/ ?>