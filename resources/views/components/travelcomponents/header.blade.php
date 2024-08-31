<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/faviconIcon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/ionicons.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.timepicker.css') }}">
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/theme_assets/assets/vendor/fonts/boxicons.css') }}" />

    @if (\Route::currentRouteName() === 'booking.success' || \Route::currentRouteName() === 'booking.cancel')
        <link rel="stylesheet" href="{{ asset('assets/css/booking_success.css') }}" />
    @endif

    <style>
        .ftco-section {
            padding: 2rem 0;
            background-color: #fff;
        }

        .heading-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
        }

        .heading-section p {
            font-size: 1.1rem;
            color: #6c757d;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            height: 50px;
            border-radius: 0.25rem;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-start;
        }

        .rating>input {
            display: none;
        }

        .rating>label {
            position: relative;
            width: 2em;
            /* Increased size */
            font-size: 40px;
            /* Larger stars */
            font-weight: 300;
            color: #FFD600;
            cursor: pointer;
        }

        .rating>label::before {
            content: "\2605";
            position: absolute;
            opacity: 0;
        }

        .rating>label:hover:before,
        .rating>label:hover~label:before {
            opacity: 1 !important;
        }

        .rating>input:checked~label:before {
            opacity: 1;
        }

        .rating:hover>input:checked~label:before {
            opacity: 0.4;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .spinner-border {
            display: inline-block;
            width: 2rem;
            height: 2rem;
        }

        @media (max-width: 768px) {
            .heading-section h2 {
                font-size: 2rem;
            }

            .form-control {
                height: 45px;
            }

            .rating>label {
                font-size: 30px;
                /* Adjust star size for smaller screens */
            }
        }

        @media (max-width: 576px) {
            .heading-section h2 {
                font-size: 1.75rem;
            }

            .rating>label {
                font-size: 25px;
                /* Further adjust star size for mobile screens */
            }
        }
    </style>


    <style>
        main {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }

        h2,
        h4 {
            margin: 0 auto 20px auto;
        }

        .slider {
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        .slide-row {
            display: flex;
            transition: transform 0.5s ease;
            width: 100%;
            /* Ensures it doesn't exceed the container */
        }

        .slide-col {
            flex: 0 0 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            /* Ensure padding is included in width */
        }

        .content {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            max-width: 800px;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        .testimonial-text {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 20px;
            position: relative;
            padding: 20px;
            border-left: 4px solid #f39c12;
            background: #f9f9f9;
            font-style: italic;
        }

        .star-rating {
            margin-bottom: 20px;
        }

        .star-rating .star-icon {
            font-size: 1.5rem;
            color: #ddd;
        }

        .star-rating .text-warning {
            color: #f39c12;
        }

        .star-rating .text-secondary {
            color: #ddd;
        }

        .content h2 {
            font-size: 1.4rem;
            color: #333;
            margin-top: 10px;
            font-weight: bold;
        }

        .indicator {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .indicator .btn {
            display: inline-block;
            height: 15px;
            width: 15px;
            margin: 0 5px;
            border-radius: 50%;
            background: #ddd;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn.active {
            background: #000;
        }

        .nav-btn {
            position: absolute;
            top: 50%;
            background: #000;
            color: white;
            border-radius: 10%;
            padding: 10px 20px;
            cursor: pointer;
            transform: translateY(-50%);
            z-index: 10;
        }

        .prev-btn {
            left: 10px;
        }

        .next-btn {
            right: 10px;
        }

        @media (max-width: 850px) {
            .content {
                width: 90%;
            }
        }

        @media (max-width: 550px) {
            .content {
                width: 95%;
            }
        }
    </style>

    <style>
        .success-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            /* 1.5 * 0.25rem (base unit) */
            padding: 0.375rem 0.75rem;
            /* py-1.5 px-3 */
            border-radius: 9999px;
            /* rounded-full */
            font-size: 0.75rem;
            /* text-xs */
            font-weight: 500;
            /* font-medium */
            background-color: #d1fae5;
            /* bg-emerald-100 (light green) */
            color: #10b981;
            /* text-emerald-500 */
        }

        .warning-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            /* 1.5 * 0.25rem (base unit) */
            padding: 0.375rem 0.75rem;
            /* py-1.5 px-3 */
            border-radius: 9999px;
            /* rounded-full */
            font-size: 0.75rem;
            /* text-xs */
            font-weight: 500;
            /* font-medium */
            background-color: #fefcbf;
            /* bg-yellow-100 */
            color: #d97706;
            /* text-yellow-800 */
        }

        .danger-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            /* 1.5 * 0.25rem (base unit) */
            padding: 0.375rem 0.75rem;
            /* py-1.5 px-3 */
            border-radius: 9999px;
            /* rounded-full */
            font-size: 0.75rem;
            /* text-xs */
            font-weight: 500;
            /* font-medium */
            background-color: #fee2e2;
            /* bg-red-100 (light red) */
            color: #ef4444;
            /* text-red-500 */
        }
    </style>

    <!-- Individual Trip Details css -->
    <style>
        .trip-section {
            padding: 40px 0;
            background-color: #f7f7f7;
        }

        .trip-image {
            height: 500px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
        }

        .trip-details {
            margin-top: 20px;
        }

        .trip-details h2 {
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 10px;
        }

        .trip-price {
            font-size: 24px;
            font-weight: 700;
            color: #efad4c;
        }

        .trip-duration {
            font-size: 18px;
            margin: 10px 0;
        }

        .trip-availability {
            font-size: 16px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }

        .trip-availability.available {
            background-color: #28a745;
            color: #fff;
        }

        .trip-availability['coming soon'] {
            background-color: #ffc107;
            color: #fff;
        }

        .trip-availability.unavailable {
            background-color: #dc3545;
            color: #fff;
        }

        .trip-description {
            font-size: 16px;
            margin: 20px 0;
        }

        .trip-info {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .trip-info li {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .trip-info .icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .booking-widget {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .booking-widget h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .booking-widget .btn {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            font-weight: 700;
            border-radius: 5px;
            background-color: #efad4c;
            color: #fff;
            text-transform: uppercase;
            border: none;
        }

        .booking-widget .btn:hover {
            background-color: #000;
        }
    </style>

    @if (\Route::currentRouteName() === 'landing.destination')
        <style>
            .testimonials-slider .card {
                border: none;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .testimonials-slider .card-body {
                padding: 2rem;
                text-align: center;
            }

            .testimonials-slider .card-text {
                font-size: 1.125rem;
                color: #333;
                font-style: italic;
            }

            .testimonials-slider .card-title {
                font-size: 1.25rem;
                font-weight: bold;
                margin-top: 1rem;
            }

            .testimonials-slider .text-muted {
                color: #666;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background-color: #000;
                border: 1px solid #000;
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
            }




            .carousel-control-prev:hover,
            .carousel-control-next:hover {
                background-color: #000;
            }
        </style>
    @endif

    <!-- End Individual Trip Details css -->

    <!-- Booking Page Styling -->
    @if (\Route::currentRouteName() === 'booking')
        <link rel="stylesheet" href="{{ asset('assets/css/booking_form.css') }}" />
    @endif
    <!-- End Booking Page Styling -->
    @livewireStyles
</head>

<body>
