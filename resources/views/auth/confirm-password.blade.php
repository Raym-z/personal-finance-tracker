<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="fw-bold mb-2" style="color: #2563eb;">Confirm Password</h2>
        <p class="text-muted mb-0">Secure area - please confirm your identity</p>
    </div>

    <div class="alert alert-warning mb-4" role="alert">
        <i class="bi bi-shield-lock me-2"></i>
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="form-label fw-semibold">{{ __('Password') }}</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" required autocomplete="current-password">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
</x-guest-layout>
