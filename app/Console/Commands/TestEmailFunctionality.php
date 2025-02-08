<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailFunctionality extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tests email sending functionality to the specified email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->ask('Enter the email you\'d like to send this test to');
        $emailBody = $this->ask('Enter the test message: ');

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->error('Invalid email provided');
            return;
        }

        try {
            Mail::raw($emailBody, function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email');
            });       
            $this->info("Test email sent successfully to {$email}");
        }

        catch(\Exception $e){
            $this->error('Failed to send email: '.$e->getMessage());
        }
    
    }

}