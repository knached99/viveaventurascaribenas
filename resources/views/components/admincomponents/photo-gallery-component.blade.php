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
            <th scope="col">View</th>
            <th scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($photos as $photo)
            @php
                $photosArray = json_decode($photos->tripPhotos, true);
            @endphp
            <tr>
                <td>
                    @if ($photosArray && is_array($photosArray) && count($photosArray) > 1)
                        <div id="carousel-{{ $photo->photoID }}" class="carousel slide" data-bs-ride="carousel"
                            data-bs-interval="false">
                            <div class="carousel-inner">
                                @foreach ($photosArray as $index => $photoArray)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $photoArray }}" class="d-block w-100" alt="Photo">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carousel-{{ $photo->photoID }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carousel-{{ $photo->photoID }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @elseif(isset($photosArray) && is_array($photosArray) && count($photosArray) === 1)
                        <img src="{{ $photosArray[0] }}" class="img-thumbnail rounded"
                            style="width: 100px; height: 100px;" />
                    @else
                        <img src="{{ asset('assets/images/image_placeholder.jpg') }}" class="img-thumbnail rounded"
                            style="width: 100px; height: 100px;" />
                    @endif
                </td>
              
                <td>{{$photo->label}}</td>
                <td class="text-truncate">{{$photo->description}}</td>
                <td>{{$photo->trip->tripLocation}}</td>
                <td>{{ date('F jS, Y', strtotime($photo->created_at)) }}</td>
                <td>{{ date('F jS, Y', strtotime($photo->updated_at)) }}</td>

                    <td>
                        <a href="#" class="text-decoration-underline">
                            View
                        </a>
                    </td>
                    <td>
                        <form method="post" action="#">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger text-white">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
