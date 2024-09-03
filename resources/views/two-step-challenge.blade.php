<x-guest-layout>
    <div class="container mt-5">
        <h2 class="text-center">Two-Factor Authentication</h2>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Please enter the authentication code</h5>

                <form method="POST" action="{{ route('two-factor.challenge') }}">
                    @csrf

                    <!-- Two-Factor Code Input -->
                    <div class="form-group mb-3">
                        <label for="code" class="form-label">Authentication Code</label>
                        <input type="text" id="code" name="code" class="form-control" required autofocus>
                    </div>

                    <!-- Error Message -->
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
