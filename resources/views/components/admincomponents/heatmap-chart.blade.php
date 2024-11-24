@props(['heatmapData'])

<div id="heatmap" style="height: 500px; width: 100%;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize the map
        var map = L.map('heatmap').setView([20, 0], 2); // Global view

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Heatmap data from Laravel
        var heatmapData = @json($heatmapData ?? []);

        if (heatmapData && Array.isArray(heatmapData) && heatmapData.length > 0) {
            // Country-to-count lookup
            var countryCounts = {};
            heatmapData.forEach(function (data) {
                countryCounts[data.country] = data.count;
            });

            // Load country polygons (GeoJSON from the 'public/assets/js' folder)
            fetch('/assets/js/countries.geojson')  // Correct path to your GeoJSON file
                .then(response => response.json())
                .then(function (geojsonData) {
                    // Add GeoJSON layer
                    L.geoJson(geojsonData, {
                        style: function (feature) {
                            var country = feature.properties.ADMIN;
                            var count = countryCounts[country] || 0;

                            // Color scale based on visit count
                            var color = count > 50 ? '#800026' :
                                        count > 20 ? '#BD0026' :
                                        count > 10 ? '#E31A1C' :
                                        count > 5 ? '#FC4E2A' :
                                        count > 0 ? '#FD8D3C' :
                                                    '#FFEDA0';

                            return {
                                fillColor: color,
                                weight: 2,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                            };
                        },
                        onEachFeature: function (feature, layer) {
                            var country = feature.properties.ADMIN; // Country name in GeoJSON
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
</script>

