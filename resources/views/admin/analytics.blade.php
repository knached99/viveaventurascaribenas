<x-authenticated-theme-layout>
    <div class="row">
        <!-- Unique Visitors Chart -->
        <div class="col-lg-6 mb-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Unique Visitors</h5>
                    <p class="card-subtitle mb-4">Filter by day, month, or year</p>
                    <div id="uniqueVisitorsChart"></div>
                    <!-- Script to initialize the chart -->
                    <script>
                        // Example initialization of a chart (use your preferred library, e.g., Chart.js, ApexCharts)
                        var ctx = document.getElementById('uniqueVisitorsChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], // Example labels
                                datasets: [{
                                    label: 'Unique Visitors',
                                    data: [65, 59, 80, 81, 56, 55, 40], // Example data
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    fill: true
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

        <!-- Unique Devices Chart -->
        <div class="col-lg-6 mb-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Unique Devices</h5>
                    <div id="uniqueDevicesChart"></div>
                    <!-- Script to initialize the chart -->
                    <script>
                        var ctx = document.getElementById('uniqueDevicesChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Desktop', 'Mobile', 'Tablet'], // Example labels
                                datasets: [{
                                    label: 'Unique Devices',
                                    data: [300, 150, 50], // Example data
                                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

        <!-- Unique IPs Chart -->
        <div class="col-lg-6 mb-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Unique IPs</h5>
                    <div id="uniqueIPsChart"></div>
                    <!-- Script to initialize the chart -->
                    <script>
                        var ctx = document.getElementById('uniqueIPsChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: ['IP1', 'IP2', 'IP3'], // Example labels
                                datasets: [{
                                    label: 'Unique IPs',
                                    data: [10, 20, 30], // Example data
                                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

        <!-- Visits and URLs Chart -->
        <div class="col-lg-6 mb-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Visits and URLs</h5>
                    <div id="visitsURLsChart"></div>
                    <!-- Script to initialize the chart -->
                    <script>
                        var ctx = document.getElementById('visitsURLsChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['URL1', 'URL2', 'URL3'], // Example labels
                                datasets: [{
                                    label: 'Visits',
                                    data: [500, 300, 200], // Example data
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

        <!-- Country Map with Heatmap -->
        <div class="col-lg-12 mb-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Country Map</h5>
                    <div id="countryMap"></div>
                    <!-- Script to initialize the map -->
                    <script>
                        // Example initialization of a map (use your preferred library, e.g., Leaflet, Google Maps)
                        // This is a basic setup; customize according to your data
                        var map = L.map('countryMap').setView([20, 0], 2);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        // Example heatmap setup
                        var heat = L.heatLayer([
                            [37.7749, -122.4194, 0.5], // Example data
                            [34.0522, -118.2437, 0.7]
                        ], {
                            radius: 25
                        }).addTo(map);
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-authenticated-theme-layout>
