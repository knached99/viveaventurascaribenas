<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestimonialSubmitted extends Notification
{
    use Queueable;

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
     
                    ->subject($this->data['name']. ' Submitted a testimonial!')
                    ->greeting('Hey Pablo, a customer has submitted a testimonial regarding their experience with you!')
                    ->line('If you\'d like to respond to the user, you can ')
                    ->line("[click here to email them](mailto:{$this->data['email']})")
                    ->action('View Testimonial', url('/admin/testimonial/'.$this->data['testimonialID'].''))
                    ->line('You can then approve, deny, or delete the testimonial')
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
