<!doctype html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="<?php echo e(asset('assets/theme_assets/assets')); ?>" data-template="vertical-menu-template-free"
    data-style="light">

<head>

    <!DOCTYPE html>

    <meta charset="utf-8" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Viveaventuarscaribenas')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- CKEDITOR 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?php echo e(config('app.name')); ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/images/faviconIcon.png')); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo e(asset('assets/theme_assets/assets/vendor/fonts/boxicons.css')); ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/theme_assets/assets/vendor/css/core.css')); ?>"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/theme_assets/assets/vendor/css/theme-default.css')); ?>"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/theme_assets/assets/css/demo.css')); ?>" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.bootstrap5.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="<?php echo e(asset('assets/theme_assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')); ?>" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/theme_assets/assets/vendor/css/pages/page-auth.css')); ?>" />


    <!-- Helpers -->
    <script src="<?php echo e(asset('assets/theme_assets/assets/vendor/js/helpers.js')); ?>"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo e(asset('assets/theme_assets/assets/js/config.js')); ?>"></script>

    <!-- CKEDITOR 5 CDN -->
	
   
        <!-- Quill Editor Styles -->
    

        <!-- Quill Editor Script -->
        


    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>

<body>
<?php /**PATH /Applications/MAMP/htdocs/viveaventurascaribenas/resources/views/components/admincomponents/header.blade.php ENDPATH**/ ?>