@props(['topBrowsers', 'topOperatingSystems'])

<div id="userAgentPieChart"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const topBrowsers = @json($topBrowsers);
        const topOperatingSystems = @json($topOperatingSystems);

        // Log to verify the data structure
        console.log("Top Browsers:", topBrowsers);
        console.log("Top Operating Systems:", topOperatingSystems);

        const options = {
            chart: {
                type: 'pie',
                height: 350,
            },
            series: [
                ...Object.values(topBrowsers), // Browser visit counts
                ...Object.values(topOperatingSystems) // OS visit counts
            ],
            labels: [
                ...Object.keys(topBrowsers), // Browser names
                ...Object.keys(topOperatingSystems) // OS names
            ],
            title: {
                text: 'Top Browsers and Operating Systems',
                align: 'center'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 250
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        const chart = new ApexCharts(document.querySelector("#userAgentPieChart"), options);
        chart.render();
    });
</script>
