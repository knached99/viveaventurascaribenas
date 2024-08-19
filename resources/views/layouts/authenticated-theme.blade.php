  <x-theme-layout>
  <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <x-admincomponents.sidebar/>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

           <livewire:layout.nav />


          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
             {{$slot}}
            </div>
            <!-- / Content -->

           <!-- FOOTER --> 
           {{-- <x-admincomponents.authenticated-footer/> --}}
           
    <!-- / Layout wrapper -->

  
  </x-theme-layout>