<?php 
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingReservedAdmin extends Notification
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
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
                    ->subject('New Reservation Notification - '.$this->data['reservationID'])
                    ->greeting('Hello Pablo,')
                    ->line('A new reservation has been made by '.$this->data['name'].'.')
                    ->line('Reservation Details:')
                    ->line('Reservation ID: '.$this->data['reservationID'])
                    ->line('Trip: '.$this->data['tripLocation'])
                    ->line('Status: Reservation Confirmed - "Coming Soon"')
                    ->line('Please review the reservation in the admin panel.')
                    ->action('View Reservation', url('/admin/reservation/'.$this->data['reservationID']))
                    ->line('Thank you for managing the reservations efficiently!')
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
