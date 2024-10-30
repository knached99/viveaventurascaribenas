@props(['transactionData'])
<div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
    <div class="card">
        <div class="row row-bordered g-0">
            <div class="col-lg-8">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Total Gross Revenue</h5>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="totalRevenue" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded bx-lg text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalRevenue">
                            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                            <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                    </div>
                </div>
                <div id="totalRevenueChart" class="px-3"></div>
            </div>
        </div>
    </div>
</div>
<!--/ Total Revenue -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const transactionData = @json($transactionData);

        // Prepare data for the ApexCharts line graph
        const dates = transactionData.map(item => item.date);
        const revenues = transactionData.map(item => item.amount);

        const totalRevenueChartEl = document.querySelector('#totalRevenueChart'),
            totalRevenueChartOptions = {
                series: [{
                    name: "Gross Revenue",
                    data: revenues
                }],
                chart: {
                    height: 317,
                    type: 'line',
                    toolbar: {
                        show: false
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: dates,
                    labels: {
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Public Sans',
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Public Sans',
                        }
                    }
                },
                colors: ['#7367f0'],
                grid: {
                    borderColor: '#ebebeb',
                }
            };

        if (typeof totalRevenueChartEl !== undefined && totalRevenueChartEl !== null) {
            const totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
            totalRevenueChart.render();
        }
    });
</script>
