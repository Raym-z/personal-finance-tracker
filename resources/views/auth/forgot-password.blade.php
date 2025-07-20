<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="fw-bold mb-2" style="color: #2563eb;">Reset Password</h2>
        <p class="text-muted mb-0">Enter your email to receive a reset link</p>
    </div>

    <div class="alert alert-info mb-4" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">{{ __('Email') }}</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{ old('email') }}" required autofocus>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                {{ __('Send Reset Link') }}
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