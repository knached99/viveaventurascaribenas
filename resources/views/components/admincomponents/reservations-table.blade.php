      @props(['reservations'])
      <!-- Reservations -->
      <div class="col order-2 mb-6">
          <div class="card h-100">
              <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title m-0 me-2">Reservations</h5>
                  <div class="dropdown">
                      <button class="btn text-muted p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                          aria-haspopup="true" aria-expanded="false">
                          <i class="bx bx-dots-vertical-rounded bx-lg"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                          <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                          <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                          <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                      </div>
                  </div>
              </div>
              <div class="card-body pt-4">
                  <table class="table table-striped p-0 m-0 dataTable">
                      <thead>
                          <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone Number</th>
                              <th>Street Address</th>
                              <th>Address 2</th>
                              <th>City</th>
                              <th>State </th>
                              <th>Zipcode</th>
                              <th>Location Booked</th>
                              <th>View Reservation</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($reservations as $reservation)
                              @php
                                  $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
                                  $product = $stripe->products->retrieve($reservation->stripe_product_id);
                                  $location = $product->name;

                              @endphp
                              <tr>
                                 <td>{{$reservation->name}}</td>
                                 <td>{{$reservation->email}}</td>
                                 <td>{{$reservation->phone_number}}</td>
                                 <td>{{$reservation->address_line_1}}</td>
                                 <td>{{$reservation->address_line_2}}</td>
                                 <td>{{$reservation->city}}</td>
                                 <td>{{$reservation->state}}</td>
                                 <td>{{$reservation->zip_code}}</td>
                                 <td>{{$location}}</td>
                                 <td><a href="#">View</td>
                              </tr>
                          @endforeach
                      </tbody>

                  </table>

              </div>
          </div>
      </div>
      <!--/ Reservations -->
