@php
    $totalTransactions = count($transactionData);
    // $totalAmount = 0;

    // foreach ($transactions as $transaction) {
    //     $totalAmount += $transaction->amount / 100; // Stripe amounts are in cents
    // }

    // $formattedTotalAmount = number_format($totalAmount, 2);
@endphp
<x-authenticated-theme-layout>
    <div class="row">
        {{-- <div class="col-xxl-8 mb-6 order-0">
                  <div class="card">
                    <div class="d-flex align-items-start row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary mb-3">Welcome back, {{auth()->user()->name}} !</h5>
                          <p class="mb-6">
                            Manage all administrative activities from this dashboard.
                          </p>

                          <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-6">
                          <img
                            src="{{asset('assets/theme_assets/assets/img/illustrations/man-with-laptop.png')}}"
                            height="175"
                            class="scaleX-n1-rtl"
                            alt="View Badge User" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div> --}}

        <div class="col-xxl-8 mb-6 order-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="d-flex align-items-start row g-0">
                    <!-- Left Section: Text Content -->
                    <div class="col-sm-7">
                        <div class="card-body">
                            <!-- Title with Icon -->
                            <h5 class="card-title text-primary mb-4">
                                <i class="bx bx-bar-chart-alt-2 me-2"></i> Website Visitor Analytics
                            </h5>

                            <!-- Overview Text -->
                            <p class="text-muted mb-5">
                                Get a quick overview of your website's visitor statistics.
                            </p>

                            <!-- Visitor Count -->
                            <h6 class="mb-4 text-dark">
                                <i class="bx bx-user me-2"></i> Total Visitors:
                                <span class="fw-bold">{{ $visitors['total_visitors_count'] }}</span>
                            </h6>

                            <!-- Most Visited Page -->
                            <h6 class="text-dark">
                                <i class="bx bx-world me-2"></i> Most Visited Page:
                                <span class="fw-bold">
                                    <a href="{{ $visitors['most_visited_url'] }}" target="_blank"
                                        rel="noopener noreferrer">{{ $visitors['most_visited_url'] }}</a>
                                </span>
                            </h6>

                            <span>View detailed analytics <a href="{{ route('admin.analytics') }}">here</a></span>
                        </div>

                    </div>

                    <!-- Right Section: Image -->
                    <div class="col-sm-5 text-center text-sm-left d-flex align-items-center justify-content-center">
                        <div class="card-body pb-0 px-0 px-md-6">
                            <img src="{{ asset('assets/theme_assets/assets/img/illustrations/cloud-storage.webp') }}"
                                height="175" class="scaleX-n1-rtl img-fluid" alt="Storage Usage Illustration" />
                        </div>
                    </div>
                </div>
            </div>
        </div>





        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">

                <!-- Gross Profit -->
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/theme_assets/assets/img/icons/unicons/chart-success.png') }}"
                                        alt="chart success" class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Gross Profit</p>
                            <h4 class="card-title mb-3">${{ $grossProfit }}</h4>
                            <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +72.80%</small>
                        </div>
                    </div>
                </div>
                <!-- / Gross Profit -->

                <!-- Total Net Costs -->
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/theme_assets/assets/img/icons/unicons/chart-success.png') }}"
                                        alt="chart success" class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Total Net Costs</p>
                            <h4 class="card-title mb-3">${{ $totalNetCosts }}</h4>
                            <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +72.80%</small>
                        </div>
                    </div>
                </div>
                <!-- / Total Net Costs -->


                <!-- Net Profit -->
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/theme_assets/assets/img/icons/unicons/chart-success.png') }}"
                                        alt="chart success" class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Net Profit</p>
                            <h4 class="card-title mb-3">${{ $netProfit }}</h4>
                            <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +72.80%</small>
                        </div>
                    </div>
                </div>
                <!-- / Net Profit -->

                <!-- Total Transactions-->
                <div class="col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/theme_assets/assets/img/icons/unicons/cc-primary.png') }}"
                                        alt="Credit Card" class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Transactions</p>
                            <h4 class="card-title mb-3">{{ $totalTransactions }}</h4>
                            {{-- <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i></small> --}}
                        </div>
                    </div>
                </div>
                <!-- / Total Transactions -->

            </div>
        </div>
        <!-- Total Revenue Chart -->
        @if (!empty($transactionData))
            <x-admincomponents.total-revenue-chart :transactionData="$transactionData" />
        @else
        @endif
        <!-- / Total Revenue Chart -->

        <!-- Most Popular Booking -->
        <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
            <div class="row">

                @if (!empty($popularTrips))
                    @foreach ($popularTrips as $trip)
                        <div class="col-12 mb-6">
                            <div class="card hover:shadow-lg ease-in-out duration-300">
                                <div class="card-body">
                                    <div
                                        class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10">
                                        <div
                                            class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                            <a href={{ route('admin.trip', ['tripID' => $trip['id']]) }}>
                                                <div class="card-title mb-6">
                                                    <h5 class="text-nowrap mb-1">Most Popular Booking</h5>
                                                    <h4 style="font-size: 18px; display: inline-block">
                                                        {{ $trip['name'] }}</h4>
                                                    <img src="{{ $trip['image'] }}" />
                                                </div>
                                            </a>
                                            <div class="mt-sm-auto">
                                                <h5 class="mb-0">Booked {{ $trip['count'] }} times</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- / Most Popular Booking -->
            </div>
        </div>
        <!-- / Most Popular Booking -->

        <!-- Most Popular Reserved Trip -->

        <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
            <div class="row">

                @if (!empty($mostPopularReservedTripName))
                    @foreach ($mostReservedTrips as $trip)
                        <div class="col-12 mb-6">
                            <div class="card hover:shadow-lg ease-in-out duration-300">
                                <div class="card-body">
                                    <div
                                        class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10">
                                        <div
                                            class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                            <a href={{ route('admin.trip', ['tripID' => $trip['tripID']]) }}>
                                                <div class="card-title mb-6">
                                                    <h5 class="text-nowrap mb-1">Most Reserved Trip</h5>
                                                    <h4 style="font-size: 18px; display: inline-block">
                                                        {{ $trip['location'] }}</h4>
                                                    <img src="{{ $trip['image'] }}" />
                                                </div>
                                            </a>
                                            <div class="mt-sm-auto">
                                                <h5 class="mb-0">Reserved {{ $trip['count'] }} times</h5>
                                            </div>

                                            <div class="mt-sm-auto">
                                                <p class="font-semibold"> Most Preferred Dates: <span
                                                        class="text-indigo-500">{{ $trip['averageStartDate'] }} -
                                                        {{ $trip['averageEndDate'] }}</span></p>

                                                <p class="font-semibold">Most Preferred Number of Days:
                                                    <span class="text-indigo-500">{{ $trip['averageDateRange'] }}
                                                        days</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>

        <!-- / Most Popular Reserved Trip -->
    </div>
    <div class="row">
        <!-- Order Statistics -->
        {{-- <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-6">
              <div class="card h-100">
                  <div class="card-header d-flex justify-content-between">
                      <div class="card-title mb-0">
                          <h5 class="mb-1 me-2">Order Statistics</h5>
                          <p class="card-subtitle">42.82k Total Sales</p>
                      </div>
                      <div class="dropdown">
                          <button class="btn text-muted p-0" type="button" id="orederStatistics"
                              data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded bx-lg"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                              <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                              <a class="dropdown-item" href="javascript:void(0);">Share</a>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-6">
                          <div class="d-flex flex-column align-items-center gap-1">
                              <h3 class="mb-1">8,258</h3>
                              <small>Total Orders</small>
                          </div>
                          <div id="orderStatisticsChart"></div>
                      </div>
                      <ul class="p-0 m-0">
                          <li class="d-flex align-items-center mb-5">
                              <div class="avatar flex-shrink-0 me-3">
                                  <span class="avatar-initial rounded bg-label-primary"><i
                                          class="bx bx-mobile-alt"></i></span>
                              </div>
                              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                  <div class="me-2">
                                      <h6 class="mb-0">Electronic</h6>
                                      <small>Mobile, Earbuds, TV</small>
                                  </div>
                                  <div class="user-progress">
                                      <h6 class="mb-0">82.5k</h6>
                                  </div>
                              </div>
                          </li>
                          <li class="d-flex align-items-center mb-5">
                              <div class="avatar flex-shrink-0 me-3">
                                  <span class="avatar-initial rounded bg-label-success"><i
                                          class="bx bx-closet"></i></span>
                              </div>
                              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                  <div class="me-2">
                                      <h6 class="mb-0">Fashion</h6>
                                      <small>T-shirt, Jeans, Shoes</small>
                                  </div>
                                  <div class="user-progress">
                                      <h6 class="mb-0">23.8k</h6>
                                  </div>
                              </div>
                          </li>
                          <li class="d-flex align-items-center mb-5">
                              <div class="avatar flex-shrink-0 me-3">
                                  <span class="avatar-initial rounded bg-label-info"><i
                                          class="bx bx-home-alt"></i></span>
                              </div>
                              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                  <div class="me-2">
                                      <h6 class="mb-0">Decor</h6>
                                      <small>Fine Art, Dining</small>
                                  </div>
                                  <div class="user-progress">
                                      <h6 class="mb-0">849k</h6>
                                  </div>
                              </div>
                          </li>
                          <li class="d-flex align-items-center">
                              <div class="avatar flex-shrink-0 me-3">
                                  <span class="avatar-initial rounded bg-label-secondary"><i
                                          class="bx bx-football"></i></span>
                              </div>
                              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                  <div class="me-2">
                                      <h6 class="mb-0">Sports</h6>
                                      <small>Football, Cricket Kit</small>
                                  </div>
                                  <div class="user-progress">
                                      <h6 class="mb-0">99</h6>
                                  </div>
                              </div>
                          </li>
                      </ul>
                  </div>
              </div>
          </div> --}}
        <!--/ Order Statistics -->

        <!-- Expense Overview -->
        {{-- <div class="col-md-6 col-lg-4 order-1 mb-6">
              <div class="card h-100">
                  <div class="card-header nav-align-top">
                      <ul class="nav nav-pills" role="tablist">
                          <li class="nav-item">
                              <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                  data-bs-target="#navs-tabs-line-card-income"
                                  aria-controls="navs-tabs-line-card-income" aria-selected="true">
                                  Income
                              </button>
                          </li>
                          <li class="nav-item">
                              <button type="button" class="nav-link" role="tab">Expenses</button>
                          </li>
                          <li class="nav-item">
                              <button type="button" class="nav-link" role="tab">Profit</button>
                          </li>
                      </ul>
                  </div>
                  <div class="card-body">
                      <div class="tab-content p-0">
                          <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                              <div class="d-flex mb-6">
                                  <div class="avatar flex-shrink-0 me-3">
                                      <img src="{{ asset('assets/theme_assets/assets/img/icons/unicons/wallet.png') }}"
                                          alt="User" />
                                  </div>
                                  <div>
                                      <p class="mb-0">Total Balance</p>
                                      <div class="d-flex align-items-center">
                                          <h6 class="mb-0 me-1">$459.10</h6>
                                          <small class="text-success fw-medium">
                                              <i class="bx bx-chevron-up bx-lg"></i>
                                              42.9%
                                          </small>
                                      </div>
                                  </div>
                              </div>
                              <div id="incomeChart"></div>
                              <div class="d-flex align-items-center justify-content-center mt-6 gap-3">
                                  <div class="flex-shrink-0">
                                      <div id="expensesOfWeek"></div>
                                  </div>
                                  <div>
                                      <h6 class="mb-0">Income this week</h6>
                                      <small>$39k less than last week</small>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div> --}}
        <!--/ Expense Overview -->

        <!-- Transactions Go Here -->
        <x-admincomponents.transactions :bookings="$bookings" />

        <!-- Reservations -->
        <x-admincomponents.reservations-table :reservations="$reservations" />
    </div>
</x-authenticated-theme-layout>
