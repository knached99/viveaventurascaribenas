<?php

namespace App\Livewire\Forms;

use App\Models\Testimonials;
use App\Mail\TestimonialSubmitted;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Exception;

class TestimonialForm extends Component 
{

    public function __construct(){
        $this->testimonialID = Str::uuid();
    }

    #[Validate('required|string')]
    public string $name = '';

    #[Validate('nullable|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $trip_details = '';

    #[Validate('required|string')]

    public string $trip_date = '';

    #[Validate('required|integer')]
    public string $trip_rating = '';

    #[Validate('required|string|max:1000')]
    public string $testimonial = '';

    #[Validate('required|boolean')]
    public string $consent = '';

    public string $status = '';
    public string $error = '';


    public function submitTestimonialForm(): void 
    {
        $this->validate();
    
        try {
            $data = [
                'testimonialID' => $this->testimonialID,
                'name' => $this->name,
                'email' => $this->email,
                'trip_details' => $this->trip_details,
                'trip_date' => $this->trip_date,
                'trip_rating' => $this->trip_rating,
                'testimonial' => $this->testimonial,
                'consent' => $this->consent,
                'testimonial_approval_status'=>'Pending'
            ];
    
            // Create a new testimonial with the data
            Testimonials::create($data);
    
            $recipientEmail = config('mail.mailers.smtp.to_email') ?? 'support@viveaventurascaribenas.com';
    
            $notificationClass = TestimonialSubmitted::class;
    
            $this->sendNotification($data, $recipientEmail, $notificationClass);
    
            $this->status = 'Your testimonial has been submitted! Thank you for providing valuable feedback!';
            $this->resetForm();
    
        } catch (Exception $e) {
            $this->error = 'Unable to submit testimonial form. Please try again later';
            $this->resetForm();
    
            \Log::error('Uncaught Exception occurred in file: ' . __FILE__ . ' in function: ' . __FUNCTION__ . ' on line: ' . __LINE__ . ' Error Message: ' . $e->getMessage());
        }
    }
    

    public function sendNotification(array $data, string $recipientEmail, string $notificationClass): void 
    {
        Mail::to($recipientEmail)
            ->send(new $notificationClass($data));
    }

    public function resetForm(): void 
    {
        $this->name = '';
        $this->email = '';
        $this->trip_details = '';
        $this->trip_date = '';
        $this->trip_rating = 0;
        $this->testimonial = '';
        $this->consent = false;
    }

    public function render()
    {
        return view('livewire.forms.testimonial-form');
    }
}
