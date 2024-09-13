<?php
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
?>

<div class="position-relative">
    <form wire:submit.prevent="search">
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center position-relative">
                <i class="bx bx-search bx-md"></i>
                <input id="searchQuery" name="searchQuery" wire:model="searchQuery" type="text" wire:loading.remove
                    class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
                    aria-label="Search..." />

                <div class="spinner-border" wire:loading role="status">
                <span class="visually-hidden">Loading...</span>
                </div>

                <!-- Autocomplete Results Container -->
                <div class="autocomplete-results position-absolute top-100 start-0 w-100 bg-white rounded shadow-lg mt-1 max-height-200 overflow-auto"
                    style="width: 100%;">
                    <!--[if BLOCK]><![endif]--><?php if(!empty($searchResults)): ?>
                        <ul class="list-group m-0 p-0">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $searchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <!--[if BLOCK]><![endif]--><?php if(isset($result['tripID'])): ?>
                                    <!-- Trip Result -->
                                    <a href="<?php echo e(route('admin.trip', ['tripID' => $result['tripID'] ?? ''])); ?>"
                                        class="text-decoration-none">
                                        <li
                                            class="list-group-item p-2 border-bottom hover:bg-light cursor-pointer d-flex align-items-center">
                                            <div class="me-2">
                                                <h5 class="mb-1"><?php echo e($result['tripLocation'] ?? 'No Location'); ?> - Trip</h5>
                                              
                                            </div>
                                         
                                        </li>
                                    </a>
                                <?php elseif(isset($result['bookingID'])): ?>
                                    <!-- Booking Result -->
                                    <a href="<?php echo e(route('admin.booking', ['bookingID' => $result['bookingID'] ?? ''])); ?>"
                                        class="text-decoration-none">
                                        <li class="list-group-item p-2 border-bottom hover:bg-light cursor-pointer">
                                            <h5 class="mb-1"><?php echo e($result['name']); ?></h5>
                                            <p class="mb-0 text-muted"><?php echo e($result['email']); ?> |
                                                <?php echo e($result['phone_number']); ?></p>
                                            <p class="mb-0 text-muted">Booked location:

                                                <?php
                                                    try {
                                                        $product = $stripe->products->retrieve(
                                                            $result['stripe_product_id'],
                                                        );
                                                        $location = $product->name;
                                                    } catch (\Exception $e) {
                                                        $location = 'Unknown location';
                                                    }
                                                ?>
                                                <?php echo e($location); ?>

                                            </p>
                                        </li>
                                    </a>
                                <?php elseif(isset($result['testimonialID'])): ?>
                                    <!-- Testimonial Result -->
                                    <a href="<?php echo e(route('admin.testimonial', ['testimonialID' => $result['testimonialID'] ?? ''])); ?>"
                                        class="text-decoration-none">
                                        <li class="list-group-item p-2 border-bottom hover:bg-light cursor-pointer">
                                            <h5 class="mb-1"><?php echo e($result['name'] ?? 'Anonymous'); ?></h5>
                                            <p class="mb-0 text-muted">
                                                <?php echo e(\Str::limit($result['testimonial'] ?? 'No Testimonial', 100)); ?></p>
                                        </li>
                                    </a>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </ul>
                        <button type="button" wire:click="clearSearchResults" class="btn btn-link mt-2">Clear</button>
                    <?php elseif(isset($searchQuery) && $searchQuery !== ''): ?>
                        <p class="p-2 text-center">No results found for "<?php echo e($searchQuery); ?>"</p>
                        <button type="button" wire:click="clearSearchResults" class="btn btn-link mt-2">Clear</button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </form>
</div>
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/livewire/forms/search.blade.php ENDPATH**/ ?>