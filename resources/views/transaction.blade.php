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
                        action="{{ $mode === 'edit' ? route('transactions.update', $transaction->id) : route('transactions.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @if($mode === 'edit')
                        @method('PUT')
                        @endif

                        <div class="mb-3">
                            <x-input-label for="type" :value="__('Type')" />
                            @php
                            $selectedType = old('type', $type ?? ($transaction->type ?? ''));
                            @endphp
                            <select id="type" name="type" class="form-select mt-1" required
                                onchange="updateTagOptions()">
                                <option value="income" {{ $selectedType == 'income' ? 'selected' : '' }}>Income</option>
                                <option value="expense" {{ $selectedType == 'expense' ? 'selected' : '' }}>Expense
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="tag" :value="__('Tag')" />
                            <div class="d-flex gap-2 mt-1">
                                <select id="tag-select" class="form-select" onchange="handleTagChange()">
                                    <option value="">Select a tag...</option>
                                </select>
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="showCustomTag()">Custom</button>
                            </div>
                            <input type="text" id="tag" name="tag" class="form-control mt-2 d-none"
                                value="{{ old('tag', $transaction->tag ?? '') }}" placeholder="Enter custom tag"
                                maxlength="100">
                            <x-input-error :messages="$errors->get('tag')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" name="amount" type="number" step="0.01" class="form-control mt-1"
                                value="{{ old('amount', $transaction->amount ?? '') }}" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="transaction_date" :value="__('Date (Optional)')" />
                            <x-text-input id="transaction_date" name="transaction_date" type="datetime-local"
                                class="form-control mt-1"
                                value="{{ old('transaction_date', $transaction ? $transaction->created_at->format('Y-m-d\TH:i') : '') }}" />
                            <small class="text-muted">Leave empty to use current date and time</small>
                            <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="description" :value="__('Description (Optional)')" />
                            <x-text-input id="description" name="description" type="text" class="form-control mt-1"
                                value="{{ old('description', $transaction->description ?? '') }}" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="image" :value="__('Upload Bill/Receipt (Optional)')" />
                            <input type="file" id="image" name="image" class="form-control mt-1" accept="image/*">
                            <small class="text-muted">Accepted formats: JPEG, PNG, JPG, GIF (max 2MB)</small>
                            @if($mode === 'edit' && $transaction->image_path)
                            <div class="mt-2">
                                <small class="text-muted">Current image:</small>
                                <img src="{{ Storage::url($transaction->image_path) }}" alt="Transaction receipt"
                                    class="img-thumbnail d-block mt-1" style="max-width: 200px;">
                            </div>
                            @endif
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
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

<script>
const predefinedTags = JSON.parse('{!! json_encode($predefinedTags) !!}');
const tagColors = JSON.parse('{!! json_encode(\App\Models\Transaction::getTagColors()) !!}');
let currentType = '{{ $selectedType }}';

// Helper function to get Bootstrap color values
function getBootstrapColor(colorClass) {
    const colors = {
        'primary': '#0d6efd',
        'secondary': '#6c757d',
        'success': '#198754',
        'danger': '#dc3545',
        'warning': '#ffc107',
        'info': '#0dcaf0',
        'light': '#f8f9fa',
        'dark': '#212529'
    };
    return colors[colorClass] || '#6c757d';
}

function updateTagOptions() {
    const typeSelect = document.getElementById('type');
    const tagSelect = document.getElementById('tag-select');
    const tagInput = document.getElementById('tag');

    currentType = typeSelect.value;

    // Clear existing options
    tagSelect.innerHTML = '<option value="">Select a tag...</option>';

    // Add predefined tags for the selected type
    if (predefinedTags && predefinedTags[currentType]) {
        predefinedTags[currentType].forEach(tag => {
            const option = document.createElement('option');
            option.value = tag;
            option.textContent = tag;

            tagSelect.appendChild(option);
        });
    } else {}

    // Reset to dropdown mode
    tagSelect.classList.remove('d-none');
    tagInput.classList.add('d-none');
    tagInput.value = '';
}

function handleTagChange() {
    const tagSelect = document.getElementById('tag-select');
    const tagInput = document.getElementById('tag');

    if (tagSelect.value) {
        tagInput.value = tagSelect.value;
    }
}

function showCustomTag() {
    const tagSelect = document.getElementById('tag-select');
    const tagInput = document.getElementById('tag');

    tagSelect.classList.add('d-none');
    tagInput.classList.remove('d-none');
    tagInput.focus();
}

// Initialize tag options on page load
document.addEventListener('DOMContentLoaded', function() {
    updateTagOptions();

    // Set current tag value if editing
    const currentTag = '{{ old("tag", $transaction->tag ?? "") }}';
    if (currentTag) {
        const tagInput = document.getElementById('tag');
        tagInput.value = currentTag;

        // Check if it's a predefined tag
        const tagSelect = document.getElementById('tag-select');
        const options = Array.from(tagSelect.options);
        const matchingOption = options.find(option => option.value === currentTag);

        if (matchingOption) {
            tagSelect.value = currentTag;
        } else {
            // It's a custom tag, show the input field
            showCustomTag();
        }
    }
});
</script>
@endsection