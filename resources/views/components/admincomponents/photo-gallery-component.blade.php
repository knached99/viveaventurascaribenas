@props(['photos'])
<table class="table dataTable">
    <thead>
        <tr>
            <th scope="col">Image</th>
            <th scope="col">Label</th>
            <th scope="col">Description</th>
            <th scope="col">Associated Trip</th>
            <th scope="col">Created At</th>
            <th scope="col">Updated At</th>
            <th scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        @if (session('delete_success'))
            <div class="alert alert-success">
                {{ session('delete_success') }}
            </div>
        @elseif(session('delete_error'))
            <div class="alert alert-danger">
                {{ session('delete_error') }}
            </div>
        @endif
        @foreach ($photos as $photo)
            @php
                $photosArray = json_decode($photo['photos'], true);
            @endphp
            <tr>
                <td>
                    @if ($photosArray && is_array($photosArray) && count($photosArray) > 1)
                        <div id="carousel-{{ $photo['photoID'] }}" class="carousel slide" data-bs-ride="carousel"
                            data-bs-interval="false">
                            <div class="carousel-inner">
                                @foreach ($photosArray as $index => $photoArray)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $photoArray }}" class="d-block"
                                            style="width: auto; height: 200px;" alt="Photo">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carousel-{{ $photo['photoID'] }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carousel-{{ $photo['photoID'] }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @elseif(isset($photosArray) && is_array($photosArray) && count($photosArray) === 1)
                        <img src="{{ asset($photosArray[0]) }}" class="img-thumbnail rounded"
                            style="width: 60; height: 60;" />
                    @else
                        <img src="{{ asset('assets/images/image_placeholder.jpg') }}" class="img-thumbnail rounded"
                            style="width: 100px; height: 100px;" />
                    @endif
                </td>

                <td>{{ $photo['photoLabel'] }}</td>
                <td class="text-truncate">{{ $photo['photoDescription'] }}</td>
                <td>{{ $photo['trip']['tripLocation'] }}</td>
                <td>{{ date('F jS, Y', strtotime($photo['created_at'])) }}</td>
                <td>{{ date('F jS, Y', strtotime($photo['updated_at'])) }}</td>


                <td>
                    <form method="post"
                        action="{{ route('admin.deletePhotosFromGallery', ['photoID' => $photo['photoID']]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger text-white">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
