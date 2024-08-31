<x-travelcomponents.header />
<x-travelcomponents.navbar />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="message-box _failed">
                <h3>Hey There {{ $name }}! </h3>
                <p>
                    @if (\Request::query('name'))
                        It looks like you’ve decided not to proceed with your booking at this time. No worries—plans
                        change
                        and we completely understand.

                        If you have any questions or need further assistance, please don't hesitate to contact us. We’re
                        here to help with any future travel plans or queries you might have.

                        Thank you for visiting us, and we hope to see you again soon!

                        Best regards,
                        <b>{{ config('app.name') }}</b>
                    @else
                        You have already booked the trip! Please check your email for more details.
                    @endif

                </p>
            </div>
        </div>
    </div>
    <hr>
</div>
<x-travelcomponents.footer />
