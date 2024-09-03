<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Two-Step Verification') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Two-Step Verification adds an extra layer of security to your account.') }}
        </p>

        <p class="mt-2 text-md text-indigo-600 font-semibold">
            {{ __('In development. Feature not yet fully functional') }}
    </header>

    <!-- Enable 2FA -->
    <form method="POST" action="/user/two-factor-authentication">
        @csrf

        @if (auth()->user()->two_factor_enabled)
            <!-- Disable 2FA -->
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                {{ __('Disable Two-Factor Authentication') }}
            </button>
        @else
            <button type="submit" class="btn btn-primary">
                {{ __('Enable Two-Factor Authentication') }}
            </button>
        @endif
    </form>

    @if (session('status') == 'two-factor-authentication-enabled')
        <div class="mt-4 font-medium text-sm text-green-600">
            {{ __('Two-Factor Authentication is now enabled. Scan the QR code below using your authenticator app.') }}
        </div>

        <div class="mt-4">
            {!! auth()->user()->twoFactorQrCodeSvg() !!}
        </div>

        <div class="mt-4">
            <p class="font-semibold">
                {{ __('Store these recovery codes in a secure place.') }}
            </p>
            <ul class="mt-2 list-disc list-inside text-sm text-gray-600">
                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
        </div>

        <div class="mt-4">
            <form method="POST" action="{{ route('two-factor.confirm') }}">
                @csrf
                <div>
                    <x-input-label for="code" :value="__('Code')" />
                    <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" autofocus
                        autocomplete="one-time-code" />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>
                <div class="flex items-center gap-4 mt-4">
                    <x-primary-button>{{ __('Confirm') }}</x-primary-button>
                </div>
            </form>
        </div>
    @endif

    @if (session('status') == 'two-factor-authentication-disabled')
        <div class="mt-4 font-medium text-sm text-red-600">
            {{ __('Two-Factor Authentication has been disabled.') }}
        </div>
    @endif

    @if (session('status') == 'two-factor-authentication-confirmed')
        <div class="mt-4 font-medium text-sm text-green-600">
            {{ __('Two-Factor Authentication is now fully configured.') }}
        </div>
    @endif

</section>
