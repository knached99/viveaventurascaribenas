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
    <style>
        .position-relative {
            position: relative;
        }

        .autocomplete-results {
            max-height: 200px;

            /* Adjust height as needed */
            overflow-y: auto;
        }

        .list-group-item {
            cursor: pointer;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
            /* Light background color on hover */
        }
    </style>
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
        width: 2em; /* Increased size */
        font-size: 40px; /* Larger stars */
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
            font-size: 30px; /* Adjust star size for smaller screens */
        }
    }

    @media (max-width: 576px) {
        .heading-section h2 {
            font-size: 1.75rem;
        }

        .rating>label {
            font-size: 25px; /* Further adjust star size for mobile screens */
        }
    }
</style>

    @livewireStyles
</head>

<body>
