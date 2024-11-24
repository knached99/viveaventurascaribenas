<x-authenticated-theme-layout>
    <div class="row">

        <!-- Visitor Devices Chart -->
        <div class="col-lg-12 mb-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Visitor Devices</h5>
                    <p class="card-subtitle mb-4">This chart shows the devices used by your users to access your site. It gives insight into whether most of your users come from mobile or desktop experiences.</p>
                    <x-admincomponents.user-agent-pie-chart :topBrowsers="$topBrowsers" :topOperatingSystems="$topOperatingSystems" />
                </div>
            </div>
        </div>

        <!-- Visitor World Heatmap Chart -->
        <div class="col-lg-12 mb-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">World Heatmap</h5>
                    <p class="card-subtitle mb-4">This map shows where your visitors come from, with darker areas indicating higher distribution of visitors.</p>
                    <x-admincomponents.heatmap-chart :heatmapData="$heatmapData" />
                </div>
            </div>
        </div>

    </div>
</x-authenticated-theme-layout>
