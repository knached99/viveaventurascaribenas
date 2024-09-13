<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['testimonials']));

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

foreach (array_filter((['testimonials']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<main>
    <h2 class="text-dark" style="font-weight: 900;">Travel Stories from Our Adventurers</h2>
    <h4>Discover firsthand accounts of unforgettable travel experiences and explore the adventures that have shaped our
        travelers' lives.</h4>
    <div class="slider">
        <div class="slide-row" id="slide-row">
            <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimony): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="slide-col">
                    <div class="content">
                        <blockquote class="testimonial-text">
                            <p><i class='bx bxs-quote-alt-left'></i><?php echo e($testimony->testimonial); ?><i
                                    class='bx bxs-quote-alt-right'></i></p>
                        </blockquote>
                        <h2><?php echo e($testimony->name); ?></h2>
                        <div class="star-rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i
                                    class="bx bxs-star star-icon <?php echo e($i <= $testimony->trip_rating ? 'text-warning' : 'text-secondary'); ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <div class="inline-block">
                            <span class="m-3 text-secondary"><i class='bx bx-map'
                                    style="font-size: 30px;"></i><?php echo e($testimony->trip->tripLocation); ?></span>
                            <span class="m-3 text-secondary"><i class='bx bx-calendar'
                                    style="font-size: 30px;"></i><?php echo e(date('F jS, Y', strtotime($testimony->created_at))); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <button class="nav-btn prev-btn">Prev</button>
        <button class="nav-btn next-btn">Next</button>
    </div>
    <div class="indicator">
        <?php for($i = 0; $i < count($testimonials); $i++): ?>
            <span class="btn <?php echo e($i === 0 ? 'active' : ''); ?>"></span>
        <?php endfor; ?>
    </div>
</main>
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/components/travelcomponents/testimonials.blade.php ENDPATH**/ ?>