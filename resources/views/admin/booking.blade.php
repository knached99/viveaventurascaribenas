@php 
$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
$stripeCheckoutID = $booking->stripe_checkout_id;
$product = $stripe->products->retrieve($booking->stripe_product_id);
$location = $product->name;

if(!empty($stripeCheckoutID)){
    $stripeCheckoutSession = $stripe->checkout->sessions->retrieve($stripeCheckoutID);

    if($stripeCheckoutSession && !empty($stripeCheckoutSession->payment_intent)){
        $paymentIntent = $stripeCheckoutSession->payment_intent;
        $charges = $stripe->charges->all(['payment_intent'=>$paymentIntent]);

        if(count($charges->data) > 0){
            $charge = $charges->data[0];

            $cardExpirationMonth = $charge->payment_method_details->card->exp_month;
            $cardExpirationYear = $charge->payment_method_details->card->exp_year;
            $cardFunding = $charge->payment_method_details->card->funding;
            $paymentAmount = '$'.number_format($charge->amount / 100, 2);
            $paymentStatus = $charge->status;
            $paymentMethod = $charge->payment_method_details->type;
            $cardLast4 = $charge->payment_method_details->card->last4;
            $paymentMethodCard = $charge->payment_method_details->card->brand;
            $receiptLink = $charge->receipt_url;
        } else {
            $cardExpirationMonth = 'N/A';
            $cardExpirationYear = 'N/A';
            $cardFunding = 'N/A';
            $paymentAmount = '0';
            $paymentStatus = 'N/A';
            $paymentMethod = 'N/A';
            $cardLast4 = 'N/A';
            $paymentMethodCard = 'N/A';
            $receiptLink = 'N/A';
        }
    }
}
@endphp 

@php 
$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
$stripeCheckoutID = $booking->stripe_checkout_id;
$product = $stripe->products->retrieve($booking->stripe_product_id);
$location = $product->name;

if(!empty($stripeCheckoutID)){
    $stripeCheckoutSession = $stripe->checkout->sessions->retrieve($stripeCheckoutID);

    if($stripeCheckoutSession && !empty($stripeCheckoutSession->payment_intent)){
        $paymentIntent = $stripeCheckoutSession->payment_intent;
        $charges = $stripe->charges->all(['payment_intent'=>$paymentIntent]);

        if(count($charges->data) > 0){
            $charge = $charges->data[0];

            $cardExpirationMonth = $charge->payment_method_details->card->exp_month;
            $cardExpirationYear = $charge->payment_method_details->card->exp_year;
            $cardFunding = $charge->payment_method_details->card->funding;
            $paymentAmount = '$'.number_format($charge->amount / 100, 2);
            $paymentStatus = $charge->status;
            $paymentMethod = $charge->payment_method_details->type;
            $cardLast4 = $charge->payment_method_details->card->last4;
            $paymentMethodCard = $charge->payment_method_details->card->brand;
            $receiptLink = $charge->receipt_url;
        } else {
            $cardExpirationMonth = 'N/A';
            $cardExpirationYear = 'N/A';
            $cardFunding = 'N/A';
            $paymentAmount = '0';
            $paymentStatus = 'N/A';
            $paymentMethod = 'N/A';
            $cardLast4 = 'N/A';
            $paymentMethodCard = 'N/A';
            $receiptLink = 'N/A';
        }
    }
}
@endphp 

<x-authenticated-theme-layout>
    <div class="relative w-full mt-6 text-gray-700 bg-white shadow-md rounded-xl">
        <div class="p-6">
            <h5 class="mb-4 text-2xl font-semibold text-blue-gray-900">
                <i class='bx bxs-user'></i> {{$booking->name}}'s Booking Information
            </h5>

            <!-- Booking Location -->
            <div class="mb-6">
                <h6 class="text-lg font-medium text-blue-gray-800">
                    <i class='bx bxs-map'></i> Booked Location
                </h6>
                <p class="text-base font-light leading-relaxed">{{$location}}</p>
                
                <h5>Customer's Address</h5>
                <!-- OpenStreetMap Embed -->
                @if($latitude && $longitude)
                    <div class="mt-4">
                        <iframe
                            width="100%"
                            height="300"
                            frameborder="0"
                            scrolling="no"
                            marginheight="0"
                            marginwidth="0"
                            src="https://www.openstreetmap.org/export/embed.html?bbox={{ $longitude - 0.005 }},{{ $latitude - 0.005 }},{{ $longitude + 0.005 }},{{ $latitude + 0.005 }}&layer=mapnik&marker={{ $latitude }},{{ $longitude }}"></iframe>
                        <small><a href="https://www.openstreetmap.org/?mlat={{ $latitude }}&mlon={{ $longitude }}#map=15/{{ $latitude }}/{{ $longitude }}" target="_blank">View Larger Map</a></small>
                    </div>
                @else
                    <p class="text-red-500">Location could not be determined for this address.</p>
                @endif
            </div>

            <!-- Payment Status -->
            <div class="mb-6">
                <h6 class="text-lg font-medium text-blue-gray-800">
                    <i class='bx bxs-check-shield'></i> Payment Status
                </h6>
                <span class="inline-block px-3 py-1 mt-2 text-xs font-semibold 
                @switch($paymentStatus)
                    @case('succeeded')
                        bg-emerald-500
                        @break
                    @case('incomplete')
                        bg-indigo-100
                        @break 
                    @case('failed')
                        bg-red-500
                        @break 
                    @case('unpactured')
                        bg-yellow-600
                        @break 
                    @case('canceled')
                        bg-orange-500
                        @break 
                @endswitch
                text-white rounded-full">
                    {{$paymentStatus}}
                </span>
            </div>

            <!-- Payment Details -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-credit-card'></i> Payment Method
                    </h6>
                    <p class="text-base font-light leading-relaxed">{{$paymentMethodCard}} (**** {{$cardLast4}})</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bx-dollar-circle'></i> Payment Amount
                    </h6>
                    <p class="text-base font-light leading-relaxed">{{$paymentAmount}}</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-calendar'></i> Card Expiration
                    </h6>
                    <p class="text-base font-light leading-relaxed">{{$cardExpirationMonth}}/{{$cardExpirationYear}}</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bx-link-external'></i> Receipt
                    </h6>
                    <a href="{{$receiptLink}}" target="_blank" class="text-blue-600 underline">View Receipt</a>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-envelope'></i> Email
                    </h6>
                    <p class="text-base font-light leading-relaxed">{{$booking->email}}</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-phone'></i> Phone
                    </h6>
                    <p class="text-base font-light leading-relaxed">{{$booking->phone_number}}</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg col-span-2">
                    <h6 class="text-lg font-medium text-blue-gray-800">
                        <i class='bx bxs-map-pin'></i> Customer's Address
                    </h6>
                    <p class="text-base font-light leading-relaxed">
                        {{$booking->address_line_1}}<br>
                        {{$booking->address_line_2}}<br>
                        {{$booking->city}}, {{$booking->state}} {{$booking->zip_code}}
                    </p>
                </div>
            </div>
        </div>

        <!-- Optional Buttons Section -->
        <div class="flex items-center justify-between p-6 border-t border-gray-200">
            <!-- Add buttons or additional actions here if needed -->
        </div>
    </div>
</x-authenticated-theme-layout>
