<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class TripAvailableNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($trip, $reservation)
    {
        $this->trip = $trip;
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */

     

     // Greet the user based on the time of day 

     private function timeOfDayGreeting(){

        $time = Carbon::now();
           // Determine greeting based on the time of day
           if ($time >= 5 && $time < 12) {

            return 'Good morning!';
        } elseif ($time >= 12 && $time < 18) {
            return 'Good afternoon!';
        } else {
            return 'Good evening!';
        }
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting($this->timeOfDayGreeting(). ' '.$this->reservation->name)
            ->line('The trip you reserved is now available to book!')
            ->action('Click here to continue booking this trip', url('/booking/' . $this->trip->slug.'/'.$this->reservation->reservationID))
            ->line('Thank you for using our application!');
    }
    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
