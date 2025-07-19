@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4" style="color: #2563eb;">Settings</h2>

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Custom Tag Creation -->
                    <div class="mb-3">
                        <h4 class="fw-semibold mb-3">Create Custom Tags</h4>
                        <p class="text-muted mb-4">Add your own custom tags for better organization of your
                            transactions.</p>

                        <form method="POST" action="{{ route('settings.custom-tags') }}">
                            @csrf
                            <div class="row d-flex align-items-end g-2 mb-3">
                                <div class="col-md-5">
                                    <label for="new_tag_name" class="form-label">Tag Name</label>
                                    <input type="text" id="new_tag_name" name="tag_name"
                                        class="form-control form-control-sm rounded-3 custom-input custom-input-height"
                                        placeholder="e.g., Coffee, Gym, Netflix" maxlength="50" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="new_tag_type" class="form-label">Type</label>
                                    <select id="new_tag_type" name="tag_type"
                                        class="form-select form-select-sm rounded-3 custom-input custom-input-height"
                                        required>
                                        <option value="">Select type...</option>
                                        <option value="income">Income</option>
                                        <option value="expense">Expense</option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label for="new_tag_color" class="form-label mb-1">Color</label>
                                    <input type="color" id="new_tag_color" name="tag_color"
                                        class="form-control form-control-color rounded-3 me-2 custom-color-input"
                                        value="#6c757d" style="width: 60px; height: 40px;">
                                </div>
                                <div class="col-auto">
                                    <button type="submit"
                                        class="btn btn-primary btn-sm rounded-3 custom-input-height">Add
                                        Custom
                                        Tag</button>
                                </div>
                            </div>
                    </div>
                    </form>

                    @if(!empty($customTags))
                    <div class="mt-4">
                        <h5 class="fw-semibold mb-3">Your Custom Tags</h5>
                        <div class="row">
                            @foreach($customTags as $tag => $tagInfo)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded">
                                    <div class="d-flex align-items-center">
                                        <span class="badge me-2 custom-tag-badge"
                                            data-color="{{ $tagInfo['color'] }}">{{ $tag }}</span>
                                        <small class="text-muted">{{ ucfirst($tagInfo['type']) }}</small>
                                    </div>
                                    <form method="POST" action="{{ route('settings.delete-custom-tag') }}"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="tag_name" value="{{ $tag }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this custom tag?')">
                                            <x-icons.delete width="14" height="14" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Tag Color Customization -->
                <div class="mb-5 p-4">
                    <h4 class="fw-semibold mb-3">Customize Tag Colors</h4>
                    <p class="text-muted mb-4">Choose your preferred colors for transaction tags to make them more
                        personal and easier to identify.</p>

                    <form method="POST" action="{{ route('settings.tag-colors') }}">
                        @csrf

                        <div class="row">
                            <!-- Income Tags -->
                            <div class="col-md-6 mb-4">
                                <h5 class="fw-semibold mb-3 text-success">Income Tags</h5>
                                @foreach(['Salary', 'Freelance', 'Investment', 'Gift', 'Bonus', 'Other'] as $tag)
                                <div class="mb-3">
                                    <label for="tag_{{ $tag }}" class="form-label d-flex align-items-center">
                                        <span class="badge me-2 tag-preview" id="preview_{{ $tag }}"
                                            data-color="{{ $tagColors[$tag] ?? '#6c757d' }}"></span>
                                        {{ $tag }}
                                    </label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="color" name="tag_colors[{{ $tag }}]" id="tag_{{ $tag }}"
                                            class="form-control form-control-color"
                                            value="{{ $tagColors[$tag] ?? '#6c757d' }}"
                                            onchange="updatePreview('{{ $tag }}', this.value)"
                                            style="width: 60px; height: 40px;">
                                        <input type="text" class="form-control"
                                            value="{{ $tagColors[$tag] ?? '#6c757d' }}"
                                            onchange="updateColorPicker('{{ $tag }}', this.value)" placeholder="#000000"
                                            style="flex: 1;">
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Expense Tags -->
                            <div class="col-md-6 mb-4">
                                <h5 class="fw-semibold mb-3 text-danger">Expense Tags</h5>
                                @foreach(['Food', 'Transportation', 'Housing', 'Utilities', 'Entertainment',
                                'Healthcare', 'Shopping', 'Education', 'Other'] as $tag)
                                <div class="mb-3">
                                    <label for="tag_{{ $tag }}" class="form-label d-flex align-items-center">
                                        <span class="badge me-2 tag-preview" id="preview_{{ $tag }}"
                                            data-color="{{ $tagColors[$tag] ?? '#6c757d' }}"></span>
                                        {{ $tag }}
                                    </label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="color" name="tag_colors[{{ $tag }}]" id="tag_{{ $tag }}"
                                            class="form-control form-control-color"
                                            value="{{ $tagColors[$tag] ?? '#6c757d' }}"
                                            onchange="updatePreview('{{ $tag }}', this.value)"
                                            style="width: 60px; height: 40px;">
                                        <input type="text" class="form-control"
                                            value="{{ $tagColors[$tag] ?? '#6c757d' }}"
                                            onchange="updateColorPicker('{{ $tag }}', this.value)" placeholder="#000000"
                                            style="flex: 1;">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back to
                                Dashboard</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
