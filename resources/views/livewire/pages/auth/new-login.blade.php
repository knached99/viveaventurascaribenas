<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.theme')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
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
                        <a href="{{ route('/') }}" class="app-brand-link gap-2 text-decoration-none">
                            <span class="app-brand-logo demo">
                                <img src="{{ asset('assets/images/faviconIcon.png') }}" />

                            </span>
                            <span class="app-brand-text demo text-heading fw-bold">{{ config('app.name') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <p class="mb-6">Sign-in to your account to perform administrative actions</p>
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form id="formAuthentication" class="mb-6" wire:submit.prevent="login">
                        <div class="mb-6">
                            <label for="email" class="form-label">Email or Username</label>
                            <input type="text"
                                class="form-control {{ $errors->has('form.email') ? 'border border-danger' : '' }}"
                                id="email" name="email-username" placeholder="Enter your email or username"
                                autofocus wire:model="form.email" />
                            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                        </div>
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password"
                                    class="form-control {{ $errors->has('form.password') ? 'border border-danger' : '' }}"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" wire:model="form.password" />
                                <span
                                    class="input-group-text cursor-pointer {{ $errors->has('form.password') ? 'border border-danger' : '' }}"><i
                                        class="bx bx-hide"></i></span>
                            </div>
                            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />

                        </div>
                        <!-- Other form fields -->
                        <div class="mb-6">
                            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                        </div>
                    </form>

                    <p class="text-center">
                        <a href="{{ route('password.request') }}">
                            <span>Forgot Your Password?</span>
                        </a>
                    </p>


                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>
