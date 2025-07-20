@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4" style="color: #2563eb;">Settings</h2>

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Custom Tag Creation -->
                    <div class="mb-3">
                        <h4 class="fw-semibold mb-3">Create Custom Tags</h4>
                        <p class="text-muted mb-4">Add your own custom tags for better organization of your
                            transactions.</p>

                        <form method="POST" action="{{ route('settings.custom-tags') }}">
                            @csrf
                            <div class="row d-flex align-items-end g-3 mb-3">
                                <div class="col-md-6">
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
                                <div class="col-md-2">
                                    <label for="new_tag_color" class="form-label">Color</label>
                                    <input type="color" id="new_tag_color" name="tag_color"
                                        class="form-control form-control-color rounded-3 custom-color-input w-100"
                                        value="#6c757d" style="height: 40px;">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit"
                                        class="btn btn-primary btn-sm rounded-3 custom-input-height w-100">Add</button>
                                </div>
                            </div>
                        </form>


                    </div>

                    <!-- Tag Color Customization -->
                    <div class="mb-5 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-semibold mb-0">Customize Tag Colors</h4>
                            <div class="d-flex align-items-center">
                                <label for="sort-select" class="form-label me-2 mb-0">Sort by:</label>
                                <select id="sort-select" class="form-select form-select-sm" style="width: auto;"
                                    onchange="changeSort()">
                                    <option value="alphabetical" {{ $sortBy === 'alphabetical' ? 'selected' : '' }}>
                                        Alphabetical</option>
                                    <option value="creation_time" {{ $sortBy === 'creation_time' ? 'selected' : '' }}>
                                        Creation Time</option>
                                </select>
                            </div>
                        </div>
                        <p class="text-muted mb-4">Choose your preferred colors for transaction tags to make them more
                            personal and easier to identify.</p>

                        <form method="POST" action="{{ route('settings.custom-tags') }}">
                            @csrf

                            <div class="row">
                                <!-- Income Tags -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="fw-semibold mb-3 text-success">Income Tags</h5>
                                    @foreach($incomeTags as $tag => $tagInfo)
                                    <div class="mb-3 tag-item">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="color-swatch me-3 tag-color-display"
                                                    data-color="{{ $tagInfo['color'] }}">
                                                </div>
                                                <span class="fw-medium">{{ $tag }}</span>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-link text-muted p-0 border-0 shadow-none"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <x-icons.three-dots width="16" height="16" />
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button
                                                            class="dropdown-item d-flex align-items-center edit-tag-btn"
                                                            type="button" data-tag="{{ $tag }}"
                                                            data-color="{{ $tagInfo['color'] }}">
                                                            <x-icons.edit width="16" height="16"
                                                                class="me-2 flex-shrink-0" />
                                                            Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button
                                                            class="dropdown-item d-flex align-items-center delete-btn delete-tag-btn"
                                                            type="button" data-tag="{{ $tag }}">
                                                            <x-icons.delete width="16" height="16"
                                                                class="me-2 flex-shrink-0" />
                                                            Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Expense Tags -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="fw-semibold mb-3 text-danger">Expense Tags</h5>
                                    @foreach($expenseTags as $tag => $tagInfo)
                                    <div class="mb-3 tag-item">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="color-swatch me-3 tag-color-display"
                                                    data-color="{{ $tagInfo['color'] }}">
                                                </div>
                                                <span class="fw-medium">{{ $tag }}</span>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-link text-muted p-0 border-0 shadow-none"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <x-icons.three-dots width="16" height="16" />
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button
                                                            class="dropdown-item d-flex align-items-center edit-tag-btn"
                                                            type="button" data-tag="{{ $tag }}"
                                                            data-color="{{ $tagInfo['color'] }}">
                                                            <x-icons.edit width="16" height="16"
                                                                class="me-2 flex-shrink-0" />
                                                            Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button
                                                            class="dropdown-item d-flex align-items-center delete-btn delete-tag-btn"
                                                            type="button" data-tag="{{ $tag }}">
                                                            <x-icons.delete width="16" height="16"
                                                                class="me-2 flex-shrink-0" />
                                                            Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex justify-content-start align-items-center mt-4">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back to
                                    Dashboard</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Tag Modal -->
    <div class="modal fade" id="editTagModal" tabindex="-1" aria-labelledby="editTagModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editTagForm">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="editTagModalLabel">Edit Tag</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-tag-name" class="form-label">Tag Name</label>
                            <input type="text" class="form-control" id="edit-tag-name" name="new_name" required
                                maxlength="50" pattern="[a-zA-Z0-9\s\-_]+">
                            <div class="form-text">Only letters, numbers, spaces, hyphens, and underscores are allowed.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit-tag-color" class="form-label">Tag Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" class="form-control form-control-color" id="edit-tag-color"
                                    name="new_color" onchange="updateEditPreview()" style="width: 60px; height: 40px;">
                                <input type="text" class="form-control" id="edit-tag-color-text"
                                    onchange="updateEditColorPicker()" placeholder="#000000" style="flex: 1;">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom Delete Confirmation Modal -->
