@props(['numTestimonials'])
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/images/faviconIcon.png') }}" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Admin</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @php
            $activeAdminRoutes = ['admin.dashboard', 'admin.profile', 'admin.testimonials', 'admin.analytics'];

            $isAdminActive = in_array(request()->route()->getName(), $activeAdminRoutes) ? 'active open' : '';

            $activeTripRoutes = ['admin.create-trip', 'admin.all-trips', 'admin.photo-gallery'];

            $isTripsActive = in_array(request()->route()->getName(), $activeTripRoutes) ? 'active open' : '';

        @endphp
        <!-- Dashboards -->
        <li class="menu-item {{ $isAdminActive }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bxs-dashboard"></i>

                <div class="text-truncate" data-i18n="Dashboards">Dashboard</div>
                {{-- <span class="badge rounded-pill bg-danger ms-auto">5</span> --}}
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link text-decoration-none">
                        <div class="text-truncate" data-i18n="Home"><i class="menu-icon tf-icons bx bx-home-smile"></i>
                            Home</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.profile') ? 'active ' : '' }}">
                    <a href="{{ route('admin.profile') }}" class="menu-link text-decoration-none">
                        <div class="text-truncate" data-i18n="Profile"><i class="menu-icon tf-icons bx bx-user"></i>
                            Profile</div>

                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.testimonials') ? 'active' : '' }}">
                    <a href="{{ route('admin.testimonials') }}" class="menu-link text-decoration-none">
                        <div class="text-truncate" data-i18n="testimonials">
                            <i class='menu-icon tf-icons bx bx-paper-plane'></i> Testimonials
                            <span
                                class="relative inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-500 text-white text-xs font-medium">
                                <i class="bx bx-bell"></i>
                                <span
                                    class="absolute top-0 right-0 -mt-1 -mr-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                                    {{ $numTestimonials }}
                                </span>
                            </span>
                        </div>
                    </a>
                </li>


                <li class="menu-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                    <a href="{{ route('admin.analytics') }}" class="menu-link text-decoration-none">
                        <div class="text-truncate" data-i18n="analytics"><i
                                class="menu-icon tf-icons fa-solid fa-chart-line"></i> Analytics</div>
                    </a>
                </li>

                <li class="menu-item {{request()->routeIs('admin.backups') ? 'active' : ''}}">
                <a href="{{route('admin.backups')}}" class="menu-link text-decoration-none">
                <div class="text-truncate" data-i18n="backups">
                <i class="menu-icon tf-icons fa-solid fa-cloud-arrow-down"></i> Backups</div>
                </a>
                </li>


            </ul>
        </li>

        <!-- Layouts -->
        <li class="menu-item {{ $isTripsActive }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bx-map-alt"></i>
                <div class="text-truncate" data-i18n="Trips">Trips</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.create-trip') ? 'active' : '' }}">
                    <a href="{{ route('admin.create-trip') }}" class="menu-link text-decoration-none">
                        <div class="text-truncate" data-i18n="trips"><i class="menu-icon tf-icons bx bx-plus"></i>
                            Create Trip</div>
                        {{-- <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto">Pro</div> --}}
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.all-trips') ? 'active' : '' }}">
                    <a href="{{ route('admin.all-trips') }}" class="menu-link text-decoration-none">
                        <div class="text-truncate" data-i18n="Without navbar"><i
                                class='menu-icon tf-icons bx bx-briefcase'></i> All Trips</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('admin.photo-gallery') ? 'active' : '' }}">
                    <a href="{{ route('admin.photo-gallery') }}" class="menu-link text-decoration-none">
                        <div class="text-truncate" data-i18n="Photo Gallery"><i
                                class='menu-icon tf-icons bx bx-images'></i>
                            Photo Gallery
                        </div>
                    </a>

                </li>

            </ul>
        </li>

        {{-- <!-- Front Pages -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Front Pages">Front Pages</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto">Pro</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a
                    href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/front-pages/landing-page.html"
                    class="menu-link"
                    target="_blank">
                    <div class="text-truncate" data-i18n="Landing">Landing</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/front-pages/pricing-page.html"
                    class="menu-link"
                    target="_blank">
                    <div class="text-truncate" data-i18n="Pricing">Pricing</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/front-pages/payment-page.html"
                    class="menu-link"
                    target="_blank">
                    <div class="text-truncate" data-i18n="Payment">Payment</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/front-pages/checkout-page.html"
                    class="menu-link"
                    target="_blank">
                    <div class="text-truncate" data-i18n="Checkout">Checkout</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/front-pages/help-center-landing.html"
                    class="menu-link"
                    target="_blank">
                    <div class="text-truncate" data-i18n="Help Center">Help Center</div>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Apps & Pages -->
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Apps &amp; Pages</span>
            </li>
            <li class="menu-item">
              <a
                href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/vertical-menu-template/app-email.html"
                target="_blank"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div class="text-truncate" data-i18n="Email">Email</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto">Pro</div>
              </a>
            </li>
            <li class="menu-item">
              <a
                href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/vertical-menu-template/app-chat.html"
                target="_blank"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-chat"></i>
                <div class="text-truncate" data-i18n="Chat">Chat</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto">Pro</div>
              </a>
            </li>
            <li class="menu-item">
              <a
                href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/vertical-menu-template/app-calendar.html"
                target="_blank"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div class="text-truncate" data-i18n="Calendar">Calendar</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto">Pro</div>
              </a>
            </li>
            <li class="menu-item">
              <a
                href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/vertical-menu-template/app-kanban.html"
                target="_blank"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-grid"></i>
                <div class="text-truncate" data-i18n="Kanban">Kanban</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto">Pro</div>
              </a>
            </li>
            <!-- Pages -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div class="text-truncate" data-i18n="Account Settings">Account Settings</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="pages-account-settings-account.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Account">Account</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="pages-account-settings-notifications.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Notifications">Notifications</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="pages-account-settings-connections.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Connections">Connections</div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                <div class="text-truncate" data-i18n="Authentications">Authentications</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="auth-login-basic.html" class="menu-link" target="_blank">
                    <div class="text-truncate" data-i18n="Basic">Login</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="auth-register-basic.html" class="menu-link" target="_blank">
                    <div class="text-truncate" data-i18n="Basic">Register</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="auth-forgot-password-basic.html" class="menu-link" target="_blank">
                    <div class="text-truncate" data-i18n="Basic">Forgot Password</div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bx-cube-alt"></i>
                <div class="text-truncate" data-i18n="Misc">Misc</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="pages-misc-error.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Error">Error</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="pages-misc-under-maintenance.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Under Maintenance">Under Maintenance</div>
                  </a>
                </li>
              </ul>
            </li>
            <!-- Components -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Components</span></li>
            <!-- Cards -->
            <li class="menu-item">
              <a href="cards-basic.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                <div class="text-truncate" data-i18n="Basic">Cards</div>
              </a>
            </li>
            <!-- User interface -->
            <li class="menu-item">
              <a href="javascript:void(0)" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div class="text-truncate" data-i18n="User interface">User interface</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="ui-accordion.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Accordion">Accordion</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-alerts.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Alerts">Alerts</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-badges.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Badges">Badges</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-buttons.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Buttons">Buttons</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-carousel.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Carousel">Carousel</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-collapse.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Collapse">Collapse</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-dropdowns.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Dropdowns">Dropdowns</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-footer.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Footer">Footer</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-list-groups.html" class="menu-link">
                    <div class="text-truncate" data-i18n="List Groups">List groups</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-modals.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Modals">Modals</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-navbar.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Navbar">Navbar</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-offcanvas.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Offcanvas">Offcanvas</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-pagination-breadcrumbs.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Pagination & Breadcrumbs">Pagination &amp; Breadcrumbs</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-progress.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Progress">Progress</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-spinners.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Spinners">Spinners</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-tabs-pills.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Tabs & Pills">Tabs &amp; Pills</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-toasts.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Toasts">Toasts</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-tooltips-popovers.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Tooltips & Popovers">Tooltips &amp; Popovers</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="ui-typography.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Typography">Typography</div>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Extended components -->
            <li class="menu-item">
              <a href="javascript:void(0)" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bx-copy"></i>
                <div class="text-truncate" data-i18n="Extended UI">Extended UI</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="extended-ui-perfect-scrollbar.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Perfect Scrollbar">Perfect Scrollbar</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="extended-ui-text-divider.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Text Divider">Text Divider</div>
                  </a>
                </li>
              </ul>
            </li>

            <li class="menu-item">
              <a href="icons-boxicons.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-crown"></i>
                <div class="text-truncate" data-i18n="Boxicons">Boxicons</div>
              </a>
            </li>

            <!-- Forms & Tables -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Forms &amp; Tables</span></li>
            <!-- Forms -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div class="text-truncate" data-i18n="Form Elements">Form Elements</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="forms-basic-inputs.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Basic Inputs">Basic Inputs</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="forms-input-groups.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Input groups">Input groups</div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div class="text-truncate" data-i18n="Form Layouts">Form Layouts</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="form-layouts-vertical.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Vertical Form">Vertical Form</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="form-layouts-horizontal.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Horizontal Form">Horizontal Form</div>
                  </a>
                </li>
              </ul>
            </li>
            <!-- Form Validation -->
            <li class="menu-item">
              <a
                href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/vertical-menu-template/form-validation.html"
                target="_blank"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-check"></i>
                <div class="text-truncate" data-i18n="Form Validation">Form Validation</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto">Pro</div>
              </a>
            </li>
            <!-- Tables -->
            <li class="menu-item">
              <a href="tables-basic.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div class="text-truncate" data-i18n="Tables">Tables</div>
              </a>
            </li>
            <!-- Data Tables -->
            <li class="menu-item">
              <a
                href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/vertical-menu-template/tables-datatables-basic.html"
                target="_blank"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-grid"></i>
                <div class="text-truncate" data-i18n="Datatables">Datatables</div>
                <div class="badge rounded-pill bg-label-primary text-uppercase fs-tiny ms-auto">Pro</div>
              </a>
            </li>
            <!-- Misc -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Misc</span></li>
            <li class="menu-item">
              <a
                href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                target="_blank"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div class="text-truncate" data-i18n="Support">Support</div>
              </a>
            </li>
            <li class="menu-item">
              <a
                href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/"
                target="_blank"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div class="text-truncate" data-i18n="Documentation">Documentation</div>
              </a>
            </li>  --}}
    </ul>
</aside>
