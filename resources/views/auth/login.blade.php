<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="fw-bold mb-2" style="color: #2563eb;">Welcome Back</h2>
        <p class="text-muted mb-0">Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">{{ __('Email') }}</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">{{ __('Password') }}</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                name="password" required autocomplete="current-password">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label text-muted" for="remember_me">
                    {{ __('Remember me') }}
                </label>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            @if (Route::has('password.request'))
            <a class="text-decoration-none" href="{{ route('password.request') }}" style="color: #2563eb;">
                {{ __('Forgot your password?') }}
            </a>
            @endif
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>

    <div class="text-center mt-4">
        <p class="text-muted mb-0">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-decoration-none" style="color: #2563eb;">
                {{ __('Sign up') }}
            </a>
        </p>
    </div>
</x-guest-layout>