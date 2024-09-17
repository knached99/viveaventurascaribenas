 <!-- Footer -->
 <footer class="content-footer footer bg-footer-theme">
     <div class="container-xxl">
         <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column"
             style="visibility: hidden;">
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

                 <a href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/"
                     target="_blank" class="footer-link me-4">Documentation</a>

                 <a href="https://github.com/themeselection/sneat-html-admin-template-free/issues" target="_blank"
                     class="footer-link">Support</a>
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

 <script src="{{ asset('assets/theme_assets/assets/vendor/libs/jquery/jquery.js') }}"></script>
 <script src="{{ asset('assets/theme_assets/assets/vendor/libs/popper/popper.js') }}"></script>
 {{-- <script src="{{asset('assets/theme_assets/assets/vendor/js/bootstrap.js')}}"></script> --}}
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
     integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
 </script>
 <script src="{{ asset('assets/theme_assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
 <script src="{{ asset('assets/theme_assets/assets/vendor/js/menu.js') }}"></script>
 {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> --}}
 <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
 <script src="https://cdn.datatables.net/2.1.5/js/dataTables.bootstrap5.js"></script>
 <script>
     // Initialize DataTable with loading animation
     $(document).ready(function() {
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

 {{-- @if (\Route::currentRouteName() === 'admin.create-trip' || \Route::currentRouteName() === 'admin.trip')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize CKEditor for all elements with the class "ckeditor"
        document.querySelectorAll('.ckeditor').forEach(element => {
            ClassicEditor
                .create(element)
                .then(editor => {
                    console.log('Editor was initialized', editor);

                    // Set initial data if needed (this assumes you have Livewire data or other source)
                    const initialData = element.getAttribute('data-initial-content');
                    if (initialData) {
                        editor.setData(initialData);
                    }

                    // Sync the CKEditor content with Livewire or other backend models
                    editor.model.document.on('change:data', () => {
                        const editorData = editor.getData();
                        
                        // Sync the CKEditor content with Livewire
                        if (window.Livewire) {
                            const livewireElement = element.closest('[wire\\:id]');
                            if (livewireElement) {
                                const componentId = livewireElement.getAttribute('wire:id');
                                window.Livewire.find(componentId).set(element.getAttribute('name'), editorData);
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error during initialization of the editor', error);
                });
        });
    });
</script>

@endif --}}

 @if (\Route::currentRouteName() === 'admin.create-trip')
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             // Initialize CKEditor for all elements with the class "ckeditor"
             document.querySelectorAll('.ckeditor').forEach(element => {
                 ClassicEditor
                     .create(element)
                     .then(editor => {

                         // Set initial data if needed
                         const initialData = element.getAttribute('data-initial-content');
                         if (initialData) {
                             editor.setData(initialData);
                         }

                         // Sync the CKEditor content with Livewire or other backend models
                         editor.model.document.on('change:data', () => {
                             const editorData = editor.getData();

                             // Sync the CKEditor content with Livewire
                             if (window.Livewire) {
                                 const livewireElement = element.closest('[wire\\:id]');
                                 if (livewireElement) {
                                     const componentId = livewireElement.getAttribute('wire:id');
                                     const propertyName = element.getAttribute('name');


                                     // Ensure propertyName is correct
                                     if (propertyName) {
                                         window.Livewire.find(componentId).set(
                                             `form.${propertyName}`, editorData);
                                     } else {
                                         console.error('Property name not found on element:',
                                             element);
                                     }
                                 } else {
                                     console.error('Livewire element not found for:', element);
                                 }
                             } else {
                                 console.error('Livewire is not available.');
                             }
                         });
                     })
                     .catch(error => {
                         console.error('Error during initialization of the editor', error);
                     });
             });
         });
     </script>
 @elseif(\Route::currentRouteName() === 'admin.trip')
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             // Initialize CKEditor for all elements with the class "ckeditor"
             document.querySelectorAll('.ckeditor').forEach(element => {
                 ClassicEditor
                     .create(element)
                     .then(editor => {

                         // Set initial data if needed
                         const initialData = element.getAttribute('data-initial-content');
                         if (initialData) {
                             editor.setData(initialData);
                         }

                         // Sync the CKEditor content with Livewire or other backend models
                         editor.model.document.on('change:data', () => {
                             const editorData = editor.getData();
                             const name = element.getAttribute('name');

                             // Sync the CKEditor content with Livewire
                             if (window.Livewire) {
                                 const livewireElement = element.closest('[wire\\:id]');
                                 if (livewireElement) {
                                     const componentId = livewireElement.getAttribute('wire:id');

                                     // Ensure the property name matches the public properties in the Livewire component
                                     if (name === 'tripDescription' || name ===
                                         'tripActivities') {
                                         window.Livewire.find(componentId).set(name, editorData);
                                     } else {
                                         console.error('Unexpected property name:', name);
                                     }
                                 } else {
                                     console.error('Livewire element not found for:', element);
                                 }
                             } else {
                                 console.error('Livewire is not available.');
                             }
                         });
                     })
                     .catch(error => {
                         console.error('Error during initialization of the editor:', error);
                     });
             });
         });
     </script>
 @endif




 <!-- endbuild -->

 <!-- Vendors JS -->


 {{-- <script src="{{ asset('assets/theme_assets/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script> --}}

 <!-- ALPINE EDITOR -->
 {{-- <script src="https://cdn.jsdelivr.net/gh/maxeckel/alpine-editor@0.3.1/dist/alpine-editor.min.js"></script> --}}


 {{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

       <!-- CKEDTITOR 5 -->
      <x-admincomponents.ckeditor-script/> --}}

 <!-- Initialize Quill editor -->
 {{-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

  <!-- Initialize Quill editor -->
  <script>
      const quill = new Quill('#editor', {
          theme: 'snow'
      });
  </script> --}}




 <!-- Main JS -->
 <script src="{{ asset('assets/theme_assets/assets/js/main.js') }}"></script>

 <!-- Page JS -->
 {{-- <script src="{{asset('assets/theme_assets/assets/js/dashboards-analytics.js')}}"></script> --}}

 {{-- <script src="../../../../../node_modules/preline/dist/preline.js"></script> --}}
 <script src="{{ asset('node_modules/preline/dist/preline.js') }}"></script>

 <!-- Place this tag before closing body tag for github widget button. -->
 <script async defer src="https://buttons.github.io/buttons.js"></script>
 @livewireScripts


 </body>

 </html>
