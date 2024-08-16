<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactNotification;
use App\Mail\ContactFormSubmitted;
use Exception;

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

    public string $status = '';
    public string $error = '';

    /**
     * Handle the form submission.
     */
    public function submitContactForm(): void
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ];

            $recipientEmail = config('mail.mailers.smtp.to_email') ?? 'support@viveaventurascaribenas.com';
            
            $notificationClass = ContactFormSubmitted::class;

            $this->sendNotification($data, $recipientEmail, $notificationClass);

            $this->status = 'Your message has been sent successfully!';
            $this->resetForm(); // Optional: Reset the form fields after successful submission
        } catch (Exception $e) {
            $this->error = 'Unable to send email, something went wrong. If this issue persists, please email us directly at '. config('mail.mailers.smtp.to_email');
            \Log::error('Notification Exception Caught: ' . $e->getMessage());
        }
    }

    private function sendNotification(array $data, string $recipientEmail, string $notificationClass): void
    {
        Mail::to($recipientEmail)
            ->send(new $notificationClass($data));
    }

    /**
     * Optional: Reset form fields after successful submission.
     */
    private function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->subject = '';
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.forms.contact-form');
    }
}
