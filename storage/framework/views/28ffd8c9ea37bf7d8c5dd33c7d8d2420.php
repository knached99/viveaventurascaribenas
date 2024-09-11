<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="#" class="app-brand-link">
              <span class="app-brand-logo demo">
                <img src="<?php echo e(asset('assets/images/faviconIcon.png')); ?>"/>
              </span>
              <span class="ms-2"><?php echo e(config('app.name')); ?></span>
              
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboards -->
            <li class="menu-item active open">
              <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                
                <div class="text-truncate" data-i18n="Dashboards">Dashboard</div>
                
              </a>
              <ul class="menu-sub"> 
                <li class="menu-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                  <a href="<?php echo e(route('admin.dashboard')); ?>" class="menu-link text-decoration-none">
                    <div class="text-truncate" data-i18n="Home"><i class="menu-icon tf-icons bx bx-home-smile"></i> Home</div>
                  </a>
                </li>
                <li class="menu-item <?php echo e(request()->routeIs('admin.profile') ? 'active ' : ''); ?>">
                  <a
                    href="<?php echo e(route('admin.profile')); ?>"
                    class="menu-link text-decoration-none">
                    <div class="text-truncate" data-i18n="Profile"><i class="menu-icon tf-icons bx bx-user"></i>  Profile</div>
                   
                  </a>
                </li>

                <li class="menu-item <?php echo e(request()->routeIs('admin.testimonials') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.testimonials')); ?>" class="menu-link text-decoration-none">
                <div class="text-truncate" data-i18n="testimonials"><i class='menu-icon tf-icons bx bx-paper-plane'></i> Testimonials</div>
                </a>
                </li>
              
              </ul>
            </li>

            <!-- Layouts -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
                <i class="menu-icon tf-icons bx bx-map-alt"></i>
                <div class="text-truncate" data-i18n="Trips">Trips</div>
              </a>

              <ul class="menu-sub">
                 <li class="menu-item <?php echo e(request()->routeIs('admin.create-trip') ? 'active' : ''); ?>">
                  <a
                    href="<?php echo e(route('admin.create-trip')); ?>"
                 
                    class="menu-link text-decoration-none">
                    <div class="text-truncate" data-i18n="trips"><i class="menu-icon tf-icons bx bx-plus"></i> Create Trip</div>
                    
                  </a>
                </li>
                <li class="menu-item <?php echo e(request()->routeIs('admin.all-trips') ? 'active' : ''); ?>">
                  <a href="<?php echo e(route('admin.all-trips')); ?>" class="menu-link text-decoration-none">
                    <div class="text-truncate" data-i18n="Without navbar"><i class='menu-icon tf-icons bx bx-briefcase' ></i> All Trips</div>
                  </a>
                </li>
             
              </ul>
            </li>

            
          </ul>
        </aside><?php /**PATH /Applications/MAMP/htdocs/viveaventurascaribenas/resources/views/components/admincomponents/sidebar.blade.php ENDPATH**/ ?>