<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingSubmittedCustomer extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $name, string $bookingID, string $receiptLink)
    {
        $this->name = $name;
        $this->bookingID = $bookingID;
        $this->receiptLInk = $receiptLink;
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
        
        ->subject('Booking Confirmation: '.$this->bookingID)
        ->greeting('Hey '.$this->name. ' This is your booking confirmation email!')
        ->line('Click on the link below to view your receipt')
        ->action('Notification Action', url('/'))
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