<div class="modal fade" id="deleteTagModal" tabindex="-1" aria-labelledby="deleteTagModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger" id="deleteTagModalLabel">
                    Delete Tag
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-0">
                <div class="text-center mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#dc3545"
                            viewBox="0 0 16 16">
                            <path
                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                            <path fill-rule="evenodd"
                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                        </svg>
                    </div>
                    <h6 class="fw-semibold mb-2">Are you sure you want to delete this tag?</h6>
                    <p class="text-muted mb-0">This action cannot be undone. The tag will be permanently removed from
                        your account.</p>
                </div>

                <div class="alert alert-warning border-0 bg-light" role="alert">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="me-2 flex-shrink-0" viewBox="0 0 16 16">
                            <path
                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        <small class="fw-medium">Warning: If this tag is used in any transactions, it cannot be
                            deleted.</small>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                    Cancel
                </button>
                <form method="POST" id="deleteTagForm" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 fw-semibold">
                        Delete Tag
                    </button>
                </form>
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
    border: 1px solid #6c757d !important;
    background: #fff !important;
    border-radius: 0.375rem !important;
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
    border: 1px solid #6c757d !important;
    border-radius: 0.375rem !important;
    background: #fff !important;
    box-shadow: none !important;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.custom-color-input:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25) !important;
    outline: none !important;
}

/* Ensure all form controls have consistent styling */
.form-control {
    border: 1px solid #6c757d !important;
    border-radius: 0.375rem !important;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25) !important;
    outline: none !important;
}

.form-select {
    border: 1px solid #6c757d !important;
    border-radius: 0.375rem !important;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-select:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25) !important;
    outline: none !important;
}

.form-control-color {
    border: 1px solid #6c757d !important;
    border-radius: 0.375rem !important;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control-color:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25) !important;
    outline: none !important;
}

/* Hover effects for dropdown items */
.dropdown-item:hover {
    background-color: #e9ecef !important;
    transition: all 0.2s ease;
}

/* Special hover effect for delete button */
.delete-btn:hover {
    background-color: #dc3545 !important;
    color: white !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
}

.delete-btn:hover svg {
    fill: white !important;
}

/* Tag delete button specific styling */
.delete-tag-btn:hover {
    background-color: #dc3545 !important;
    color: white !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
}

.delete-tag-btn:hover svg {
    fill: white !important;
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
// Event listeners for edit and delete buttons
document.addEventListener('DOMContentLoaded', function() {
    // Edit tag buttons
    document.querySelectorAll('.edit-tag-btn').forEach(button => {
        button.addEventListener('click', function() {
            const tag = this.getAttribute('data-tag');
            const color = this.getAttribute('data-color');
            editTag(tag, color);
        });
    });

    // Delete tag buttons
    document.querySelectorAll('.delete-tag-btn').forEach(button => {
        button.addEventListener('click', function() {
            const tag = this.getAttribute('data-tag');
            deleteTag(tag);
        });
    });
});

function editTag(tagName, currentColor) {
    // Populate the edit modal
    document.getElementById('edit-tag-name').value = tagName;
    document.getElementById('edit-tag-color').value = currentColor;
    document.getElementById('edit-tag-color-text').value = currentColor;

    // Set the form action
    document.getElementById('editTagForm').action = '{{ route("settings.update-tag", ":tag") }}'.replace(':tag',
        tagName);

    // Show the modal
    const editModal = new bootstrap.Modal(document.getElementById('editTagModal'));
    editModal.show();
}

function deleteTag(tagName) {
    // Set the form action for the custom modal
    document.getElementById('deleteTagForm').action = '{{ route("settings.delete-tag", ":tag") }}'.replace(':tag',
        tagName);

    // Show the custom modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteTagModal'));
    deleteModal.show();
}

function updateEditPreview() {
    const color = document.getElementById('edit-tag-color').value;
    document.getElementById('edit-tag-color-text').value = color;
}

function updateEditColorPicker() {
    const color = document.getElementById('edit-tag-color-text').value;
    if (/^#[0-9A-F]{6}$/i.test(color)) {
        document.getElementById('edit-tag-color').value = color;
    }
}

function updatePreview(tag, color) {
    const preview = document.getElementById(`preview_${tag}`);
    const textInput = preview.parentElement.nextElementSibling.querySelector('input[type="text"]');
    const colorPicker = document.getElementById(`tag_${tag}`);

    preview.style.backgroundColor = color;
    preview.dataset.color = color;
    textInput.value = color;
    colorPicker.value = color;

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

function changeSort() {
    const sortSelect = document.getElementById('sort-select');
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('sort', sortSelect.value);
    window.location.href = currentUrl.toString();
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

    // Set colors for tag color displays
    document.querySelectorAll('.tag-color-display').forEach(function(element) {
        const color = element.dataset.color;
        if (color) {
            element.style.setProperty('--tag-color', color);
        }
    });
});
</script>

<style>
.color-swatch {
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    width: 20px;
    height: 20px;
    border-radius: 4px;
    border: 1px solid #dee2e6;
}

.color-swatch:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.tag-color-display {
    background-color: var(--tag-color);
}

.tag-item {
    transition: background-color 0.2s ease;
    border-radius: 6px;
    padding: 8px 12px;
}

.tag-item:hover {
    background-color: #f8f9fa;
}

/* Custom Dropdown Options Styling */
#sort-select option {
    border-radius: 8px;
    margin: 2px 0;
    padding: 8px 12px;
    background-color: #ffffff;
    color: #495057;
    font-weight: 500;
    transition: all 0.2s ease;
}

#sort-select option:hover {
    background-color: #f8f9fa;
    color: #2563eb;
}

#sort-select option:checked {
    background-color: #e3f2fd;
    color: #1976d2;
    font-weight: 600;
}
</style>
@endsection