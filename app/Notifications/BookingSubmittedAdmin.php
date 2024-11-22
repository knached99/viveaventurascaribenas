<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingSubmittedAdmin extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $name, string $bookingID)
    {
        $this->name = $name;
        $this->bookingID = $bookingID;
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
        return (new MailMessage)
         
                    ->subject($this->name. ' Has booked a trip!')
                    ->greeting('Hey Pablo, customer '.$this->name. ' has booked a trip with you!')
                    ->line('You can view the booking details by clicking on the link below')
                    ->action('View booking', url('/admin/'.$this->bookingID.'/booking'))
                    ->line('Have a great day!')
                    ->cc(env('MAIL_CC_ADDRESS'));

                   
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
