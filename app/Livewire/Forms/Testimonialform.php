<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use App\Livewire\Forms\PostForm;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Testimonials;
use App\Models\TripsModel;
use App\Models\BookingModel;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TestimonialSubmitted;
use Exception;

class TestimonialForm extends Component
{
    public HoneypotData $extraFields;

   
    use UsesSpamProtection;

    public array $trips = [];
    public string $testimonialID;
    
    public string $name = '';

    public string $email = '';

    public string $tripID = ''; 

   
    public string $trip_date = '';

    public string $trip_rating = '';

    public string $testimonial = '';

    public bool $consent = false;

    public string $status = '';
    public string $error = '';



    protected $rules = [
        'name' => 'required|string',
        'email'=>'required|string|email|unique:testimonials',
        'tripID'=>'required|uuid',
        'trip_date'=>'required|string',
        'trip_rating'=>'required|integer',
        'testimonial'=>'required|string|max:1000',
        'consent'=>'required|accepted',
    ];

    protected $messages = [
        'name.required'=>'Your first name is required',
        'email.required'=>'Your email is required',
        'email.email'=>'You\'ve entered an invalid email',
        'email.unique'=>'You\'ve already submitted a testimonial with us before',
        'tripID.required'=>'You must select the trip you went on',
        'tripID.uuid'=> 'Option selected is not valid',
        'trip_date.required'=>'You must provide the month you went on this trip',
        'trip_rating.required'=>'You need to provide a rating for this trip',
        'testimonial.required'=>'Your testimonial is required',
        'testimonial.max'=>'Your testimonial is too long!',
        'consent.required'=>'You must consent to submitting your testimonial',
        'consent.accepted'=>'You must consent to submitting your testimonial'
    ];

    

    public function mount(?string $tripID = null)
    {
        $this->extraFields = new HoneypotData();
        $this->trips = TripsModel::select('tripID', 'tripLocation')
            ->where('tripStartDate', '<', Carbon::now())
            ->orWhere('tripEndDate', '<', Carbon::now())
            ->get()
            ->toArray();

            if ($tripID && Str::isUuid($tripID)) {
                $this->tripID = $tripID;
            }
    
    }


     // 🔹 This method is triggered when the event is emitted
     public function tripSelected($tripID)
     {
         if (Str::isUuid($tripID)) {
             $this->tripID = $tripID;
         }
     }
 

    public function submitTestimonialForm(): void {

        $this->validate();
        $this->protectAgainstSpam();
        

        try{

            $booking = BookingModel::where('email', $this->email)->first();
            $trip = TripsModel::where('tripID', $this->tripID)->where('active', true)->first();

            if(empty($booking) || empty($trip)){

                $this->error = 'You cannot submit a testimonial unless you\'ve booked a trip with us';
                return; // Kills the PHP script to prevent form submission 
            }

            $data = [
                'testimonialID' => Str::uuid(),
                'name' => $this->name,
                'email' => $this->email,
                'tripID' => $this->tripID,
                'trip_date' => $this->trip_date,
                'trip_rating' => $this->trip_rating,
                'testimonial' => $this->testimonial,
                'consent' => $this->consent,
                'testimonial_approval_status' => 'Pending',
            ];

            Testimonials::create($data);
            $recipientEmail = config('mail.mailers.smtp.to_email') ?? 'travel@viveaventurascaribenas.net';
            $notificationClass = TestimonialSubmitted::class;
            $this->sendNotification($data, $recipientEmail, $notificationClass);
            
            // 🔹 Emit tripID to maintain selection
            $this->emit('tripSelected', $this->tripID);
            
            $this->status = 'Your testimonial has been submitted! Thank you for providing valuable feedback!';
            $this->resetForm();
            
            
        }

        catch(Exception $e){
            $this->error = 'Unable to submit testimonial form. Please try again later';
            $this->resetForm();
            \Log::error('Uncaught Exception: ' . $e->getMessage());
        }
    }


    public function sendNotification(array $data, string $recipientEmail, string $notificationClass): void {

        Notification::route('mail', $recipientEmail)->notify(new $notificationClass($data));
    }


    public function resetForm(): void {

        $this->name = '';
        $this->email = '';
        $this->trip_date = '';
        $this->trip_rating = 0;
        $this->testimonial = '';
        $this->consent = false;
    }


    // public function render(){

    //     return view('livewire.forms.testimonial-form', [
    //         'trips'=>$this->trips,
    //     ]);
    // }



    public function render()
{
    return view('livewire.forms.testimonial-form', [
        'trips' => $this->trips, 
        'tripID' => $this->tripID,
    ]);
}

    


}
