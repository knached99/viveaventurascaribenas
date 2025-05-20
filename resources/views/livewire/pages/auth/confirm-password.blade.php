<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
    }
}; ?>

{{-- <div>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form wire:submit="confirmPassword">
        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password"
                          id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</div> --}}


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
                    <p class="mb-6">This is a secure area of the application. Please confirm your password before continuing.</p>
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form id="formAuthentication" class="mb-6" wire:submit.prevent="confirmPassword">
                      
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password"
                                    class="form-control {{ $errors->has('password') ? 'border border-danger' : '' }}"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" wire:model="password" />
                                <span
                                    class="input-group-text cursor-pointer {{ $errors->has('password') ? 'border border-danger' : '' }}"><i
                                        class="bx bx-hide"></i></span>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        </div>
                        <!-- Other form fields -->
                        <div class="mb-6">
                            <button class="btn btn-primary d-grid w-100" type="submit">Confirm</button>
                        </div>
                    </form>

                 


                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>