<x-travelcomponents.header />
<x-travelcomponents.navbar />
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="message-box _success">
                <i class="fa fa-check-circle" aria-hidden="true"></i>
                <h3>Thank you, {{ $customerName }}! </h3>
                <p>Your reservation is confirmed. We've sent the reservation details to
                    {{ $customerEmail ?? 'your email' }} <br>
                </p>
                <p class="block">
                If you do not see the email in your inbox, please check your spam folder
                </p>
                <a href="/" style="color: #0ea5e9; text-decoration: underline;">Go Home</a>

            </div>
        </div>
    </div>
    <hr>



</div>
<x-travelcomponents.footer />
