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
 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
 
<script src=" https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
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

          layout: {
                topStart: {
                    buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
                }
            }
         });
     });
 </script>


 @if (\Route::currentRouteName() === 'admin.create-trip' || \Route::currentRouteName() === 'admin.trip')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor for all elements with the class "ckeditor"
        document.querySelectorAll('.ckeditor').forEach(element => {
            ClassicEditor
                .create(element, {
                    // Additional configuration options for the editor

                    toolbar: [
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', 
                        'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo', 'fontColor', 'fontBackgroundColor',
                    ],
                    // Enable fontColor plugin for the color wheel
                    fontSize: {
                        options: [
                            'tiny',
                            'default', 
                            'medium',
                            'big',
                        ],
                    },
   fontColor: {
            colors: [
                
                  {
                    color: 'hsl(222, 47%, 11%)',
                    label: 'Dark Slate',
                },

                {
                    color: 'hsl(221, 39%, 11%)',
                    label: 'Dark Gray',
                },

                {
                 color: 'hsl(226, 57%, 21%)',
                 label: 'Dark Blue',
                },

                {
                  color: 'hsl(272, 72%, 47%)',
                  label: 'Grimace Shake Purple',
                },

                {
                    color: 'hsl(0, 0%, 0%)',
                    label: 'Black'
                },
                {
                    color: 'hsl(0, 0%, 30%)',
                    label: 'Dim grey'
                },
                {
                    color: 'hsl(0, 0%, 60%)',
                    label: 'Grey'
                },
                {
                    color: 'hsl(0, 0%, 90%)',
                    label: 'Light grey'
                },
                {
                    color: 'hsl(0, 0%, 100%)',
                    label: 'White',
                    hasBorder: true
                },
                // More colors.
                // ...
            ]
        },
        fontBackgroundColor: {
            colors: [
                
                {
                    color: 'hsl(222, 47%, 11%)',
                    label: 'Dark Slate',
                },

                {
                    color: 'hsl(221, 39%, 11%)',
                    label: 'Dark Gray',
                },

                {
                 color: 'hsl(226, 57%, 21%)',
                 label: 'Dark Blue',
                },

                {
                  color: 'hsl(272, 72%, 47%)',
                  label: 'Grimace Shake Purple',
                },

                {
                    color: 'hsl(0, 75%, 60%)',
                    label: 'Red'
                },
                {
                    color: 'hsl(30, 75%, 60%)',
                    label: 'Orange'
                },
                {
                    color: 'hsl(60, 75%, 60%)',
                    label: 'Yellow'
                },
                {
                    color: 'hsl(90, 75%, 60%)',
                    label: 'Light green'
                },
                {
                    color: 'hsl(120, 75%, 60%)',
                    label: 'Green'
                },
              
            ]
        },
                    // Enable font plugin to provide font color options in the toolbar
                    fontFamily: {
                        options: [
                            'default', 'Arial', 'Courier New', 'Georgia', 'Times New Roman', 'Verdana'
                        ]
                    }
                })
                .then(editor => {
                    // No need to inject <style> tags here for color - CKEditor will handle it
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
                                    console.error('Property name not found on element:', element);
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

{{-- 
 @elseif(\Route::currentRouteName() === 'admin.trip')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor for all elements with the class "ckeditor"
        document.querySelectorAll('.ckeditor').forEach(element => {
            ClassicEditor
                .create(element, {
                    // Additional configuration options for the editor
                    toolbar: [
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', 
                        'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo', 'fontColor'
                    ],
                    // Enable fontColor plugin for the color wheel
                    fontSize: {
                        options: [
                            'tiny',
                            'default', 
                            'medium',
                            'big',
                        ],
                    },
   fontColor: {
            colors: [
                
                  {
                    color: 'hsl(222, 47%, 11%)',
                    label: 'Dark Slate',
                },

                {
                    color: 'hsl(221, 39%, 11%)',
                    label: 'Dark Gray',
                },

                {
                 color: 'hsl(226, 57%, 21%)',
                 label: 'Dark Blue',
                },

                {
                  color: 'hsl(272, 72%, 47%)',
                  label: 'Grimace Shake Purple',
                },
                
                {
                    color: 'hsl(0, 0%, 0%)',
                    label: 'Black'
                },
                {
                    color: 'hsl(0, 0%, 30%)',
                    label: 'Dim grey'
                },
                {
                    color: 'hsl(0, 0%, 60%)',
                    label: 'Grey'
                },
                {
                    color: 'hsl(0, 0%, 90%)',
                    label: 'Light grey'
                },
                {
                    color: 'hsl(0, 0%, 100%)',
                    label: 'White',
                    hasBorder: true
                },
                // More colors.
                // ...
            ]
        },
        fontBackgroundColor: {
            colors: [
                
                {
                    color: 'hsl(222, 47%, 11%)',
                    label: 'Dark Slate',
                },

                {
                    color: 'hsl(221, 39%, 11%)',
                    label: 'Dark Gray',
                },

                {
                 color: 'hsl(226, 57%, 21%)',
                 label: 'Dark Blue',
                },

                {
                  color: 'hsl(272, 72%, 47%)',
                  label: 'Grimace Shake Purple',
                },

                {
                    color: 'hsl(0, 75%, 60%)',
                    label: 'Red'
                },
                {
                    color: 'hsl(30, 75%, 60%)',
                    label: 'Orange'
                },
                {
                    color: 'hsl(60, 75%, 60%)',
                    label: 'Yellow'
                },
                {
                    color: 'hsl(90, 75%, 60%)',
                    label: 'Light green'
                },
                {
                    color: 'hsl(120, 75%, 60%)',
                    label: 'Green'
                },
              
            ]
        },
                    // Enable font plugin to provide font color options in the toolbar
                    fontFamily: {
                        options: [
                            'default', 'Arial', 'Courier New', 'Georgia', 'Times New Roman', 'Verdana'
                        ]
                    }
                })
                .then(editor => {
                    // No need to inject <style> tags here for color - CKEditor will handle it
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
                                    console.error('Property name not found on element:', element);
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
</script> --}}

 @endif




 <!-- endbuild -->



 <!-- Main JS -->
 <script src="{{ asset('assets/theme_assets/assets/js/main.js') }}"></script>

 <!-- Page JS -->
 {{-- <script src="{{asset('assets/theme_assets/assets/js/dashboards-analytics.js')}}"></script> --}}


 <!-- Place this tag before closing body tag for github widget button. -->
 <script async defer src="https://buttons.github.io/buttons.js"></script>
 @livewireScripts


 </body>

 </html>
