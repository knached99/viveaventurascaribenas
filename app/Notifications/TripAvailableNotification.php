<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class TripAvailableNotification extends Notification
{
    use Queueable;

    private $trip;
    private $reservationID;
    private $customerName;

    public function __construct($trip, $reservationID, $customerName)
    {
        $this->trip = $trip;
        $this->reservationID = $reservationID;
        $this->customerName = $customerName;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    private function timeOfDayGreeting()
    {
        $hour = Carbon::now()->hour;

        if ($hour >= 5 && $hour < 12) {
            return 'Good morning!';
        } elseif ($hour >= 12 && $hour < 18) {
            return 'Good afternoon!';
        } else {
            return 'Good evening!';
        }
    }

    public function toMail(object $notifiable): MailMessage
    {

        // Signed URL valid for 1 day to prevent tampering 
        $signedURL = URL::temporarySignedRoute('booking', now()->addDay(), [
            'slug'=>$this->trip->slug,
            'reservationID'=>$this->reservationID
        ]);

        return (new MailMessage)
            ->greeting($this->timeOfDayGreeting() . ' ' . $this->customerName)
            ->line('The trip you reserved is now available to book!')
            ->action('Click here to continue booking this trip', $signedURL)
            ->line('Thank you for using '.config('app.name'))
            ->cc(env('MAIL_CC_ADDRESS'));
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
