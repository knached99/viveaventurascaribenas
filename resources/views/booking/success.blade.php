<x-travelcomponents.header />
<x-travelcomponents.navbar />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="message-box _success">
                <i class="fa fa-check-circle" aria-hidden="true"></i>
                <h3>Thank you, {{ $customerName }}! </h3>
                <p>Your payment was successful. We've sent the confirmation details to
                    {{ $customerEmail ?? 'your email' }} <br>
                </p>
            </div>
        </div>
    </div>
    <hr>



</div>
<x-travelcomponents.footer />
