<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.ckeditor').forEach((editor) => {
            ClassicEditor
                .create(editor)
                .catch(error => console.error(error));
        });
    });

    Livewire.hook('message.processed', (message, component) => {
        document.querySelectorAll('.ckeditor').forEach((editor) => {
            if (!editor.ckeditorInstance) {
                ClassicEditor
                    .create(editor)
                    .catch(error => console.error(error));
            }
        });
    });
</script>
