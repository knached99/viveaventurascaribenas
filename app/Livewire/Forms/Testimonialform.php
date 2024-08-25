<?php 

namespace App\Livewire\Forms;

use Carbon\Carbon;
use App\Models\Testimonials;
use App\Models\TripsModel;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use App\Mail\TestimonialSubmitted;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Exception;

class TestimonialForm extends Component 
{
    use UsesSpamProtection;

    public array $trips = [];
    public string $testimonialID;
    
    public function __construct()
    {
        $this->testimonialID = (string) Str::uuid(); // Generate UUID for the testimonialID
    }

    #[Validate('required|string')]
    public string $name = '';

    #[Validate('nullable|string|email')]
    public string $email = '';

    #[Validate('required|uuid')]
    public string $tripID = ''; // Update to tripID

    #[Validate('required|string')]
    public string $trip_date = '';

    #[Validate('required|integer')]
    public string $trip_rating = '';

    #[Validate('required|string|max:1000')]
    public string $testimonial = '';

    #[Validate('required|boolean')]
    public bool $consent = false;

    public string $status = '';
    public string $error = '';

    public HoneypotData $extraFields;
    
    public function mount()
    {
        $this->extraFields = new HoneypotData();
        $this->trips = TripsModel::select('tripID', 'tripLocation')
            ->where('tripStartDate', '<', Carbon::now())
            ->where('tripEndDate', '<', Carbon::now())
            ->get()
            ->toArray();
    }

    public function submitTestimonialForm(): void 
    {
        $this->validate();
        $this->protectAgainstSpam();

        try {
            $data = [
                'testimonialID' => $this->testimonialID,
                'name' => $this->name,
                'email' => $this->email,
                'tripID' => $this->tripID, // Store the selected tripID
                'trip_date' => $this->trip_date,
                'trip_rating' => $this->trip_rating,
                'testimonial' => $this->testimonial,
                'consent' => $this->consent,
                'testimonial_approval_status' => 'Pending'
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
            \Log::error('Uncaught Exception: ' . $e->getMessage());
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
        $this->tripID = ''; // Reset tripID
        $this->trip_date = '';
        $this->trip_rating = 0;
        $this->testimonial = '';
        $this->consent = false;
    }

    public function render()
    {
        return view('livewire.forms.testimonial-form', [
            'trips' => $this->trips
        ]);
    }
}
