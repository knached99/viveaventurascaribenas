 <?php
 
 use App\Livewire\Actions\Logout;
 use Livewire\Volt\Component;
 
 new class extends Component {
     /**
      * Log the current user out of the application.
      */
     public function logout(Logout $logout): void
     {
         $logout();
 
         $this->redirect('/login');
     }
 }; ?>

 <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">
     <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
         <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
             <i class="bx bx-menu bx-md"></i>
         </a>
     </div>

     <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
         <!-- Search Livewire Component -->
         <livewire:forms.search lazy/>

         <!-- /Search Livewire Component -->

         <ul class="navbar-nav flex-row align-items-center ms-auto">
           

             <!-- User -->
             <li class="nav-item navbar-dropdown dropdown-user dropdown">
                 <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                     data-bs-toggle="dropdown">
                     <div class="avatar avatar-online">
                         <img src="{{ asset('assets/theme_assets/assets/img/avatars/default_profile_pic.jpg') }}" alt
                             class="w-px-40 h-auto rounded-circle" />
                     </div>
                 </a>
                 <ul class="dropdown-menu dropdown-menu-end">
                     <li>
                         <a class="dropdown-item" href="#">
                             <div class="d-flex">
                                 <div class="flex-shrink-0 me-3">
                                     <div class="avatar avatar-online">
                                         <img src="{{ asset('assets/theme_assetsassets/img/avatars/default_profile_pic.jpg') }}" alt
                                             class="w-px-40 h-auto rounded-circle" />
                                     </div>
                                 </div>
                                 <div class="flex-grow-1">
                                     <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                     <small class="text-muted">Admin</small>
                                 </div>
                             </div>
                         </a>
                     </li>
                     <li>
                         <div class="dropdown-divider my-1"></div>
                     </li>
                     <li>
                         <a class="dropdown-item" href="{{ route('admin.profile') }}">
                             <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                         </a>
                     </li>
                     <li>
                         <a class="dropdown-item" href="#"> <i
                                 class="bx bx-cog bx-md me-3"></i><span>Settings</span> </a>
                     </li>
                     <li>
                         <a class="dropdown-item" href="#">
                             <span class="d-flex align-items-center align-middle">
                                 <i class="flex-shrink-0 bx bx-credit-card bx-md me-3"></i><span
                                     class="flex-grow-1 align-middle">Billing Plan</span>
                                 <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                             </span>
                         </a>
                     </li>
                     <li>
                         <div class="dropdown-divider my-1"></div>
                     </li>
                     <li>

                         <button button wire:click="logout" class="dropdown-item">
                             <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                         </button>
                     </li>
                 </ul>
             </li>
             <!--/ User -->
         </ul>
     </div>
 </nav>
