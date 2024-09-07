<script>
    document.querySelectorAll('.editor').forEach(editorElement => {
        ClassicEditor
            .create(editorElement)
            .catch(error => {
                console.error(error);
            });
    });
</script>
