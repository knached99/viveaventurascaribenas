<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.theme')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>


 <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card px-sm-6 px-0">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="{{route('/')}}" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                   <img src="{{asset('assets/images/faviconIcon.png')}}" />
    
                  </span>
                  <span class="app-brand-text demo text-heading fw-bold">{{config('app.name')}}</span>
                </a>
              </div>
              <!-- /Logo -->
              <p class="mb-6">Forgot your password? No problem, just enter your email and look for the password reset link we send</p>
       <x-auth-session-status class="mb-4" :status="session('status')" />
        <form wire:submit="sendPasswordResetLink">    
        <div class="mb-6">
        <label for="email" class="form-label">Email or Username</label>
        <input
            type="text"
            class="form-control {{$errors->has('email') ? 'border border-danger' : ''}}"
            id="email"
            name="email-username"
            placeholder="Enter your email"
            autofocus
            wire:model="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
    </div>
  
    <!-- Other form fields -->
    <div class="mb-6">
        <button class="btn btn-primary d-grid w-100" type="submit">Reset Password</button>
    </div>
</form>
 <p class="text-center">
                <a href="{{route('login')}}">
                  <span>Login to your account</span>
                </a>
              </p>


            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