.tag-preview,
.custom-tag-badge {
    color: white;
}

.custom-input {
    border: 1px solid #ced4da !important;
    background: #fff !important;
    border-radius: 0.5rem !important;
    box-shadow: none !important;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.custom-input:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25) !important;
    outline: none !important;
}

.custom-input-height {
    height: 40px !important;
    min-height: 40px !important;
    box-sizing: border-box;
}

.custom-color-input {
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    /* matches Bootstrap's rounded-3 */
    background: #fff !important;
    box-shadow: none !important;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.custom-color-input:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25) !important;
    outline: none !important;
}

.piechart-legend-wrapper {
    padding-top: 0.5rem;
}

.legend-vertical-single .legend-list {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    /* allow wrapping */
    align-items: flex-start;
    width: 100%;
}

.legend-vertical-single .legend-list>div {
    width: 100%;
    justify-content: flex-start;
    margin-bottom: 0.5rem;
}

.chart-tag-badge {
    color: white;
    min-width: 80px;
    white-space: nowrap;
}
</style>

<script>
function updatePreview(tag, color) {
    const preview = document.getElementById(`preview_${tag}`);
    const textInput = preview.parentElement.nextElementSibling.querySelector('input[type="text"]');

    preview.style.backgroundColor = color;
    preview.dataset.color = color;
    textInput.value = color;

    // Update text color for contrast
    const luminance = getLuminance(color);
    preview.style.color = luminance > 0.5 ? '#000000' : '#ffffff';
}

function updateColorPicker(tag, color) {
    const colorPicker = document.getElementById(`tag_${tag}`);
    const preview = document.getElementById(`preview_${tag}`);

    // Validate hex color format
    if (/^#[0-9A-F]{6}$/i.test(color)) {
        colorPicker.value = color;
        preview.style.backgroundColor = color;
        preview.dataset.color = color;

        // Update text color for contrast
        const luminance = getLuminance(color);
        preview.style.color = luminance > 0.5 ? '#000000' : '#ffffff';
    }
}

function getLuminance(hexColor) {
    const hex = hexColor.replace('#', '');
    const r = parseInt(hex.substr(0, 2), 16);
    const g = parseInt(hex.substr(2, 2), 16);
    const b = parseInt(hex.substr(4, 2), 16);
    return (0.299 * r + 0.587 * g + 0.114 * b) / 255;
}

// Initialize colors on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial colors for tag previews
    document.querySelectorAll('.tag-preview').forEach(function(preview) {
        const color = preview.dataset.color;
        if (color) {
            preview.style.backgroundColor = color;
            const luminance = getLuminance(color);
            preview.style.color = luminance > 0.5 ? '#000000' : '#ffffff';
        }
    });

    // Set initial colors for custom tag badges
    document.querySelectorAll('.custom-tag-badge').forEach(function(badge) {
        const color = badge.dataset.color;
        if (color) {
            badge.style.backgroundColor = color;
            const luminance = getLuminance(color);
            badge.style.color = luminance > 0.5 ? '#000000' : '#ffffff';
        }
    });
});
</script>
@endsection