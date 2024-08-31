@php 
$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
if($booking->stripe_checkout_id){
$checkoutSession = $stripe->checkout->sessions->retrieve($booking->stripe_checkout_id);
$paymentIntentID = $checkoutSession->payment_intent;
if(!empty($paymentIntentID)){
    $charge = $stripe->charges->all(['payment_intent'=>$paymentIntentID])->data[0];
    $paymentStatus = $charge->status;
}
}
@endphp 

<x-authenticated-theme-layout>
    <div class="relative w-full mt-6 text-gray-700 bg-white shadow-md bg-clip-border rounded-xl">
        <div class="p-6">
            <h5 class="block mb-4 font-sans text-2xl font-semibold leading-snug tracking-normal text-blue-gray-900">
                {{$booking->name}}'s Booking Information
            </h5>

            <!-- Payment Status -->
            <div class="mb-6">
                <h6 class="font-sans text-lg font-medium text-blue-gray-800">Payment Status</h6>
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

            <!-- Contact Information -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <h6 class="font-sans text-lg font-medium text-blue-gray-800">Email</h6>
                    <p class="font-sans text-base font-light leading-relaxed text-inherit">{{$booking->email}}</p>
                </div>
                <div>
                    <h6 class="font-sans text-lg font-medium text-blue-gray-800">Phone</h6>
                    <p class="font-sans text-base font-light leading-relaxed text-inherit">{{$booking->phone_number}}</p>
                </div>
                <div class="col-span-2">
                    <h6 class="font-sans text-lg font-medium text-blue-gray-800">Address</h6>
                    <p class="font-sans text-base font-light leading-relaxed text-inherit">
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
