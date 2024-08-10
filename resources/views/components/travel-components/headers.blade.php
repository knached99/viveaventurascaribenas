<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{config('app.name')}}</title>
    <link rel="shortcut icon" href="{{asset('assets/images/fav.png')}}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('assets/images/fav.jpg')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}" />
</head>

    <body>
        <header class="container-flui">
           <div class="header-top">
               <div class="container">
                    <div class="row">
                        <div class="col-md-4 d-none d-md-block mail-detail">
                            <ul>
                                <li>Call US : +12 878 777 76765</li>
                                <li>Email : info@thetraveller.com</li>
                            </ul>
                        </div>
                        <div class="col-md-4 logo">
                            {{-- <img src="assets/images/logo.png" alt=""> --}}
                            <h1 class="text-white fw-bold">Vive Aventuras Caribenas</h1>
                            <a data-toggle="collapse" data-target="#menu-jk" href="#menu-jk"><i class="fas d-block d-sm-block d-md-none small-menu fa-bars"></i></a>
                        </div>
                        <div class="col-md-4 d-none d-md-block social-link ">
                            <ul>
                                <li>
                                    <i class="fab fa-facebook-square"></i>
                                </li>
                                <li>
                                    <i class="fab fa-twitter-square"></i>
                                </li>
                                <li>
                                    <i class="fab fa-instagram"></i>
                                </li>
                                <li>
                                    <i class="fab fa-linkedin"></i>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
           </div>
           <div id="menu-jk" class="header-nav d-none d-md-block">
               <div class="container">
                   <div class="row nav-row">
                       <ul>
                           <li><a href="/">Home</a></li>
                           <li><a href="{{route('about')}}">About Us</a></li>
                           <li><a href="{{route('destinations')}}">Destinations</a></li>
                           <li><a href="{{route('blog')}}">Blog</a></li>
                           <li><a href="{{route('gallery')}}">Gallery</a></li>
                           <li><a href="{{route('contact')}}">Contact Us</a></li>
                       </ul>
                   </div>
               </div>
           </div>
            
        </header>   
        