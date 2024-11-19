<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingSubmittedCustomer extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $name, string $tripLocation, string $tripPhotos, string $tripStartDate, string $tripEndDate, string $bookingID, string $receiptLink)
    {
        $this->name = $name;
        $this->tripLocation = $tripLocation; 
        $this->tripPhotos = $tripPhotos;
        $this->tripStartDate = $tripStartDate;
        $this->tripEndDate = $tripEndDate;
        $this->bookingID = $bookingID;
        $this->receiptLink = $receiptLink;
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
    $email = new MailMessage();

    // Decode trip photos JSON
    $photos = json_decode($this->tripPhotos, true);
    $photoArr = explode('/', $photos[0]);

    \Log::info('Photo Array: ' . print_r($photoArr, true));

    // Extract necessary parts of the array 
    $localFilePath = isset($photoArr) 
        ? storage_path('app/public/' . $photoArr[4] . '/' . $photoArr[5]) 
        : asset('assets/images/booking_page_bg.webp');

    \Log::info('Local file path: ' . $localFilePath);

    // Check if the file exists
    if (file_exists($localFilePath)) {
        \Log::info('File exists: ' . $localFilePath);
    } else {
        \Log::error('File does not exist: ' . $localFilePath);
    }

    // Determine MIME type based on file extension
    $mimeType = 'image/jpeg'; // Default to JPEG
    $fileExtension = pathinfo($localFilePath, PATHINFO_EXTENSION);
    if (in_array(strtolower($fileExtension), ['jpg', 'jpeg'])) {
        $mimeType = 'image/jpeg';
    } elseif (strtolower($fileExtension) == 'png') {
        $mimeType = 'image/png';
    }

    // Compose the email
    $email->subject('Booking Confirmation: ' . $this->bookingID)
        ->greeting('Hey ' . $this->name . ', This is your booking confirmation email!')
        ->line('Location Booked: ' . $this->tripLocation)
        ->line('Trip Dates: ' . date('F jS, Y', strtotime($this->tripStartDate)) . ' - ' . date('F jS, Y', strtotime($this->tripEndDate)));

    // Add image attachment if file exists
    if (file_exists($localFilePath)) {
        $email->attach($localFilePath, [
            'as' => 'photo.' . $fileExtension,  // Give it the correct file extension
            'mime' => $mimeType,  // Use the appropriate MIME type
        ]);
    } else {
        $email->line('Trip photo not available.');
    }

    $email->action('View Receipt', $this->receiptLink)
        ->line('Thank you for booking your trip with us!')
        ->salutation('Best regards,')
        ->salutation(config('app.name'));

    return $email;
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
