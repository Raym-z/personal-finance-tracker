@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: #2563eb;">Budgets & Goals</h2>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-semibold mb-0">Budgets</h4>
                        <a href="{{ route('budgets.create') }}" class="btn btn-primary btn-sm">Add Budget</a>
                    </div>
                    @if($budgets->isEmpty())
                    <div class="text-center text-muted py-4">
                        <x-icons.plus-circle width="32" height="32" class="mb-2" />
                        <div>No budgets set yet.<br><small>Start by adding your first budget!</small></div>
                    </div>
                    @endif
                    @foreach($budgets as $budget)
                    @php
                    $spent = isset($budget->spent) ? $budget->spent : 0;
                    $percent = isset($budget->amount) && $budget->amount > 0 ? min(100, ($spent / $budget->amount) *
                    100) : 0;
                    $percent = is_numeric($percent) ? $percent : 0;
                    $periodLabel = $budget->period === 'monthly' ? 'this month' : 'this week';
                    $isOver = $spent > $budget->amount;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-semibold">{{ $budget->category }}</span>
                                <span
                                    class="badge bg-secondary bg-opacity-25 text-secondary period-pill">{{ $periodLabel }}</span>
                                @if($isOver)
                                <span class="badge bg-danger bg-opacity-75 text-white ms-2">Over!</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted">${{ number_format($spent, 2) }} /
                                    ${{ number_format($budget->amount, 2) }}</span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted p-0 border-0 shadow-none"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <x-icons.three-dots width="18" height="18" />
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="{{ route('budgets.edit', $budget->id) }}">
                                                <x-icons.edit width="16" height="16" class="me-2 flex-shrink-0" /> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('budgets.destroy', $budget->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="dropdown-item d-flex align-items-center delete-btn"
                                                    onclick="return confirm('Delete this budget?')">
                                                    <x-icons.delete width="16" height="16" class="me-2 flex-shrink-0" />
                                                    Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar {{ $isOver ? 'bg-danger' : 'bg-primary' }}" role="progressbar"
                                style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-semibold mb-0">Goals</h4>
                        <a href="{{ route('goals.create') }}" class="btn btn-success btn-sm">Add Goal</a>
                    </div>
                    @if($goals->isEmpty())
                    <div class="text-center text-muted py-4">
                        <x-icons.plus-circle width="32" height="32" class="mb-2" />
                        <div>No goals set yet.<br><small>Start by adding your first goal!</small></div>
                    </div>
                    @endif
                    @foreach($goals as $goal)
                    @php
                    $percent = isset($goal->target_amount) && $goal->target_amount > 0 ? min(100, ($goal->current_amount
                    / $goal->target_amount) * 100) : 0;
                    $percent = is_numeric($percent) ? $percent : 0;
                    $isComplete = $goal->current_amount >= $goal->target_amount && $goal->target_amount > 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">{{ $goal->name }}
                                @if($isComplete)
                                <x-icons.check width="18" height="18" class="text-success ms-1 align-middle" />
                                @endif
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted">${{ number_format($goal->current_amount, 2) }} /
                                    ${{ number_format($goal->target_amount, 2) }}</span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted p-0 border-0 shadow-none"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <x-icons.three-dots width="18" height="18" />
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="{{ route('goals.edit', $goal->id) }}">
                                                <x-icons.edit width="16" height="16" class="me-2 flex-shrink-0" /> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('goals.destroy', $goal->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="dropdown-item d-flex align-items-center delete-btn"
                                                    onclick="return confirm('Delete this goal?')">
                                                    <x-icons.delete width="16" height="16" class="me-2 flex-shrink-0" />
                                                    Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar {{ $isComplete ? 'bg-success' : 'bg-success bg-opacity-75' }}"
                                role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}"
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.period-pill {
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: 12px;
    padding: 2px 10px;
    opacity: 0.7;
}
</style>