
<?php

$isLandingDestination = \Route::currentRouteName() === 'landing.destination' || \Route::currentRouteName() === 'booking.success' || \Route::currentRouteName() === 'booking.cancel';

$linkClass = $isLandingDestination ? 'nav-link text-dark' : 'nav-link';

$fontSizeStyle = 'font-size: 20px;';

?>

<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light"  id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" style="background-color: rgb(239, 173, 76);" href="<?php echo e(route('/')); ?>">viveaventuras<span>
                caribenas</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
            aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

  <div class="collapse navbar-collapse" id="ftco-nav">
    <ul class="navbar-nav ml-auto">
    
        <li class="nav-item <?php echo e(request()->routeIs('/') ? 'active' : ''); ?>">
            <a href="/" class="<?php echo e($linkClass); ?>" style="<?php echo e($fontSizeStyle); ?>">Home</a>
        </li>
        
        <li class="nav-item <?php echo e(request()->routeIs('about') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('about')); ?>" class="<?php echo e($linkClass); ?>" style="<?php echo e($fontSizeStyle); ?>">About</a>
        </li>

        <li class="nav-item <?php echo e(request()->routeIs('destinations') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('destinations')); ?>" class="<?php echo e($linkClass); ?>" style="<?php echo e($fontSizeStyle); ?>">Destinations</a>
        </li>

        <li class="nav-item <?php echo e(request()->routeIs('contact') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('contact')); ?>" class="<?php echo e($linkClass); ?>" style="<?php echo e($fontSizeStyle); ?>">Contact</a>
        </li>

        
    </ul>
</div>

    </div>
</nav>
<!-- END nav -->
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/components/travelcomponents/navbar.blade.php ENDPATH**/ ?>