<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactNotification;
use Illuminate\Validation\NotificationException;
use App\Mail\ContactFormSubmitted;

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
        try {
            \Log::info('User input validated!');
            \Log::info('Payload:');

            \Log::info([
                'Name' => $this->name,
                'Email' => $this->email,
                'Subject' => $this->subject,
                'Message' => $this->message
            ]);

            \Log::info('Attempting to send contact data to ' . env('MAIL_TO_ADDRESS'));

            // Using the custom sendEmail method
            $this->sendEmail($this->name, $this->email, $this->subject, $this->message);

            \Log::info('Notified target contact');
            session()->flash('status', 'Your message has been sent successfully!');
            \Log::info('Success message displayed to user');
        } catch (\Exception $e) {
            \Log::error('Notification Exception Caught. Unable to send email because: ' . $e->getMessage());
        }
    }

    private function sendEmail($name, $email, $subject, $message)
    {
        // Mail::raw($message, function($mail) use ($name, $email, $subject) {
        //     $mail->to(env('MAIL_TO_ADDRESS'))
        //         ->subject($subject)
        //         ->from($email, $name);
        // });

        Mail::to("support@viveaventurascaribenas.com")
        ->send(new ContactFormSubmitted($name, $email, $subject, $message));
    }
}
