<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.theme')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
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
              <p class="mb-6">Enter your new password to complete the password reset</p>
       <x-auth-session-status class="mb-4" :status="session('status')" />
            <form id="formAuthentication" class="mb-6" wire:submit.prevent="resetPassword">
    <div class="mb-6">
        <label for="email" class="form-label">Email</label>
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
    <div class="mb-6 form-password-toggle">
        <label class="form-label" for="password">Password</label>
        <div class="input-group input-group-merge">
            <input
                type="password"
                id="password"
                class="form-control {{$errors->has('password') ? 'border border-danger' : ''}}"
                name="password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="password"
                wire:model="password" />
            <span class="input-group-text cursor-pointer {{$errors->has('password') ? 'border border-danger' : ''}}"><i class="bx bx-hide"></i></span>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-2"/>

    </div>

        <div class="mb-6 form-password-toggle">
        <label class="form-label" for="password">Confirm Password</label>
        <div class="input-group input-group-merge">
            <input
                type="password"
                id="password_confirmation"
                class="form-control {{$errors->has('password_confirmation') ? 'border border-danger' : ''}}"
                name="password_confirmation"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="password"
                wire:model="password_confirmation" />
            <span class="input-group-text cursor-pointer {{$errors->has('password_confirmation') ? 'border border-danger' : ''}}"><i class="bx bx-hide"></i></span>
        </div>
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>

    </div>
    <!-- Other form fields -->
    <div class="mb-6">
        <button class="btn btn-primary d-grid w-100" type="submit">Reset Password</button>
    </div>
</form>

            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>