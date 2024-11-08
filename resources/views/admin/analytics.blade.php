<x-authenticated-theme-layout>
    <div class="row">
        <!-- Visits and URLs Table -->
        <div class="col-lg-12 mb-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Visits and URL Details</h5>
                    <p class="card-subtitle mb-4">Detailed table showing URLs visited, the number of visits, referrers,
                        IP addresses, devices, browsers, and countries.</p>
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>URL</th>
                                <th>Number of Visits</th>
                                <th>Referrers</th>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th>Browser</th>
                                <th>Region/State</th>
                                <th>City</th>
                                <th>Country</th>
                                <th>Date Visited</th>
                                <th>Map Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($urlData as $url)
                                <tr>
                                    <td><a rel="noopener noreferrer" target="_blank"
                                            href="{{ config('app.url') . ':8000' . $url->visited_url }}">{{ $url->visited_url }}</a>
                                    </td>
                                    <td>{{ $url->visit_count }}</td>
                                    <td><a href={{ $url->visitor_referrer ?? '#' }} rel="noopener noreferrer"
                                            target="_blank">{{ $url->visitor_referrer }}</a>
                                    </td>
                                    <td>{{ Crypt::decryptString($url->visitor_ip_address) ?? 'N/A' }}</td>
                                    <td>{{ $url->operating_system ?? 'N/A' }}</td>
                                    <td>{{ $url->browser ?? 'N/A' }}</td>
                                    <td>{{ $url->state ?? 'N/A' }}</td>
                                    <td>{{ $url->city ?? 'N/A' }}</td>
                                    <td>{{ $url->country ?? 'N/A' }}</td>
                                    <td>{{ date('F jS, Y, \a\t g:i A', strtotime($url->created_at)) }}</td>
                                    <td>
                                        @if (empty($url->latitude) || empty($url->longitude))
                                            <span>Map Unavailable</span>
                                        @else
                                            <a href="https://www.openstreetmap.org/?mlat={{ $url->latitude }}&mlon={{ $url->longitude }}#map=17/"
                                                target="_blank" rel="noreferrer noopener">View Location</a>
                                    </td>
                            @endif



                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-12 mb-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Visitor Devices</h5>
                    <p class="card-subtitle mb-4">This chart shows the devices used by your users to access your site.
                        This gives you insight to knowing whether or not most of your users come from a mobile
                        experience or desktop experience</p>
                    <x-admincomponents.user-agent-pie-chart :topBrowsers="$topBrowsers" :topOperatingSystems="$topOperatingSystems" />
                </div>
            </div>
        </div>



        <!-- Visitor Heatmap Table -->
        {{-- <div class="col-lg-12 mb-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Visitor Density by Country</h5>
                    <p class="card-subtitle mb-4">Table showing visitor density across different countries.</p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Visitor Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($heatmapData as $data)
                                <tr>
                                    <td>{{ $data->country }}</td>
                                    <td>{{ $data->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}
        <!-- /Visitor Heatmap Table -->

    </div>
</x-authenticated-theme-layout>
