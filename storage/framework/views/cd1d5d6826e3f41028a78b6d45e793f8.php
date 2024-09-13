 
         <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column" style="visibility: hidden;">
                  <div class="text-body">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    , made with ❤️ by
                    <a href="https://themeselection.com" target="_blank" class="footer-link">ThemeSelection</a>
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                    <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                    <a
                      href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/"
                      target="_blank"
                      class="footer-link me-4"
                      >Documentation</a
                    >

                    <a
                      href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                      target="_blank"
                      class="footer-link"
                      >Support</a
                    >
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->



 </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="<?php echo e(asset('assets/theme_assets/assets/vendor/libs/jquery/jquery.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/theme_assets/assets/vendor/libs/popper/popper.js')); ?>"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="<?php echo e(asset('assets/theme_assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/theme_assets/assets/vendor/js/menu.js')); ?>"></script>
    
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap5.js"></script>
    <script>
    // Initialize DataTable with loading animation
    $(document).ready(function () {
        $('.dataTable').DataTable({
            scrollY: '400px',
            scrollCollapse: true,
            paging: true,
            language: {
                processing: 'Loading Data...'
            },
            processing: true,
        });
    });
</script>



    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?php echo e(asset('assets/theme_assets/assets/vendor/libs/apex-charts/apexcharts.js')); ?>"></script>

    <!-- ALPINE EDITOR -->
    

    
  

   <!-- Initialize Quill editor -->




 
    <!-- Main JS -->
    <script src="<?php echo e(asset('assets/theme_assets/assets/js/main.js')); ?>"></script>

    <!-- Page JS -->
    <script src="<?php echo e(asset('assets/theme_assets/assets/js/dashboards-analytics.js')); ?>"></script>


    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>



  </body>
</html>
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/components/admincomponents/footer.blade.php ENDPATH**/ ?>