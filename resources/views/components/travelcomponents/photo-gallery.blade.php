@props(['photos'])
@php

@endphp

<!-- Photo Grid -->
<div class="header">
    <h1>Photo Gallery</h1>
    <h5>Welcome to the Photo Gallery! Here, you can browse through a curated collection of captivating images captured
        during various trips and adventures. Use the gallery to explore the stunning visuals, featuring different
        locations, activities, and moments from our travelers' experiences.</h5>
</div>

<!-- Photo Grid -->
<div class="row">

    @foreach ($photos as $photo)
        @php
            $photoURLs = [];
            // Decode Photos
            $decodedPhotos = json_decode($photo->photos, true);

            // Handle single or multiple photos
            if (is_null($decodedPhotos)) {
                // If it's a single photo string
    $photoURLs[] = $photo->photos;
} else {
    // If it's a JSON array of photo URLs, merge them into $photoURLs
                $photoURLs = array_merge($photoURLs, $decodedPhotos);
            }
        @endphp

        @foreach ($photoURLs as $photoURL)
            <div class="column hover:ease-in-out hover:duration:300 relative">
                <a href="{{ route('landing.destination', ['slug' => $photo->trip->slug]) }}">
                    <img src="{{ asset($photoURL) }}" alt="Photo" style="width: 100%;" />
                    <div class="overlay">
                        <h4 class="text-white">{{ $photo->photoLabel }}</h4>
                        <p class="text-lg">{{ $photo->photoDescription }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    @endforeach

</div>


</div>
