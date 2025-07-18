@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4" style="color: #2563eb;">Add Budget</h2>
                    <form method="POST" action="{{ route('budgets.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select id="category" name="category" class="form-select" required>
                                <option value="">Select a category...</option>
                                @foreach($tags as $tag)
                                <option value="{{ $tag }}" {{ old('category') == $tag ? 'selected' : '' }}>{{ $tag }}
                                </option>
                                @endforeach
                            </select>
                            @error('category')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" id="amount" name="amount" class="form-control" required min="0.01"
                                step="0.01" value="{{ old('amount') }}">
                            @error('amount')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="period" class="form-label">Period</label>
                            <select id="period" name="period" class="form-select" required>
                                <option value="monthly" {{ old('period') == 'monthly' ? 'selected' : '' }}>Monthly
                                </option>
                                <option value="weekly" {{ old('period') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            </select>
                            @error('period')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date (optional)</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                value="{{ old('start_date') }}">
                            @error('start_date')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date (optional)</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                value="{{ old('end_date') }}">
                            @error('end_date')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('budgets_goals.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Budget</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection