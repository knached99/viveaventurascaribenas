<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" style="font-size: 30px; background-color: #efad4c;"
            href="{{ route('/') }}">viveaventuras<span> caribenas</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
            aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item {{ request()->routeIs('/') ? 'active' : '' }}">
                    <a href="/" class="nav-link" style="font-size:20px;">Home</a>
                </li>

                <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}" style="font-size:20px;"><a
                        href="{{ route('about') }}" class="nav-link" style="font-size:20px;">About</a></li>
                <li class="nav-item {{ request()->routeIs('destinations') ? 'active' : '' }}" style="font-size:20px;"><a
                        href="{{ route('destinations') }}" class="nav-link" style="font-size:20px;">Destinations</a>
                </li>
                <li class="nav-item {{ request()->routeIs('blog') ? 'active' : '' }}" style="font-size:20px;"><a
                        href="{{ route('blog') }}" class="nav-link" style="font-size:20px;">Blog</a></li>
                <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}" style="font-size:20px;"><a
                        href="{{ route('contact') }}" class="nav-link" style="font-size:20px;">Contact</a></li>
                <li class="nav-item cta"><a href="#" class="nav-link">Book Now</a></li>

            </ul>
        </div>
    </div>
</nav>
<!-- END nav -->
