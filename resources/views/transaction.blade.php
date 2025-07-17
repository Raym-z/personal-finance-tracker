@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4" style="color: #2563eb;">
                        {{ $mode === 'edit' ? 'Edit Transaction' : 'Add Transaction' }}
                    </h2>
                    <form method="POST"
                        action="{{ $mode === 'edit' ? route('transactions.update', $transaction->id) : route('transactions.store') }}">
                        @csrf
                        @if($mode === 'edit')
                        @method('PUT')
                        @endif
                        <div class="mb-3">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-input id="description" name="description" type="text" class="form-control mt-1"
                                value="{{ old('description', $transaction->description ?? '') }}" required autofocus />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" name="amount" type="number" step="0.01" class="form-control mt-1"
                                value="{{ old('amount', $transaction->amount ?? '') }}" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <x-input-label for="type" :value="__('Type')" />
                            @php
                            $selectedType = old('type', $type ?? ($transaction->type ?? ''));
                            @endphp
                            <select id="type" name="type" class="form-select mt-1" required>
                                <option value="income" {{ $selectedType == 'income' ? 'selected' : '' }}>Income</option>
                                <option value="expense" {{ $selectedType == 'expense' ? 'selected' : '' }}>Expense
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                {{ $mode === 'edit' ? 'Update' : 'Add' }} Transaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection