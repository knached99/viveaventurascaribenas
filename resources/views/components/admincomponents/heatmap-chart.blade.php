@props(['heatmapData'])

@php 
$geojsonPath = asset('assets/js/countries.geojson');
@endphp

<div id="heatmap" style="height: 500px; width: 100%;"></div>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('heatmap').setView([20, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var heatmapData = @json($heatmapData ?? []);

        if (heatmapData && Array.isArray(heatmapData) && heatmapData.length > 0) {
            var countryCounts = {};
            heatmapData.forEach(function (data) {
                countryCounts[data.country] = data.count;
            });

            fetch("{{ $geojsonPath }}")
                .then(response => response.json())
                .then(function (geojsonData) {
                    L.geoJson(geojsonData, {
                        style: function (feature) {
                            var country = feature.properties.ADMIN; // Country name from GeoJSON
                            var count = countryCounts[country] || 0;

                            // Define colors based on count
                            var color = count > 50 ? '#800026' :
                                        count > 20 ? '#BD0026' :
                                        count > 10 ? '#E31A1C' :
                                        count > 5 ? '#FC4E2A' :
                                        count > 0 ? '#FD8D3C' :
                                                    '#FFEDA0';

                            return {
                                fillColor: color,
                                weight: 1,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                            };
                        },
                        onEachFeature: function (feature, layer) {
                            var country = feature.properties.ADMIN;
                            var count = countryCounts[country] || 0;
                            layer.bindPopup(`<strong>${country}</strong><br>Visits: ${count}`);
                        }
                    }).addTo(map);
                })
                .catch(function (error) {
                    console.error('Error loading GeoJSON:', error);
                });
        } else {
            console.error("No valid heatmap data found.");
        }
    });
</script> --}}


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('heatmap').setView([20, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var heatmapData = @json($heatmapData ?? []);

        if (heatmapData && Array.isArray(heatmapData) && heatmapData.length > 0) {
            var countryCounts = {};
            heatmapData.forEach(function (data) {
                countryCounts[data.country] = data.count;
            });

            fetch("{{ $geojsonPath }}")
                .then(response => response.json())
                .then(function (geojsonData) {
                    L.geoJson(geojsonData, {
                        style: function (feature) {
                            var isoCode = feature.properties.ISO_A2; // Use ISO Alpha-2 code
                            var count = countryCounts[isoCode] || 0;

                            // Define colors based on count
                            var color = count > 50 ? '#e93e3a' :
                                        count > 20 ? '#ed683c' :
                                        count > 10 ? '#f3903f' :
                                        count > 5 ? '#fdc70c' :
                                        count > 0 ? '#fff' :
                                                    '#fff';

                            return {
                                fillColor: color,
                                weight: 1,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                            };
                        },
                        onEachFeature: function (feature, layer) {
                            var isoCode = feature.properties.ISO_A2;
                            var count = countryCounts[isoCode] || 0;
                            var countryName = feature.properties.ADMIN; // Full country name
                            layer.bindPopup(`<strong>${countryName}</strong><br>Visits: ${count}`);
                        }
                    }).addTo(map);
                })
                .catch(function (error) {
                    console.error('Error loading GeoJSON:', error);
                });
        } else {
            console.error("No valid heatmap data found.");
        }
    });
</script>

