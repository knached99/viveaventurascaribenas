<?php

namespace App\Notifications;

use App\Models\Reservations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripAvailabilityNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

     protected Reservations $reservation;
     protected string $isAvailable;


    public function __construct(Reservations $reservation, string $isAvailable)
    {   
        $this->reservation = $reservation;
        $this->isAvailable = $isAvailable;
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
    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->isAvailable;

        return (new MailMessage)
                    ->subject('Trip Availability Updated!')
                    ->greeting('Hello {user}!')
                    ->line('Your reserved trip is now '.$status)
                    ->action('View Trip', url('/booking/{slug}'))
                    ->line('Thank you for using our service!');
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
