@php
    $isAuthenticated = \Auth::check();
    $redirectRoute = '';
    if ($isAuthenticated) {
        $redirectRoute = 'admin.dashboard';
    } else {
        $redirectRoute = '/';
    }

@endphp

<head>
    <link rel="stylesheet" href="{{ asset('assets/css/404.css') }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/faviconIcon.png') }}">
    <title>Page Not Found </title>
</head>
<div class="container">
    <div class="d-flex align-center flex-column">
        <div class="col-L">
            <img src="{{ asset('assets/images/faviconIcon.png') }}" />


            <h1>Lost in the Wilderness!</h1>
            <h2>
                Oops! The Page You’re Looking For Has Gone Off the Map.
            </h2>
            <hr>
            <p>It seems like you’ve ventured into uncharted territory. The page you’re searching for is currently
                missing from our website.
            </p>

            <p>But Don’t Worry – Your Adventure Doesn’t Have to End Here!
                <a href="{{ route($redirectRoute) }}">return to safety</a>
            </p>
        </div>
        <div class="col-R">


            <img src="{{ asset('assets/theme_assets/assets/img/illustrations/404_illustration.jpg') }}" />
        </div>
    </div>
    </body>

    </html>
