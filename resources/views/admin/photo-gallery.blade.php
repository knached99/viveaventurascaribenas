<x-authenticated-theme-layout>
    <div class="row">
        <div class="card-body">
            <div class="col-sm-7">
                <div class="m-3">
                    <a href="{{ route('admin.photo-gallery-upload') }}"
                        class="bg-indigo-500 p-2 rounded text-white no-underline hover:bg-indigo-600">
                        Upload
                    </a>
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Upload Photo
                    </button> --}}
                </div>
            </div>
        </div>
    </div>

    @if (!empty($photos))
        <div class="card shadow-sm bg-white rounded">
            <h5 class="m-3">Your Photo Gallery</h5>

            @if (session('photo_deleted_from_gallery'))
                <div class="alert alert-success" role="alert">
                    {{ session('photo_deleted_from_gallery') }}
                </div>
            @endif

            <div class="table-responsive">

                <x-admincomponents.photo-gallery-component :photos="$photos" />
            </div>
        </div>
    @else
        <h3 class="text-secondary">No Photos in gallery, go ahead and upload one now</h3>
    @endif


</x-authenticated-theme-layout>

{{-- <script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('photoUploaded', () => {
            var modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
            modal.hide();  // This will close the modal once the photo is uploaded successfully
        });
    });
</script> --}}
