<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="fw-bold mb-2" style="color: #2563eb;">Set New Password</h2>
        <p class="text-muted mb-0">Enter your new password below</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">{{ __('Email') }}</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">{{ __('New Password') }}</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                name="password" required autocomplete="new-password">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">{{ __('Confirm New Password') }}</label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>

    <div class="text-center">
        <p class="text-muted mb-0">
            Remember your password?
            <a href="{{ route('login') }}" class="text-decoration-none" style="color: #2563eb;">
                {{ __('Sign in') }}
            </a>
        </p>
    </div>
</x-guest-layout>