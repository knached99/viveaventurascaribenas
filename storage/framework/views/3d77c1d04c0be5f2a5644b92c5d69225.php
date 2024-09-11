<head>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/404.css')); ?>" />
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/images/faviconIcon.png')); ?>">
    <title>Page Not Found </title>
</head>
<div class="container">
    <div class="d-flex align-center flex-column">
        <div class="col-L">
            <img src="<?php echo e(asset('assets/images/faviconIcon.png')); ?>" />


            <h1>Lost in the Wilderness!</h1>
            <h2>
                Oops! The Page You’re Looking For Has Gone Off the Map.
            </h2>
            <hr>
            <p>It seems like you’ve ventured into uncharted territory. The page you’re searching for is currently
                missing from our website.
            </p>

            <p>But Don’t Worry – Your Adventure Doesn’t Have to End Here!
                <a href="<?php echo e(route('/')); ?>">return to safety</a>
            </p>
        </div>
        <div class="col-R">


            <img src="<?php echo e(asset('assets/theme_assets/assets/img/illustrations/404_illustration.jpg')); ?>" />
        </div>
    </div>
    </body>

    </html>
<?php /**PATH /Applications/MAMP/htdocs/viveaventurascaribenas/resources/views/errors/404.blade.php ENDPATH**/ ?>