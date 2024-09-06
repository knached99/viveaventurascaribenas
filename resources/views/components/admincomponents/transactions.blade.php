@props(['bookings'])
<!-- Transactions -->
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        @php
                            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
                            $checkoutSessionID = $booking->stripe_checkout_id;
                            $product = $stripe->products->retrieve($booking->stripe_product_id);
                            $location = $product->name;

                            $paymentMethod = 'N/A';
                            $paymentAmount = '$0.00';
                            $paymentStatus = 'N/A';
                            $cardExpirationMonth = 'N/A';
                            $cardExpirationYear = 'N/A';
                            $cardLast4 = 'N/A';
                            $paymentMethodCard = 'N/A';
                            $receiptLink = 'N/A';

                            if (!empty($checkoutSessionID)) {
                                $checkoutSession = $stripe->checkout->sessions->retrieve($checkoutSessionID);
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
                        @endphp

                        <tr>
                            <td>{{ $booking->name }}</td>
                            <td>{{ $location }}</td>
                            <td>{{ $paymentMethod }}</td>
                            <td>{{ $paymentAmount }}</td>
                            <td>{{ $paymentStatus }}</td>
                            
                            <!-- Show card details only if payment was made with card -->
                            @if ($paymentMethod === 'card')
                                <td>{{ $paymentMethodCard }}</td>
                                <td>{{ $cardExpirationMonth . '/' . $cardExpirationYear }}</td>
                                <td>{{ $cardLast4 }}</td>
                            @else
                                <td colspan="3">N/A</td>
                            @endif

                            <td><a href="{{ $receiptLink }}" target="_blank" rel="noreferrer noopener">View Receipt</a></td>
                            <td><a href="{{ route('admin.booking', ['bookingID' => $booking->bookingID]) }}">View Booking</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/ Transactions -->
