@props(['bookings', 'productMap'])

<div class="col order-2 mb-6">
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Bookings</h5>
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
                        <th>Booked Location</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Card Used</th>
                        <th>Card Expiration</th>
                        <th>Card Last 4</th>
                        <th>Receipt Link</th>
                        <th>Booking Link</th>
                        <th>Booked At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        @php
                            // Retrieve location from productMap
                            $location = $productMap[$booking->stripe_product_id] ?? 'Unknown';

                            // Initialize default values
                            $paymentMethod = 'N/A';
                            $paymentAmount = '$0.00';
                            $paymentStatus = 'N/A';
                            $cardExpirationMonth = 'N/A';
                            $cardExpirationYear = 'N/A';
                            $cardLast4 = 'N/A';
                            $paymentMethodCard = 'N/A';
                            $receiptLink = 'N/A';

                            if (!empty($booking->stripe_checkout_id)) {
                                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
                                $checkoutSession = $stripe->checkout->sessions->retrieve($booking->stripe_checkout_id);

                                if ($checkoutSession && !empty($checkoutSession->payment_intent)) {
                                    $paymentIntent = $checkoutSession->payment_intent;
                                    $charges = $stripe->charges->all(['payment_intent' => $paymentIntent]);

                                    if (count($charges->data) > 0) {
                                        $charge = $charges->data[0];
                                        $paymentMethod = $charge->payment_method_details->type;

                                        switch ($paymentMethod) {
                                            case 'card':
                                                $cardExpirationMonth = $charge->payment_method_details->card->exp_month;
                                                $cardExpirationYear = $charge->payment_method_details->card->exp_year;
                                                $cardLast4 = $charge->payment_method_details->card->last4;
                                                $paymentMethodCard = $charge->payment_method_details->card->brand;
                                                break;

                                            case 'cashapp':
                                                $paymentMethod = 'CashApp';
                                                break;

                                            case 'affirm':
                                                $paymentMethod = 'Affirm';
                                                break;

                                            default:
                                                $paymentMethod = 'Unknown';
                                                break;
                                        }

                                        $paymentAmount = '$' . number_format($charge->amount / 100, 2);
                                        $paymentStatus = $charge->status;
                                        $receiptLink = $charge->receipt_url;
                                    }
                                }
                            }

                            // Handle case where trip is null
                            $tripLocation = $booking->trip ? $booking->trip->tripLocation : 'Unknown Location';
                        @endphp

                        <tr>
                            <td>{{ $booking->name }}</td>
                            <td>{{ $tripLocation }}</td>
                            <td>{{ $paymentMethod }}</td>
                            <td>{{ $paymentAmount }}</td>
                            <td>{{ $paymentStatus }}</td>
                            <td>{{ $paymentMethodCard }}</td>
                            <td>{{ $cardExpirationMonth . '/' . $cardExpirationYear }}</td>
                            <td>{{ $cardLast4 }}</td>
                            <td><a href="{{ $receiptLink }}" target="_blank" rel="noopener noreferrer">View Receipt</a>
                            </td>
                            <td><a href="{{ route('admin.booking', ['bookingID' => $booking->bookingID]) }}">View
                                    Booking</a></td>
                            <td>{{ date('F jS, Y \a\t g:i A', strtotime($booking->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
