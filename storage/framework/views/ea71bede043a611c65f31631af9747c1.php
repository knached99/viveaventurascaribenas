<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['bookings', 'productMap']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['bookings', 'productMap']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

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
                    <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
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
                        ?>

                        <tr>
                            <td><?php echo e($booking->name); ?></td>
                            <td><?php echo e($tripLocation); ?></td>
                            <td><?php echo e($paymentMethod); ?></td>
                            <td><?php echo e($paymentAmount); ?></td>
                            <td><?php echo e($paymentStatus); ?></td>
                            <td><?php echo e($paymentMethodCard); ?></td>
                            <td><?php echo e($cardExpirationMonth . '/' . $cardExpirationYear); ?></td>
                            <td><?php echo e($cardLast4); ?></td>
                            <td><a href="<?php echo e($receiptLink); ?>" target="_blank" rel="noopener noreferrer">View Receipt</a>
                            </td>
                            <td><a href="<?php echo e(route('admin.booking', ['bookingID' => $booking->bookingID])); ?>">View
                                    Booking</a></td>
                            <td><?php echo e(date('F jS, Y \a\t g:iA', strtotime($booking->created_at))); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH /Applications/MAMP/htdocs/viveaventurascaribenas/resources/views/components/admincomponents/transactions.blade.php ENDPATH**/ ?>