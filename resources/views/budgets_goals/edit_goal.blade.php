@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4" style="color: #2563eb;">Edit Goal</h2>
                    <form method="POST" action="{{ route('goals.update', $goal->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Goal Name</label>
                            <input type="text" id="name" name="name" class="form-control" required maxlength="100"
                                value="{{ old('name', $goal->name) }}">
                            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="target_amount" class="form-label">Target Amount</label>
                            <input type="number" id="target_amount" name="target_amount" class="form-control" required
                                min="0.01" step="0.01" value="{{ old('target_amount', $goal->target_amount) }}">
                            @error('target_amount')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="current_amount" class="form-label">Current Amount (optional)</label>
                            <input type="number" id="current_amount" name="current_amount" class="form-control" min="0"
                                step="0.01" value="{{ old('current_amount', $goal->current_amount) }}">
                            @error('current_amount')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="target_date" class="form-label">Target Date (optional)</label>
                            <input type="date" id="target_date" name="target_date" class="form-control"
                                value="{{ old('target_date', $goal->target_date) }}">
                            @error('target_date')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('budgets_goals.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">Update Goal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection