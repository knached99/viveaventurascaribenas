<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactNotification;
use Illuminate\Validation\NotificationException;

class ContactForm extends Form
{
    #[Validate('required|string')]
    public string $name = '';

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $subject = '';

    #[Validate('required|string')]
    public string $message = '';

    /**
     * Handle the form submission.
     */
    public function submitContactForm(): void
    {
        $this->validate();
        \Log::info('Validating User Input...');

         // Send notification using the Notification facade
    try{
        \Log::info('User input validated!');
        \Log::info('Payload Sent:');

        \Log::info(['Name'=> $this->name, 'Email'=>$this->email,  'Subject'=>$this->subject, 'Message'=>$this->message]);

        \Log::info('Attempting to send contact data to '.env('MAIL_TO_ADDRESS'));

        Notification::route('mail', 'support@viveaventurascaribenas.com')
        ->notify(new ContactNotification($this->name, $this->email, $this->subject, $this->message));
        \Log::info('Notified contact target');
            // You could also use session to flash success messages if needed
            session()->flash('status', 'Your message has been sent successfully!');
            \Log::info('Success message displayed to user');
        }

        catch(NotificationException $e){
            \Log::error('Notification Exception Caught. Unable to send email because: '.$e->getMessage());
        }
    }

  
   
}
