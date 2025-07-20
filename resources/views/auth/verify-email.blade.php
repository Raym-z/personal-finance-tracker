<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="fw-bold mb-2" style="color: #2563eb;">Verify Your Email</h2>
        <p class="text-muted mb-0">One more step to complete your registration</p>
    </div>

    <div class="alert alert-info mb-4" role="alert">
        <i class="bi bi-envelope me-2"></i>
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-grid gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-lg fw-semibold w-100">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>