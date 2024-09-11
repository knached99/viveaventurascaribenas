<?php
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    $stripeCheckoutID = $booking->stripe_checkout_id;
    $product = $stripe->products->retrieve($booking->stripe_product_id);
    $location = $product->name;

    if (!empty($stripeCheckoutID)) {
        $stripeCheckoutSession = $stripe->checkout->sessions->retrieve($stripeCheckoutID);

        if ($stripeCheckoutSession && !empty($stripeCheckoutSession->payment_intent)) {
            $paymentIntent = $stripeCheckoutSession->payment_intent;
            $charges = $stripe->charges->all(['payment_intent' => $paymentIntent]);

            if (count($charges->data) > 0) {
                $charge = $charges->data[0];
                $paymentMethod = $charge->payment_method_details->type;

                // Initialize variables to avoid undefined errors
                $cardExpirationMonth = 'N/A';
                $cardExpirationYear = 'N/A';
                $cardFunding = 'N/A';
                $cardLast4 = 'N/A';
                $paymentMethodCard = 'N/A';
                $paymentMethodCashapp = 'N/A';
                $paymentMethodAffirm = 'N/A';

                switch ($paymentMethod) {
                    case 'card':
                        $cardExpirationMonth = $charge->payment_method_details->card->exp_month;
                        $cardExpirationYear = $charge->payment_method_details->card->exp_year;
                        $cardFunding = $charge->payment_method_details->card->funding;
                        $cardLast4 = $charge->payment_method_details->card->last4;
                        $paymentMethodCard = $charge->payment_method_details->card->brand;
                        break;

                    case 'cashapp':
                        $paymentMethodCashapp = 'CashApp';
                        break;

                    case 'affirm':
                        $paymentMethodAffirm = 'Affirm';
                        break;

                    default:
                        $paymentMethod = 'Unknown';
                        break;
                }

                $paymentAmount = '$' . number_format($charge->amount / 100, 2);
                $paymentStatus = $charge->status;
                $receiptLink = $charge->receipt_url;
            } else {
                // Set default values if no charge is found
                $paymentAmount = '0';
                $paymentStatus = 'N/A';
                $receiptLink = 'N/A';
            }
        }
    }
?>


<?php if (isset($component)) { $__componentOriginaldb6c893c91deabe715f4e95492242b1a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldb6c893c91deabe715f4e95492242b1a = $attributes; } ?>
<?php $component = App\View\Components\AuthenticatedThemeLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('authenticated-theme-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AuthenticatedThemeLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="relative w-full mt-6 text-gray-700 bg-white shadow-md rounded-xl">
        <div class="p-6">
            <h5 class="mb-4 text-2xl font-semibold text-blue-gray-900">
                <i class='bx bxs-user'></i> <?php echo e($booking->name); ?>'s Booking Information
            </h5>

            <!-- Booking Location -->
            <div class="mb-6">
                <h6 class="text-lg font-medium text-blue-gray-800">
                    <i class='bx bxs-map'></i> Booked Location
                </h6>
                <p class="text-base font-light leading-relaxed"><?php echo e($location); ?></p>


            </div>

            <div class="mb-6">
                <h6 class="text-lg font-medium text-blue-gray-800">
                    <i class='bx bxs-calendar'></i> Booked At
                </h6>
                <p class="text-base font-light leading-relaxed">
                    <?php echo e(date('F jS, Y \a\t g:iA', strtotime($booking->created_at))); ?></p>
            </div>

            <!-- Payment Status -->
            <div class="mb-6">
                <h6 class="text-lg font-medium text-blue-gray-800">
                    <i class='bx bxs-check-shield'></i> Payment Status
                </h6>
                <span
                    class="inline-block px-3 py-1 mt-2 text-xs font-semibold 
                <?php switch($paymentStatus):
                    case ('succeeded'): ?>
                        bg-emerald-500
                        <?php break; ?>
                    <?php case ('incomplete'): ?>
                        bg-indigo-100
                        <?php break; ?> 
                    <?php case ('failed'): ?>
                        bg-red-500
                        <?php break; ?> 
                    <?php case ('unpactured'): ?>
                        bg-yellow-600
                        <?php break; ?> 
                    <?php case ('canceled'): ?>
                        bg-orange-500
                        <?php break; ?> 
                <?php endswitch; ?>
                text-white rounded-full">
                    <?php echo e($paymentStatus); ?>

                </span>
            </div>

            <!-- Payment Details -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Payment Method -->
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-credit-card'></i> Payment Method
                    </h6>
                    <p class="text-base font-light leading-relaxed">
                        <?php if($paymentMethod === 'card'): ?>
                            <?php echo e($paymentMethodCard); ?> (**** <?php echo e($cardLast4); ?>)
                        <?php elseif($paymentMethod === 'cashapp'): ?>
                            CashApp
                        <?php elseif($paymentMethod === 'affirm'): ?>
                            Affirm
                        <?php else: ?>
                            Unknown Payment Method
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Payment Amount -->
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bx-dollar-circle'></i> Payment Amount
                    </h6>
                    <p class="text-base font-light leading-relaxed"><?php echo e($paymentAmount); ?></p>
                </div>

                <!-- Card Expiration (only for card payments) -->
                <?php if($paymentMethod === 'card'): ?>
                    <div class="p-4 bg-gray-100 rounded-lg">
                        <h6 class="text-lg font-medium text-blue-gray-800">
                            <i class='bx bxs-calendar'></i> Card Expiration
                        </h6>
                        <p class="text-base font-light leading-relaxed">
                            <?php echo e($cardExpirationMonth); ?>/<?php echo e($cardExpirationYear); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Receipt Link -->
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bx-link-external'></i> Receipt
                    </h6>
                    <a href="<?php echo e($receiptLink); ?>" target="_blank" class="text-blue-600 underline">View Receipt</a>
                </div>
            </div>


            <!-- Contact Information -->
            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-envelope'></i> Email
                    </h6>
                    <p class="text-base font-light leading-relaxed"><?php echo e($booking->email); ?></p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-phone'></i> Phone
                    </h6>
                    <p class="text-base font-light leading-relaxed"><?php echo e($booking->phone_number); ?></p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg col-span-2">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-map-pin'></i> Customer's Address
                    </h6>
                    <p class="text-base font-light leading-relaxed">
                        <?php echo e($booking->address_line_1); ?><br>
                        <?php echo e($booking->address_line_2); ?><br>
                        <?php echo e($booking->city); ?>, <?php echo e($booking->state); ?> <?php echo e($booking->zip_code); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Optional Buttons Section -->
        <div class="flex items-center justify-between p-6 border-t border-gray-200">
            <!-- Add buttons or additional actions here if needed -->
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldb6c893c91deabe715f4e95492242b1a)): ?>
<?php $attributes = $__attributesOriginaldb6c893c91deabe715f4e95492242b1a; ?>
<?php unset($__attributesOriginaldb6c893c91deabe715f4e95492242b1a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldb6c893c91deabe715f4e95492242b1a)): ?>
<?php $component = $__componentOriginaldb6c893c91deabe715f4e95492242b1a; ?>
<?php unset($__componentOriginaldb6c893c91deabe715f4e95492242b1a); ?>
<?php endif; ?>
<?php /**PATH /Applications/MAMP/htdocs/viveaventurascaribenas/resources/views/admin/booking.blade.php ENDPATH**/ ?>