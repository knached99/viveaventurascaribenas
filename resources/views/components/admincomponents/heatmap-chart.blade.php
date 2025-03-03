@props(['heatmapData'])

@php 
$geojsonPath = asset('assets/js/countries.geojson');
@endphp

<div id="heatmap" style="height: 500px; width: 100%;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Use a dark-themed tile layer for an "ocean black" look
        var map = L.map('heatmap').setView([20, 0], 2);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors & CartoDB'
        }).addTo(map);

        var heatmapData = @json($heatmapData ?? []);

        if (heatmapData && Array.isArray(heatmapData) && heatmapData.length > 0) {
            var countryCounts = {};
            heatmapData.forEach(function (data) {
                // Assuming the data.country value is an ISO code for better matching
                countryCounts[data.country] = data.count;
            });

            fetch("{{ $geojsonPath }}")
                .then(response => response.json())
                .then(function (geojsonData) {
                    L.geoJson(geojsonData, {
                        style: function (feature) {
                            var isoCode = feature.properties.ISO_A2; // Use ISO Alpha-2 code
                            var count = countryCounts[isoCode] || 0;

                          // Define colors based on count using different shades of purple
                            var color = count > 50 ? '#4B0082' : // indigo
                            count > 20 ? '#6A0DAD' : // dark purple
                            count > 10 ? '#800080' : // classic purple
                            count > 5  ? '#9932CC' : // medium purple
                            count > 0  ? '#BA55D3' : // light purple
                                        '#000000';  // fallback: pure black


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
