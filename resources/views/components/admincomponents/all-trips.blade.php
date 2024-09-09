@props(['trips'])
<table class="table dataTable">
    <thead>
        <tr>
            <th scope="col">Image</th>
            <th scope="col">Location</th>
            <th scope="col">Landscape</th>
            <th scope="col">Availability</th>
            <th scope="col">Start Date</th>
            <th scope="col">End Date</th>
            <th scope="col">Number of Days</th>
            <th scope="col">Price (per person)</th>
            <th scope="col">Created At</th>
            <th scope="col">Updated At</th>
            <th scope="col">View</th>
            <th scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trips as $trip)
            @php
                $tripPhotos = json_decode($trip->tripPhoto, true);
            @endphp
            <tr>
                <td>
                    @if($tripPhotos && is_array($tripPhotos) && count($tripPhotos) > 1)
                        <div id="carousel-{{ $trip->tripID }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($tripPhotos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $photo }}" class="d-block w-100" alt="Photo">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $trip->tripID }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $trip->tripID }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @elseif($tripPhotos && is_array($tripPhotos) && count($tripPhotos) === 1)
                        <img src="{{ $tripPhotos[0] }}" class="img-thumbnail rounded" style="width: 100px; height: 100px;" />
                    @else
                        <img src="{{ asset('assets/images/image_placeholder.jpg') }}" class="img-thumbnail rounded" style="width: 100px; height: 100px;" />
                    @endif
                </td>
                <td>{{ $trip->tripLocation }}</td>
                @switch($trip->tripLandscape)
                    @case('Beach')
                        <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="{{ $trip->tripLandscape }}"><img src="{{ asset('assets/images/beach.png') }}" style="width: 40px; height: 40px; margin: 5px;" /></td>
                    @break
                    @case('City')
                        <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="{{ $trip->tripLandscape }}"><img src="{{ asset('assets/images/buildings.png') }}" style="width: 40px; height: 40px; margin: 5px;" /></td>
                    @break
                    @case('Country Side')
                        <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="{{ $trip->tripLandscape }}"><img src="{{ asset('assets/images/farm.png') }}" style="width: 40px; height: 40px; margin: 5px;" /></td>
                    @break
                    @case('Mountainous')
                        <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="{{ $trip->tripLandscape }}"><img src="{{ asset('assets/images/mountain.png') }}" style="width: 40px; height: 40px; margin: 5px;" /></td>
                    @break
                    @case('Forested')
                        <td data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="{{ $trip->tripLandscape }}"><img src="{{ asset('assets/images/forest.png') }}" style="width: 40px; height: 40px; margin: 5px;" /></td>
                    @break
                @endswitch
                @switch($trip->tripAvailability)
                    @case('available')
                        <td class="m-3 text-white badge rounded-pill bg-success">{{ $trip->tripAvailability }}</td>
                    @break
                    @case('coming soon')
                        <td class="m-3 text-white badge rounded-pill bg-warning">{{ $trip->tripAvailability }}</td>
                    @break
                    @case('unavailable')
                        <td class="m-3 text-white badge rounded-pill bg-danger">{{ $trip->tripAvailability }}</td>
                    @break
                @endswitch
                <td>{{ date('F jS, Y', strtotime($trip->tripStartDate)) }}</td>
                <td>{{ date('F jS, Y', strtotime($trip->tripEndDate)) }}</td>
                <td>{{ \Carbon\Carbon::parse($trip->tripStartDate)->diffInDays($trip->tripEndDate) }}</td>
                <td>${{ number_format($trip->tripPrice, 2) }}</td>
                <td>{{ date('F jS, Y', strtotime($trip->created_at)) }}</td>
                <td>{{ date('F jS, Y', strtotime($trip->updated_at)) }}</td>
                <td>
                    <a href="{{ route('admin.trip', ['tripID' => $trip->tripID]) }}" class="text-decoration-underline">
                        View
                    </a>
                </td>
                <td>
                    <form method="post" action="{{ route('admin.trip.delete', ['tripID' => $trip->tripID]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger text-white">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
