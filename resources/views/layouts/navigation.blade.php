<nav class="navbar navbar-light bg-white border-bottom shadow-sm mb-4">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a class="navbar-brand fw-bold d-flex align-items-center mb-0" href="/dashboard" style="color: #2563eb;">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#2563eb" class="me-2"
                viewBox="0 0 16 16">
                <path d="M8 0a8 8 0 1 0 8 8A8 8 0 0 0 8 0Zm0 15A7 7 0 1 1 15 8 7 7 0 0 1 8 15Z" />
                <circle cx="8" cy="8" r="4" />
            </svg>
            Finance Tracker
        </a>
        <div class="d-flex align-items-center">
            <a class="nav-link fw-semibold text-dark mx-3" href="{{ route('transactions.index') }}">Transactions</a>
            <a class="nav-link fw-semibold text-dark mx-3" href="{{ route('budgets_goals.index') }}">Budgets & Goals</a>
            <a class="nav-link fw-semibold text-dark mx-3" href="{{ route('reports.index') }}">Reports & Insights</a>
            <a class="nav-link fw-semibold text-dark mx-3" href="{{ route('settings.index') }}">Settings</a>
            <a class="nav-link fw-semibold text-dark mx-3" href="{{ route('profile.edit') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn nav-link text-danger mx-3 p-0"
                    style="background: none; border: none;">Log Out</button>
            </form>
        </div>
    </div>
</nav>