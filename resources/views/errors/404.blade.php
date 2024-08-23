<!DOCTYPE html>
<html lang="en">

<head>
    <title>Site en maintenance</title>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-color: #fff;
            font-family: 'Montserrat', sans-serif;
        }

        .d-flex {
            display: flex;
        }

        .align-center {
            align-items: center;
        }

        .container {
            align-items: center;
            max-width: 90%;
        }

        .col-L {
            padding: 2em;
        }

        .col-R {
            max-width: 50%;
            width: 50%
        }

        .color-white {
            fill: #FFFFFF;
        }

        .logo {
            height: 40px;
            width: 40px;
            font-size: .5em;
            background-color: #ffcb1f;
            border-radius: 50%;
            padding: 1em;
            margin: 1em;
        }

        h1 {
            margin: 0;
            font-size: 5vmax;
            font-size: 36px;
            font-size: 7vmin;
            color: #000;
            font-weight: 900;
        }

        p {
            font-size: 25px;
            word-wrap: break-word;
            color: darkslategray;
        }

        @media only screen and (min-width: 992px) {
            .container {
                justify-content: center;
                height: calc(100vh - 16px);
                display: flex;
                flex-wrap: wrap;
            }

            .col-L {
                width: 40%;
                margin-right: 10%;
            }
        }

        @media only screen and (max-width: 991px) {
            .flex-column {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
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
                    <a href="{{ route('/') }}">return to safety</a>
                </p>
            </div>
            <div class="col-R">


                <img src="{{ asset('assets/theme_assets/assets/img/illustrations/404_illustration.jpg') }}" />
            </div>
        </div>
</body>

</html>
