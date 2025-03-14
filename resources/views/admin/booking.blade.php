@php
    use Carbon\Carbon;
/*    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    $stripeCheckoutID = $booking->stripe_checkout_id;
    $product = $stripe->products->retrieve($booking->stripe_product_id);
    $location = $product->name;
*/
    $location = $booking->trip->tripLocation;
    $fullPrice = $booking->trip->tripPrice;

    $photos = is_string($booking->trip->tripPhoto) ? json_decode($booking->trip->tripPhoto, true) : $booking->trip->tripPhoto;
    $firstPhoto = !empty($photos) ? asset($photos[0]) : asset('assets/images/booking_page_bg.webp');

    if (!empty($stripeCheckoutID)) {
        $stripeCheckoutSession = $stripe->checkout->sessions->retrieve($stripeCheckoutID);

        if ($stripeCheckoutSession && !empty($stripeCheckoutSession->payment_intent)) {
            $paymentIntent = $stripeCheckoutSession->payment_intent;
            $charges = $stripe->charges->all(['payment_intent' => $paymentIntent]);

            if (count($charges->data) > 0) {
                $charge = $charges->data[0];
                $paymentMethod = $charge->payment_method_details->type;

                $chargedAmount = $charge->amount / 100;

                // Initialize variables to avoid undefined errors
                $cardExpirationMonth = 'N/A';
                $cardExpirationYear = 'N/A';
                $cardFunding = 'N/A';
                $cardLast4 = 'N/A';
                $paymentMethodCard = 'N/A';
                $paymentMethodCashapp = 'N/A';
                $paymentMethodAffirm = 'N/A';

                // Process payment method details based on the payment method used
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

                $paymentAmount = '$' . number_format($chargedAmount, 2);
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
@endphp

<x-authenticated-theme-layout>
    <div class="relative w-full mt-6 text-gray-700 bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <h5 class="mb-4 text-3xl font-semibold text-gray-900">
                <i class='bx bxs-user'></i> {{ $booking->name }}'s Booking Information
            </h5>

            <!-- Booking Location -->
            <div class="mb-6">
                <h6 class="text-lg font-medium text-blue-gray-800">
                    <i class='bx bxs-map'></i> Booked Location
                </h6>
                <a class="btn btn-primary mb-5" href="{{ route('admin.trip', ['tripID' => $booking->trip->tripID]) }}">
                    {{ $location }}
                </a>

                <div class="block">
                    <img src="{{ $firstPhoto }}" alt="Location Image"
                        class="w-full max-w-sm h-auto rounded-lg shadow-md object-cover" />
                </div>
            </div>

            <!-- Booking Time -->
            <div class="mb-6">
                <h6 class="text-lg font-medium text-blue-gray-800">
                    <i class='bx bxs-calendar'></i> Booked At
                </h6>
                <p class="text-base font-light leading-relaxed">
                    {{ date('F jS, Y \a\t g:iA', strtotime($booking->created_at)) }}
                </p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Payment Status -->
                    <div class="mb-6">
                        <h6 class="text-lg font-medium text-blue-gray-800">
                            <i class='bx bxs-check-shield'></i> Payment Status
                        </h6>
                        <span
                            class="inline-block px-3 py-1 mt-2 text-xs font-semibold rounded-full
                    @switch($paymentStatus)
                        @case('succeeded') bg-emerald-500 @break
                        @case('incomplete') bg-indigo-500 @break
                        @case('failed') bg-red-500 @break
                        @case('uncaptured') bg-yellow-600 @break
                        @case('canceled') bg-orange-500 @break
                    @endswitch
                    text-white">
                            {{ $paymentStatus }}
                        </span>
                    </div>
                </div> <!-- End Col -->

            </div>
            <!-- End Row -->
            <!-- Payment Details -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Payment Method -->
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-credit-card'></i> Payment Method
                    </h6>
                    <p class="text-base font-light leading-relaxed mt-2">
                        @if ($paymentMethod === 'card')
                            {{ $paymentMethodCard }} (**** {{ $cardLast4 }})
                        @elseif ($paymentMethod === 'cashapp')
                            CashApp
                        @elseif ($paymentMethod === 'affirm')
                            Affirm
                        @else
                            Unknown Payment Method
                        @endif
                    </p>
                </div>

                <!-- Payment Amount -->
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bx-dollar-circle'></i> Payment Amount
                    </h6>
                    <p class="text-base font-light leading-relaxed mt-2">{{ $paymentAmount }}</p>
                </div>

                <!-- Card Expiration (only for card payments) -->
                @if ($paymentMethod === 'card')
                    <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                        <h6 class="text-lg font-medium text-blue-gray-800">
                            <i class='bx bxs-calendar'></i> Card Expiration
                        </h6>
                        <p class="text-base font-light leading-relaxed mt-2">
                            {{ $cardExpirationMonth }}/{{ $cardExpirationYear }}
                        </p>
                    </div>
                @endif

                <!-- Receipt Link -->
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bx-link-external'></i> Receipt
                    </h6>
                    <a href="{{ $receiptLink }}" target="_blank" class="text-blue-600 underline mt-2 block">
                        View Receipt
                    </a>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-envelope'></i> Email
                    </h6>
                    <p class="text-base font-light leading-relaxed mt-2">{{ $booking->email }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-phone'></i> Phone
                    </h6>
                    <p class="text-base font-light leading-relaxed mt-2">{{ $booking->phone_number }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm col-span-2">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-map-pin'></i> Customer's Address
                    </h6>
                    <p class="text-base font-light leading-relaxed mt-2">
                        {{ $booking->address_line_1 }}<br>
                        {{ $booking->address_line_2 }}<br>
                        {{ $booking->city }}, {{ $booking->state }} {{ $booking->zip_code }}
                    </p>
                </div>

                @if ($booking->preferred_start_date && $booking->preferred_end_date)
                    <div class="p-4 bg-gray-50 rounded-lg shadow-sm col-span-2">
                        <h6 class="text-lg font-medium text-blue-gray-800">
                            <i class='bx bx-calendar-heart'></i> Preferred Travel Dates
                        </h6>
                        <p class="text-base font-light leading-relaxed mt-2">
                            {{ date('F jS, Y', strtotime($booking->preferred_start_date)) }} -
                            {{ date('F jS, Y', strtotime($booking->preferred_end_date)) }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Optional Buttons Section -->
        <div class="flex items-center justify-between p-6 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            <!-- Add buttons or additional actions here if needed -->
        </div>
    </div>
</x-authenticated-theme-layout>
