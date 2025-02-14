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
            <th scope="col">Status</th>
            <th scope="col">View</th>
            <th scope="col">Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trips as $trip)
            @php
                // Check if tripPhotos and landscapes are already arrays, if not decode them
                $tripPhotos = is_string($trip->tripPhoto) ? json_decode($trip->tripPhoto, true) : $trip->tripPhoto;
                $landscapes = is_string($trip->tripLandscape)
                    ? json_decode($trip->tripLandscape, true)
                    : $trip->tripLandscape;
            @endphp
            <tr>
                <td>
                    @if ($tripPhotos && is_array($tripPhotos) && count($tripPhotos) > 1)
                        <div id="carousel-{{ $trip->tripID }}" class="carousel slide" data-bs-ride="carousel"
                            data-bs-interval="false">
                            <div class="carousel-inner">
                                @foreach ($tripPhotos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $photo }}" class="d-block w-100" alt="Photo">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carousel-{{ $trip->tripID }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carousel-{{ $trip->tripID }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @elseif(isset($tripPhotos) && is_array($tripPhotos) && count($tripPhotos) === 1)
                        <img src="{{ $tripPhotos[0] }}" class="d-block w-100 card-img-top" />
                    @else
                        <img src="{{ asset('assets/images/image_placeholder.jpg') }}"
                            class="d-block w-100 card-img-top" />
                    @endif
                </td>
                <td>{{ $trip->tripLocation }}</td>

                <td>
                    @if (is_array($landscapes))
                        <div class="d-flex align-items-center">
                            @foreach ($landscapes as $landscape)
                                @switch($landscape)
                                    @case('Beach')
                                        <img src="{{ asset('assets/images/beach.png') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" data-bs-title="{{ $landscape }}"
                                            style="width: 40px; height: 40px; margin: 5px;" />
                                    @break

                                    @case('City')
                                        <img src="{{ asset('assets/images/buildings.png') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" data-bs-title="{{ $landscape }}"
                                            style="width: 40px; height: 40px; margin: 5px;" />
                                    @break

                                    @case('Country Side')
                                        <img src="{{ asset('assets/images/farm.png') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" data-bs-title="{{ $landscape }}"
                                            style="width: 40px; height: 40px; margin: 5px;" />
                                    @break

                                    @case('Mountainous')
                                        <img src="{{ asset('assets/images/mountain.png') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" data-bs-title="{{ $landscape }}"
                                            style="width: 40px; height: 40px; margin: 5px;" />
                                    @break

                                    @case('Forested')
                                        <img src="{{ asset('assets/images/forest.png') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" data-bs-title="{{ $landscape }}"
                                            style="width: 40px; height: 40px; margin: 5px;" />
                                    @break
                                @endswitch
                            @endforeach
                        </div>
                    @else
                        <span>No landscape information available</span>
                    @endif
                </td>

                @switch($trip->tripAvailability)
                    @case('available')
                        <td class="m-5 text-white badge rounded-pill bg-success">{{ $trip->tripAvailability }}</td>
                    @break

                    @case('coming soon')
                        <td class="m-5 text-white badge rounded-pill bg-warning">{{ $trip->tripAvailability }}</td>
                    @break

                    @case('unavailable')
                        <td class="m-5 text-white badge rounded-pill bg-danger">{{ $trip->tripAvailability }}</td>
                    @break
                @endswitch

                <td>{{ date('F jS, Y', strtotime($trip->tripStartDate)) }}</td>
                <td>{{ date('F jS, Y', strtotime($trip->tripEndDate)) }}</td>
                <td>{{ \Carbon\Carbon::parse($trip->tripStartDate)->diffInDays($trip->tripEndDate) }}</td>
                <td>${{ number_format($trip->tripPrice, 2) }}</td>
                <td>{{ date('F jS, Y', strtotime($trip->created_at)) }}</td>
                <td>{{ date('F jS, Y', strtotime($trip->updated_at)) }}</td>

                @switch($trip->active)
                    @case(true)
                        <td class="m-3 text-white badge rounded-pill bg-success">Active</td>
                    @break

                    @case(false)
                        <td class="m-3 text-white badge rounded-pill bg-secondary">Inactive</td>
                    @endswitch

                    <td>
                        <a href="{{ route('admin.trip', ['tripID' => ltrim($trip->tripID, '#trip_id_')]) }}"
                            class="btn btn-primary">
                            <i class="fa-solid fa-eye mr-2"></i> View
                        </a>
                    </td>

                    <td>
                        <form method="post" action="{{ route('admin.trip.delete', ['tripID' => $trip->tripID]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger text-white">
                                <i class="fa-solid fa-trash mr-2"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
