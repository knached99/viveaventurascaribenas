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
// use App\Mail\ContactFormSubmitted;
use App\Helpers\Helper;
use Exception;

class ContactForm extends Component
{

    use UsesSpamProtection;

    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $message = '';
    public string $status = '';
    public string $error = '';


    protected $rules = [
        'name'=>'required|string',
        'email'=>'required|string|email',
        'subject'=>'required',
        'message'=>"required|max:500",
    ];

    protected $messages = [
        'name.required'=>'Your name is required',
        'email.required'=>'Your email is required',
        'email.email'=>'You\'ve entered an invalid email',
        'subject.required'=>'Choose a subject',
        'message.required'=>'Please provide a reason for why you\'re reaching out to us',
        'message.max'=>'Your message is too long',
    ];

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



            $recipientEmail = env('MAIL_FROM_ADDRESS');

            $notificationClass = ContactNotification::class;

            Helper::sendNotification($data, $recipientEmail, $notificationClass);

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
