<script>
    // Function to destroy existing CKEditor instances
    function destroyCKEditorInstances() {
        document.querySelectorAll('.ckeditor').forEach((editor) => {
            if (editor.ckeditorInstance) {
                editor.ckeditorInstance.destroy().catch(error => console.error(
                    'Error destroying CKEditor instance:', error));
                editor.ckeditorInstance = null;
            }
        });
    }

    // Function to initialize CKEditor on elements with the class 'ckeditor'
    function initializeCKEditors() {
        document.querySelectorAll('.ckeditor').forEach((editor) => {
            if (!editor.ckeditorInstance) {
                ClassicEditor
                    .create(editor)
                    .then(editorInstance => {
                        editor.ckeditorInstance = editorInstance; // Store the instance on the element
                    })
                    .catch(error => console.error('Error initializing CKEditor:', error));
            }
        });
    }

    // Initialize CKEditor on page load
    document.addEventListener("DOMContentLoaded", function() {
        initializeCKEditors();
    });

    // Check if Livewire is defined before using it
    function setupLivewireHook() {
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('message.processed', (message, component) => {
                // Destroy existing instances first
                destroyCKEditorInstances();
                // Add a slight delay before re-initializing CKEditor
                setTimeout(initializeCKEditors, 100);
            });
        } else {
            console.warn('Livewire is not defined.');
        }
    }

    // Setup Livewire hook once Livewire is loaded
    document.addEventListener("DOMContentLoaded", function() {
        setupLivewireHook();
    });
</script>
