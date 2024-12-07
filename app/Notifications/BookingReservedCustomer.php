<?php 
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingReservedCustomer extends Notification
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
                    ->subject('Reservation Confirmation - '.$this->data['tripLocation'])
                    ->greeting('Dear '.$this->data['name'].',')
                    ->line('We are pleased to confirm your reservation for the upcoming trip to '.$this->data['tripLocation'].'.')
                    ->line('Reservation Details:')
                    ->line('Reservation ID: '.$this->data['reservationID'])
                    ->line('Trip: '.$this->data['tripLocation'])
                    ->line('Your preferred travel dates: '.$this->data['preferredStartDate'].' -  '.$this->data['preferredEndDate'])
                    ->line('Status: Reservation Confirmed')
                    ->line('At this time, the trip is marked as "Coming Soon". Once the trip becomes available, we will notify you so you can proceed with the payment to secure and confirm your booking.')
                    ->line('Please keep an eye on your email for further updates.')
                    ->line('We look forward to helping you embark on an exciting journey to '.$this->data['tripLocation'].'!')
                    ->line('Thank you for choosing us for your travel plans.')
                    ->salutation('Best regards,')
                    ->salutation(config('app.name'))
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
