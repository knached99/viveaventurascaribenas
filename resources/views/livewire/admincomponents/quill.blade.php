<div>
    <!-- Include stylesheet -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
 
    <!-- Create the editor container -->
    <div id="{{ $quillId }}" wire:ignore></div>
 
    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
 
    <!-- Initialize Quill editor -->
    <script>
        document.addEventListener('livewire:load', function () {
            const quill = new Quill('#{{ $quillId }}', {
                theme: 'snow'
            });

            // Set initial content
            quill.root.innerHTML = @json($value);

            quill.on('text-change', function () {
                let value = quill.root.innerHTML;
                @this.set('value', value);
            });
        });
    </script>
</div>
