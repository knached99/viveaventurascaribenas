<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactNotification extends Notification
{
    use Queueable;


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
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->data['subject'])
            ->greeting('Hey Pablo, '.$this->data['name']. ' has contacted you')
            ->from(env('MAIL_FROM_ADDRESS'), $this->data['name'])
            ->line($this->data['message'])
            ->line('This contact is expecting a response from you within 24-48 hours.')
            ->line('You can reply to them by')
            ->line("[Clicking Here](mailto:{$this->data['email']})")
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
