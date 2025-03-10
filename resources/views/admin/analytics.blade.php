<x-authenticated-theme-layout>
    <div class="row">

        <!-- Most Visited URL and total number of visitors -->
        <div class="col-lg-12 mb-6">
        <div class="card">
        <div class="card-body m-3 p-2">
        <div class="fs-4 block m-5">
        <span class="d-block fs-4">For performance reasons, we will refresh the data every week.</span>
        Data current as of {{ date('F jS, Y \a\t g:i A', strtotime($data_current_as_of)) }}  
    <span class="text-secondary d-block"> Data will be refreshed on {{ date('F jS, Y \a\t g:i A', strtotime($cache_expiration_date)) }}
</span>
    </div>

        <h5 class="card-title">
        Total Number of Visitors
        </h5>
        <p class="block"> {{$total_visitors_count}}</p>

        <h5 class="card-title">
        Most Visited URL 
        </h5>
        <a href="{{$most_visited_url}}" target="_blank" rel="noopener noreferrer">{{$most_visited_url}}</a>
        
        <h5 class="card-title">Top Referrer URL</h5>
        <p class="block">Shows which external websites or platforms drive the most visitors to your site</p>
       @if($topReferrerURL !== 'unknown')
        <button class="btn btn-outline-primary" onclick="redirectToURL('{{ $topReferrerURL }}')">
            {{ $topReferrerURL }}
        </button>
        @else 
        <span class="text-secondary">{{ $topReferrerURL }}</span>
        @endif
        </div>
        </div>
        <!-- / Most Visited URL and total number of visitors -->


            <!-- Most frequent bot and total number of bots -->
        <div class="col-lg-12 mb-6">
            <div class="card">
                <div class="card-body m-3 p-2">
                    <h5 class="card-title">Fake Traffic Overview</h5>
                    <p class="block">Fake traffic refers to non-human bot visits to your website, which can skew your analytics.</p>

                    <div class="mb-3">
                        <h6 class="card-title">Total Number of Bots</h6>
                        <p class="block">{{$totalBots}}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="card-title">Most Frequent Bot</h6>
                        <p class="block">{{$mostFrequentBot}}</p>
                    </div>

                    <!-- Percentage of fake vs real visitors -->
                    <div class="mb-3">
                        <h6 class="card-title">Percentage of Fake Traffic</h6>
                        <p class="block">{{number_format($botPercentage,2,".",".")}}%</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="card-title">Percentage of Real Traffic</h6>
                        <p class="block">{{number_format($realVisitorsPercentage,2,".",".")}}%</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Most frequent bot and total number of bots -->


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

            <!-- Color Legend -->
            <div class="mt-4">
                <h6>Visitor Density Legend</h6>
                <div class="d-flex flex-wrap align-items-center">
                    <div class="d-flex align-items-center me-3">
                        <div style="width: 20px; height: 20px; background-color: #6f00ff; margin-right: 8px;"></div> 
                        <span>50+ visitors</span>
                    </div>
                    <div class="d-flex align-items-center me-3">
                        <div style="width: 20px; height: 20px; background-color: #903bff; margin-right: 8px;"></div> 
                        <span>21-50 visitors</span>
                    </div>
                    <div class="d-flex align-items-center me-3">
                        <div style="width: 20px; height: 20px; background-color: #8fb4ff; margin-right: 8px;"></div> 
                        <span>11-20 visitors</span>
                    </div>
                    <div class="d-flex align-items-center me-3">
                        <div style="width: 20px; height: 20px; background-color: #b5cdff; margin-right: 8px;"></div> 
                        <span>6-10 visitors</span>
                    </div>
                    <div class="d-flex align-items-center me-3">
                        <div style="width: 20px; height: 20px; background-color: #edf3ff; margin-right: 8px;"></div> 
                        <span>1-5 visitors</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div style="width: 20px; height: 20px; background-color: black; margin-right: 8px;"></div> 
                        <span>No visitors</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    </div>
   @once
@push('scripts')
<script>
  /**
   * Checks if the given URL is considered safe by querying urlscan.io for prior scans.
   *
   * @param {string} url - The URL to check.
   * @returns {Promise<boolean>} - Returns true if safe, false otherwise.
   */
  async function checkUrlSafety(url) {
    // Validate URL syntax and protocol.
    try {
      const parsedUrl = new URL(url);
      if (parsedUrl.protocol !== 'http:' && parsedUrl.protocol !== 'https:') {
        console.error('Invalid protocol. Only HTTP and HTTPS are allowed.');
        return false;
      }
    } catch (error) {
      console.error('Invalid URL format:', error);
      return false;
    }
  
    // Query urlscan.io's search API for previous scan results.
    try {
      const apiEndpoint = `https://urlscan.io/api/v1/search/?q=url:${encodeURIComponent(url)}`;
      const response = await fetch(apiEndpoint);
      if (!response.ok) {
        console.error('Failed to fetch scan results from urlscan.io.');
        return false;
      }
      const data = await response.json();
  
      // Check each scan result for malicious or suspicious verdicts.
      if (data.results && data.results.length > 0) {
        for (const result of data.results) {
          if (result.verdicts) {
            if (result.verdicts.malicious || result.verdicts.suspicious) {
              console.error('URL flagged as unsafe by urlscan.io.');
              return false;
            }
          }
        }
      }
      // If no concerning verdict is found, consider the URL safe.
      return true;
    } catch (error) {
      console.error('Error while checking URL safety:', error);
      return false;
    }
  }
  
  /**
   * Redirects the user to the specified URL in a new tab after verifying its safety.
   *
   * @param {string} url - The URL to redirect to.
   */
  async function redirectToURL(url) {
    const isSafe = await checkUrlSafety(url);
    if (!isSafe) {
      alert('The URL provided is not safe to visit.');
      return;
    }
  
    // Create an anchor element with target="_blank" and secure rel attributes.
    const anchor = document.createElement('a');
    anchor.href = url;
    anchor.target = '_blank';
    anchor.rel = 'noopener noreferrer';
  
    // Append the anchor, simulate a click, then remove it.
    document.body.appendChild(anchor);
    anchor.click();
    document.body.removeChild(anchor);
  }
  
  // Expose the function to the global scope
  window.redirectToURL = redirectToURL;
</script>
@endpush 
@endonce
</x-authenticated-theme-layout>
