<x-travelcomponents.header />
<x-travelcomponents.navbar />
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="message-box _success">
                <i class="fa fa-check-circle" aria-hidden="true"></i>
                <h3>Thank you, {{ $customerName }}! </h3>
                <p>Your payment was successful. We've sent the confirmation details to
                    {{ $customerEmail ?? 'your email' }} <br>
                </p>
                <p class="block">
                If you do not see the email in your inbox, please check your spam folder
                </p>
                <a href="/" class="text-indigo-500 font-bold block">Go Home</a>
            </div>
        </div>
    </div>
    <hr>



</div>
<x-travelcomponents.footer />
