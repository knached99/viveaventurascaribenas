<?php
    use Carbon\Carbon;

    $today = Carbon::today();

    $startDate = Carbon::parse($trip->tripStartDate);
    $endDate = Carbon::parse($trip->tripEndDate);
    $tripPhotos = json_decode($trip->tripPhoto, true);
?>

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

<section class="trip-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- Photo Grid Section -->
                <div class="photo-grid">
                    <?php if(!empty($tripPhotos)): ?>
                        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel"
                            style="border-radius: 8px;">
                            <div class="carousel-inner">
                                <?php $__currentLoopData = $tripPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                        <img src="<?php echo e($photo); ?>" class="d-block w-100"
                                            alt="Photo <?php echo e($index + 1); ?>">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="photo-item">
                            <img src="<?php echo e(asset('assets/images/image_placeholder.jpg')); ?>" class="d-block w-100"
                                style="height: 300px;" />
                        </div>
                    <?php endif; ?>
                </div>

                <!-- End Photo Grid Section -->



                <div class="trip-details">
                    <h2><?php echo e($trip->tripLocation); ?>

                        <!-- If this is the most popular booking, then dispaly the badge here-->
                        <?php if($isMostPopular): ?>
                            <img src="<?php echo e(asset('assets/theme_assets/assets/img/popularBadge.webp')); ?>"
                                style="width: 100px; height: 100px;" />
                        <?php endif; ?>
                    </h2>



                    <!-- Average Star Rating -->
                    <div class="star-rating mb-3">
                        <?php
                            // Calculate the number of full stars
                            $fullStars = floor($averageTestimonialRating);

                            // Determine if there is a half star needed
                            $halfStar = $averageTestimonialRating - $fullStars >= 0.5;

                            // Calculate the number of empty stars
                            $emptyStars = 5 - ($fullStars + ($halfStar ? 1 : 0));

                            // Calculate the fraction of the star needed
                            $fraction = $averageTestimonialRating - $fullStars;
                        ?>

                        <!-- Render full stars -->
                        <?php for($i = 1; $i <= $fullStars; $i++): ?>
                            <i class="bx bxs-star star-icon text-warning"></i>
                        <?php endfor; ?>

                        <!-- Render half star if needed -->
                        <?php if($fraction >= 0.25 && $fraction < 0.75): ?>
                            <i class="bx bxs-star-half star-icon text-warning"></i>
                        <?php elseif($fraction >= 0.75): ?>
                            <i class="bx bxs-star star-icon text-warning"></i>
                        <?php endif; ?>

                        <!-- Render empty stars -->
                        <?php for($i = 1; $i <= $emptyStars; $i++): ?>
                            <i class="bx bxs-star star-icon text-secondary"></i>
                        <?php endfor; ?>



                        <!-- Display the average rating -->
                        <span class="text-muted">(<?php echo e(number_format($averageTestimonialRating, 1)); ?> / 5.0)</span>
                        <span class="mt-3 block">
                            <br />
                            <?php if($testimonials->isEmpty()): ?>
                                Be among the first to experience this trip and share your review! Your feedback will
                                help others discover this amazing adventure.
                            <?php endif; ?>
                        </span>
                    </div>



                    <span class="trip-price">$<?php echo e(number_format($trip->tripPrice, 2)); ?> /person</span>
                    <p class="trip-duration">
                        <!-- End Average Star Rating -->


                        <?php echo e(\Carbon\Carbon::parse($trip->tripStartDate)->diffInDays($trip->tripEndDate)); ?> Days Tour
                    </p>
                    <p class="trip-availability">
                        <?php switch($trip->tripAvailability):
                            case ('available'): ?>
                                <span class="success-badge"><?php echo e($trip->tripAvailability); ?></span>
                            <?php break; ?>

                            <?php case ('coming soon'): ?>
                                <span class="warning-badge"><?php echo e($trip->tripAvailability); ?></span>
                                <!-- Add a disclaimer -->
                                <br />
                                <span class="text-gray-800 mt-3" style="font-style: italic; ">This trip will be available soon!
                                    Once
                                    we have enough travelers,
                                    dates will be released. Let us know your preferred month to travel, and we’ll do our best to
                                    accommodate.</span>
                            <?php break; ?>

                            <?php case ('unavailable'): ?>
                                <span class="danger-badge"><?php echo e($trip->tripAvailability); ?></span>
                            <?php break; ?>
                        <?php endswitch; ?>
                    </p>
                    <p class="trip-description"><?php echo e($trip->tripDescription); ?></p>
                    <ul class="trip-info">
                        <li><img src="<?php echo e(asset('assets/images/calendar.png')); ?>" class="icon" />
                            <?php echo e(date('F jS, Y', strtotime($trip->tripStartDate))); ?> -
                            <?php echo e(date('F jS, Y', strtotime($trip->tripEndDate))); ?>

                        </li>
                        <?php switch($trip->tripLandscape):
                            case ('Beach'): ?>
                                <li><img src="<?php echo e(asset('assets/images/beach.png')); ?>" class="icon" />
                                    <?php echo e($trip->tripLandscape); ?></li>
                            <?php break; ?>

                            <?php case ('City'): ?>
                                <li><img src="<?php echo e(asset('assets/images/buildings.png')); ?>" class="icon" />
                                    <?php echo e($trip->tripLandscape); ?></li>
                            <?php break; ?>

                            <?php case ('Country Side'): ?>
                                <li><img src="<?php echo e(asset('assets/images/farm.png')); ?>" class="icon" />
                                    <?php echo e($trip->tripLandscape); ?></li>
                            <?php break; ?>

                            <?php case ('Mountainous'): ?>
                                <li><img src="<?php echo e(asset('assets/images/mountain.png')); ?>" class="icon" />
                                    <?php echo e($trip->tripLandscape); ?></li>
                            <?php break; ?>

                            <?php case ('Forested'): ?>
                                <li><img src="<?php echo e(asset('assets/images/forest.png')); ?>" class="icon" />
                                    <?php echo e($trip->tripLandscape); ?></li>
                            <?php break; ?>
                        <?php endswitch; ?>
                    </ul>

                    <!-- No refunds disclaimer -->
                    <div class="mt-4 text-lg text-gray-800" style="font-weight: bold;">
                        <p>
                            Please note that due to the time-sensitive nature and significant costs involved in
                            organizing our trips, we are unable to offer refunds once the booking is confirmed. We
                            deeply value your understanding and appreciate your support in helping us maintain the
                            high-quality experiences we strive to provide.
                        </p>
                    </div>
                </div>


                <!-- Activities Section -->
                <div class="border-bottom-1 border-secondary"> </div>
                <div style="border-bottom: 1px solid #1e293b"></div>
                <h2 class="m-3" style="font-weight: 900;">Trip Activities</h2>
                <p class="trip-description"><?php echo e($trip->tripActivities); ?></p>
                <!-- End Activities Section -->

                <!-- Testimonials Slider -->
                <div class="testimonials-slider mt-4">
                    <h3>What other travellers have to say</h3>
                    <?php if(!$testimonials->isEmpty()): ?>
                        <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="carousel-item <?php echo e($key === 0 ? 'active' : ''); ?>">
                                        <div class="card testimonial-card">
                                            <div class="card-body">
                                                <p class="card-text">“<?php echo e($testimonial->testimonial); ?>”</p>
                                                <h5 class="card-title"><?php echo e($testimonial->name); ?></h5>
                                                <div class="star-rating">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <i
                                                            class="bx bxs-star star-icon <?php echo e($i <= $testimonial->trip_rating ? 'text-warning' : 'text-secondary'); ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <p class="text-muted"><i class='bx bx-calendar'
                                                        style="font-size: 30px;"></i>
                                                    <?php echo e(date('F jS, Y', strtotime($testimonial->created_at))); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <!-- Custom buttons -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel"
                                data-bs-slide="prev">
                                <span class="visually-hidden"><i class='bx bx-left-arrow-alt'></i></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel"
                                data-bs-slide="next">
                                <span class="visually-hidden"><i class='bx bx-right-arrow-alt'></i></span>
                            </button>
                        </div>
                    <?php else: ?>
                        <p style="font-size: 25px; color: #94a3b8; margin-left: 10px;">Be the first to leave a review!
                        </p>
                    <?php endif; ?>
                </div>
                <!-- End Testimonials Slider -->


            </div>
            <?php if($trip->tripAvailability === 'unavailable'): ?>
                <div class="col-md-4">
                    <!-- Booking Widget -->
                    <div class="booking-widget">
                        <h3 class="text-secondary">Trip not available to book</h3>


                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-4">
                    <!-- Booking Widget -->
                    <div class="booking-widget">
                        <h3><?php echo e($trip->tripAvailability === 'coming soon' ? 'Reserve this Trip' : 'Book this Trip'); ?>

                        </h3>
                        <a href="<?php echo e(route('booking', ['tripID' => $tripID])); ?>" type="submit"
                            class="btn"><?php echo e($trip->tripAvailability === 'coming soon'
                                ? 'Reserve Now'
                                : 'Book
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        Now'); ?></a>

                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

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
<?php /**PATH /Applications/MAMP/htdocs/viveaventurascaribenas/resources/views//landing/destination.blade.php ENDPATH**/ ?>