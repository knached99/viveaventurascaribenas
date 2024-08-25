<?php

namespace App\Livewire\Forms;

use Carbon\Carbon;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactNotification;
use App\Mail\ContactFormSubmitted;
use Exception;

class ContactForm extends Component
{

    use UsesSpamProtection;

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

    public HoneypotData $extraFields;

    public function mount(){
        $this->extraFields = new HoneypotData();
    }

    

    public function submitContactForm(): void 
    {
        $this->validate();

        $this->protectAgainstSpam();

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

            $this->status = 'Your message has been sent successfully! We will respond to you within 24-48 hours.';
            $this->resetForm();

        } catch (Exception $e) {
            $this->error = 'Unable to send submit contact form, something went wrong. If this issue persists, please email us directly at '. config('mail.mailers.smtp.to_email');
            $this->resetForm();
            \Log::error('Notification Exception Caught: ' . $e->getMessage());
            \Log::info(['Contact Submission Details: ', $data]);
            \Log::info('Submitted on '. date('F jS, Y \a\t g:i A ', strtotime(Carbon::now())));
        }
    }

    public function sendNotification(array $data, string $recipientEmail, string $notificationClass): void 
    {
        Mail::to($recipientEmail)->send(new $notificationClass($data));
    }

    public function resetForm(): void 
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
