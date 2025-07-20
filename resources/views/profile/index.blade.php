@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="h3 mb-4 text-dark">
                {{ __('Profile') }}
            </h2>
            <!-- Profile Information Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title h5 text-dark">
                        {{ __('Profile Information') }}
                    </h3>

                    <p class="text-muted mb-3">
                        {{ __("Manage your account's profile information.") }}
                    </p>

                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">
                                {{ __('Name') }}
                            </label>
                            <p class="text-dark">
                                {{ $user->name }}
                            </p>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">
                                {{ __('Email') }}
                            </label>
                            <p class="text-dark">
                                {{ $user->email }}
                            </p>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">
                                {{ __('Member Since') }}
                            </label>
                            <p class="text-dark">
                                {{ $user->created_at->format('F j, Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            {{ __('Edit Profile') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Statistics Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title h5 text-dark">
                        {{ __('Account Statistics') }}
                    </h3>

                    <p class="text-muted mb-3">
                        {{ __('Overview of your financial tracking activity.') }}
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <div class="h2 fw-bold text-primary">
                                    {{ $user->transactions->count() }}
                                </div>
                                <div class="text-muted">
                                    {{ __('Total Transactions') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <div class="h2 fw-bold text-success">
                                    ${{ number_format($user->transactions->where('type', 'income')->sum('amount') - $user->transactions->where('type', 'expense')->sum('amount'), 2) }}
                                </div>
                                <div class="text-muted">
                                    {{ __('Net Balance') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title h5 text-dark">
                        {{ __('Quick Actions') }}
                    </h3>

                    <p class="text-muted mb-3">
                        {{ __('Common actions for your account.') }}
                    </p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('transactions.create') }}" class="btn btn-outline-primary text-start">
                            {{ __('Add New Transaction') }}
                        </a>

                        <a href="{{ route('budgets_goals.index') }}" class="btn btn-outline-primary text-start">
                            {{ __('Manage Budgets & Goals') }}
                        </a>

                        <a href="{{ route('reports.index') }}" class="btn btn-outline-primary text-start">
                            {{ __('View Reports') }}
                        </a>

                        <a href="{{ route('settings.index') }}" class="btn btn-outline-primary text-start">
                            {{ __('Account Settings') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection