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


                <h1>We’re Taking a Pit
                    Stop!</h1>
                <h2>
                    Adventure Awaits, but We’re Currently Under Construction!
                </h2>
                <hr>
                <p>Our travel site is temporarily offline as we embark on a journey to enhance your experience with
                    exciting new features and improvements. We’re working hard to ensure your next adventure with us
                    is
                    even better!

                </p>
            </div>
            <div class="col-R">


                <img src="{{ asset('assets/theme_assets/assets/img/illustrations/traveller.avif') }}" />
            </div>
        </div>
</body>

</html>
