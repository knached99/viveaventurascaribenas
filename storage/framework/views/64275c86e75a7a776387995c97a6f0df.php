<?php
    $totalTransactions = count($transactions);
    $totalAmount = 0;

    foreach ($transactions as $transaction) {
        $totalAmount += $transaction->amount / 100; // Stripe amounts are in cents
    }

    $formattedTotalAmount = number_format($totalAmount, 2);
?>
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
        

        <div class="col-xxl-8 mb-6 order-0">
            <div class="card">
                <div class="d-flex align-items-start row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">Storage Usage</h5>
                            <p class="mb-6">
                                You have used <strong>100 GB</strong> of your
                                <strong>100 GB</strong> storage capacity.
                            </p>
                            <div class="progress mb-3">
                                <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="mb-6">
                                You have <strong>0 GB</strong> of storage remaining.
                            </p>

                            <a href="javascript:;" class="btn btn-sm btn-outline-primary">Manage Storage</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-6">
                            <img src="<?php echo e(asset('assets/theme_assets/assets/img/illustrations/cloud-storage.webp')); ?>"
                                height="175" class="scaleX-n1-rtl" alt="Storage Usage" />
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="<?php echo e(asset('assets/theme_assets/assets/img/icons/unicons/chart-success.png')); ?>"
                                        alt="chart success" class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Gross Profit</p>
                            <h4 class="card-title mb-3">$<?php echo e($formattedTotalAmount); ?></h4>
                            <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +72.80%</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="<?php echo e(asset('assets/theme_assets/assets/img/icons/unicons/wallet-info.png')); ?>"
                                        alt="wallet info" class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Most Popular Booking</p>
                            <?php if($mostPopularBooking && $mostPopularTripName): ?>
                                <h4 style="font-size: 18px; display: inline-block"><?php echo e($mostPopularTripName); ?></h4>
                                <p>Booked <?php echo e($mostPopularBooking->booking_count); ?> times</p>
                            <?php endif; ?>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Revenue -->
        <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
            <div class="card">
                <div class="row row-bordered g-0">
                    <div class="col-lg-8">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Total Revenue</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="totalRevenue" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded bx-lg text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalRevenue">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div id="totalRevenueChart" class="px-3"></div>
                    </div>
                    <div class="col-lg-4 d-flex align-items-center">
                        <div class="card-body px-xl-9">
                            <div class="text-center mb-6">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-primary">
                                        <script>
                                            document.write(new Date().getFullYear() - 1);
                                        </script>
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0);">2021</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">2020</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">2019</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div id="growthChart"></div>
                            <div class="text-center fw-medium my-6">62% Company Growth</div>

                            <div class="d-flex gap-3 justify-content-between">
                                <div class="d-flex">
                                    <div class="avatar me-2">
                                        <span class="avatar-initial rounded-2 bg-label-primary"><i
                                                class="bx bx-dollar bx-lg text-primary"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>
                                            <script>
                                                document.write(new Date().getFullYear() - 1);
                                            </script>
                                        </small>
                                        <h6 class="mb-0">$32.5k</h6>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="avatar me-2">
                                        <span class="avatar-initial rounded-2 bg-label-info"><i
                                                class="bx bx-wallet bx-lg text-info"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>
                                            <script>
                                                document.write(new Date().getFullYear() - 2);
                                            </script>
                                        </small>
                                        <h6 class="mb-0">$41.2k</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Total Revenue -->
        <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
            <div class="row">
                <div class="col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="<?php echo e(asset('assets/theme_assets/assets/img/icons/unicons/paypal.png')); ?>"
                                        alt="paypal" class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Successful Payments</p>
                            <h4 class="card-title mb-3"><?php echo e($totalTransactions); ?></h4>
                            
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="<?php echo e(asset('assets/theme_assets/assets/img/icons/unicons/cc-primary.png')); ?>"
                                        alt="Credit Card" class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Transactions</p>
                            <h4 class="card-title mb-3">$<?php echo e($formattedTotalAmount); ?></h4>
                            
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-6">
                    <div class="card">
                        <div class="card-body">
                            <div
                                class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10">
                                <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                    <div class="card-title mb-6">
                                        <h5 class="text-nowrap mb-1">Profile Report</h5>
                                        <span class="badge bg-label-warning">YEAR 2022</span>
                                    </div>
                                    <div class="mt-sm-auto">
                                        <span class="text-success text-nowrap fw-medium"><i
                                                class="bx bx-up-arrow-alt"></i> 68.2%</span>
                                        <h4 class="mb-0">$84,686k</h4>
                                    </div>
                                </div>
                                <div id="profileReportChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Order Statistics -->
        
        <!--/ Order Statistics -->

        <!-- Expense Overview -->
        
        <!--/ Expense Overview -->

        <!-- Transactions Go Here -->
        <?php if (isset($component)) { $__componentOriginal5dc2ae1852c674485bc5fce5ff60e472 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5dc2ae1852c674485bc5fce5ff60e472 = $attributes; } ?>
<?php $component = App\View\Components\Admincomponents\Transactions::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admincomponents.transactions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Admincomponents\Transactions::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['bookings' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($bookings)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5dc2ae1852c674485bc5fce5ff60e472)): ?>
<?php $attributes = $__attributesOriginal5dc2ae1852c674485bc5fce5ff60e472; ?>
<?php unset($__attributesOriginal5dc2ae1852c674485bc5fce5ff60e472); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5dc2ae1852c674485bc5fce5ff60e472)): ?>
<?php $component = $__componentOriginal5dc2ae1852c674485bc5fce5ff60e472; ?>
<?php unset($__componentOriginal5dc2ae1852c674485bc5fce5ff60e472); ?>
<?php endif; ?>

        <!-- Reservations -->
        <?php if (isset($component)) { $__componentOriginal6fe410394fee0f0771e718a4529fc226 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6fe410394fee0f0771e718a4529fc226 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admincomponents.reservations-table','data' => ['reservations' => $reservations]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admincomponents.reservations-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['reservations' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reservations)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6fe410394fee0f0771e718a4529fc226)): ?>
<?php $attributes = $__attributesOriginal6fe410394fee0f0771e718a4529fc226; ?>
<?php unset($__attributesOriginal6fe410394fee0f0771e718a4529fc226); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6fe410394fee0f0771e718a4529fc226)): ?>
<?php $component = $__componentOriginal6fe410394fee0f0771e718a4529fc226; ?>
<?php unset($__componentOriginal6fe410394fee0f0771e718a4529fc226); ?>
<?php endif; ?>
    </div>
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
<?php /**PATH /Applications/MAMP/htdocs/viveaventurascaribenas/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>