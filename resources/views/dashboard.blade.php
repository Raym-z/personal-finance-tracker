@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-2">
        <div class="col-12 col-md-8">
            <h1 class="fw-bold mb-3" style="color: #1a202c;">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-muted mb-4">Here's an overview of your finances at a glance.</p>
        </div>
    </div>

    <!-- Dashboard Summary Cards -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-4">
                <div class="fw-semibold text-secondary mb-2">Total Balance</div>
                <div class="fs-3 fw-bold" style="color: #2563eb;">${{ number_format($totalBalance, 2) }}</div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-4">
                <div class="fw-semibold text-secondary mb-2">Total Income</div>
                <div class="fs-4 fw-bold text-success">${{ number_format($totalIncome, 2) }}</div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-4">
                <div class="fw-semibold text-secondary mb-2">Total Expenses</div>
                <div class="fs-4 fw-bold text-danger">${{ number_format($totalExpenses, 2) }}</div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-4">
                <div class="fw-semibold text-secondary mb-2">Savings Goals</div>
                <div class="fs-4 fw-bold" style="color: #1a202c;">${{ number_format($savingsGoals, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Transactions -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title fw-semibold mb-0" style="color: #2563eb;">Recent Transactions</h5>
                        <div class="dropdown">
                            <button
                                class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center p-0"
                                type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                style="width: 48px; height: 48px;">
                                <x-icons.filter width="22" height="22" class="text-white" />
                            </button>
                            <form method="GET" class="dropdown-menu p-3 border-0 shadow-lg"
                                aria-labelledby="filterDropdown" style="min-width: 280px; border-radius: 12px;">
                                <div class="mb-2">
                                    <label for="type" class="form-label mb-0">Type:</label>
                                    <select name="type" id="type" class="form-select form-select-sm rounded-3">
                                        <option value="">All</option>
                                        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>
                                            Income</option>
                                        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>
                                            Expense</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mb-0">Tags:</label>
                                    <div id="tag-checkboxes" class="border rounded-3 p-2"
                                        style="max-height: 150px; overflow-y: auto; display: none;">
                                        <div class="form-check form-check-sm">
                                            <input class="form-check-input" type="checkbox" id="select-all-tags"
                                                value="">
                                            <label class="form-check-label fw-semibold" for="select-all-tags">
                                                Select All
                                            </label>
                                        </div>
                                        <hr class="my-2">
                                        <div id="tag-options">
                                            <!-- Tag options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                    <small class="text-muted" id="tag-help">Select a type first to filter by
                                        tags</small>
                                </div>
                                <div class="mb-2">
                                    <label for="sort" class="form-label mb-0">Sort:</label>
                                    <select name="sort" id="sort" class="form-select form-select-sm rounded-3">
                                        <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>
                                            Most Recent</option>
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Oldest
                                        </option>
                                    </select>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit"
                                        class="btn btn-sm btn-primary flex-grow-1 rounded-3">Apply</button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-danger rounded-2">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if(request('type') || request('tags') || request('tag') || request('sort') != 'desc')
                    <div class="mb-3">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <small class="text-muted me-2">Active filters:</small>
                            @if(request('type'))
                            <span class="badge bg-primary px-3 py-2 fw-semibold">{{ ucfirst(request('type')) }}</span>
                            @endif
                            @if(request('tags'))
                            @foreach(request('tags') as $tag)
                            <span
                                class="badge bg-{{ \App\Models\Transaction::getTagColor($tag, Auth::id()) }} px-3 py-2 fw-semibold">{{ $tag }}</span>
                            @endforeach
                            @elseif(request('tag'))
                            <span
                                class="badge bg-{{ \App\Models\Transaction::getTagColor(request('tag'), Auth::id()) }} px-3 py-2 fw-semibold">{{ request('tag') }}</span>
                            @endif
                            @if(request('sort') && request('sort') != 'desc')
                            <span
                                class="badge bg-secondary px-3 py-2 fw-semibold">{{ request('sort') == 'asc' ? 'Oldest First' : 'Most Recent' }}</span>
                            @endif
                            <a href="{{ route('dashboard') }}"
                                class="badge bg-light text-dark text-decoration-none px-3 py-2 border fw-semibold">Clear
                                All</a>
                        </div>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="transactions-container"
                        style="max-height: 400px; overflow-y: auto; padding-right: 12px;">
                        <ul class="list-group list-group-flush">
                            @forelse ($recentTransactions as $transaction)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>
                                    <div class="d-flex align-items-center mb-1">
                                        <span
                                            class="badge bg-{{ \App\Models\Transaction::getTagColor($transaction->tag, Auth::id()) }} me-2">{{ $transaction->tag }}</span>
                                        <span class="text-muted small">{{ ucfirst($transaction->type) }}</span>
                                        @if($transaction->image_path)
                                        <span class="ms-2" title="Has attached image">
                                            <x-icons.image width="16" height="16" class="text-muted" />
                                        </span>
                                        @endif
                                    </div>
                                    <span class="fw-semibold">{{ $transaction->description ?: 'No description' }}</span>
                                </span>
                                <span>
                                    <span
                                        class="fw-bold {{ $transaction->type === 'income' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                    </span>
                                    <div class="dropdown d-inline-block ms-2">
                                        <button class="btn btn-sm btn-link text-muted p-0 border-0 shadow-none"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <x-icons.three-dots width="16" height="16" />
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($transaction->image_path)
                                            <li>
                                                <button class="dropdown-item d-flex align-items-center" type="button"
                                                    onclick="viewImage('{{ Storage::url($transaction->image_path) }}', '{{ $transaction->description ?: $transaction->tag }}')">
                                                    <x-icons.image width="16" height="16" class="me-2 flex-shrink-0" />
                                                    View Image
                                                </button>
                                            </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('transactions.edit', $transaction->id) }}">
                                                    <x-icons.edit width="16" height="16" class="me-2 flex-shrink-0" />
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('transactions.destroy', $transaction->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item d-flex align-items-center delete-btn"
                                                        onclick="return confirm('Delete this transaction?')">
                                                        <x-icons.delete width="16" height="16"
                                                            class="me-2 flex-shrink-0" />
                                                        Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </span>
                            </li>
                            @empty
                            <li class="list-group-item px-0">No recent transactions.</li>
                            @endforelse
                        </ul>
                    </div>
                    @if ($recentTransactions->count() >= 6)
                    <div class="text-end mt-2">
                        <small class="text-muted">Scroll to see more transactions</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Links & Visual Summary -->
        <div class="col-12 col-lg-6 d-flex flex-column" id="right-dashboard-col">
            <div class="card border-0 shadow-sm rounded-4 mb-3 flex-shrink-0" id="quick-links-card">
                <div class="card-body text-center py-3 px-2">
                    <h5 class="card-title mb-2 fw-semibold" style="color: #2563eb; font-size: 1.1rem;">Quick Links</h5>
                    <a href="{{ route('transactions.create', ['type' => 'income']) }}"
                        class="btn btn-primary me-2 mb-1 btn-sm">Add Income</a>
                    <a href="{{ route('transactions.create', ['type' => 'expense']) }}"
                        class="btn btn-outline-primary mb-1 btn-sm">Add Expense</a>
                </div>
            </div>
            <div class="card border-0 shadow-sm rounded-4 flex-grow-1 d-flex flex-column" id="pie-chart-card">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0 fw-semibold" style="color: #2563eb; font-size: 1.1rem;">Categories
                        </h5>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Chart Toggle">
                            <button type="button" class="btn btn-outline-primary active"
                                id="toggle-expenses">Expenses</button>
                            <button type="button" class="btn btn-outline-primary" id="toggle-incomes">Incomes</button>
                            <button type="button" class="btn btn-outline-primary" id="toggle-both">Both</button>
                        </div>
                    </div>
                    <div class="row flex-grow-1 g-0 flex-nowrap piechart-legend-row">
                        <div class="col-12 col-md-7 d-flex align-items-center justify-content-center chart-container-col"
                            style="min-height: 220px;">
                            <div class="chart-container w-100"
                                style="position: relative; min-height: 220px; height: 1px; max-width: 340px;">
                                <canvas id="spendingChart"></canvas>
                            </div>
                        </div>
                        <div
                            class="col-12 col-md-5 legend-container-col d-flex flex-column justify-content-center align-items-center">
                            <div class="chart-legend flex-grow-1 legend-vertical-single d-flex flex-column justify-content-center align-items-center"
                                style="max-height: 320px; overflow-y: auto; width: 100%;">
                                <div class="legend-list" id="chart-legend-rows">
                                    {{-- Legend will be rendered by JS --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="no-data-message" class="text-muted text-center py-4 d-none">
                        <x-icons.plus-circle width="48" height="48" class="mb-3" />
                        <p class="mb-0">No data available</p>
                        <small>Add some transactions to see your breakdown</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image View Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Transaction Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Transaction receipt" class="img-fluid" style="max-height: 70vh;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function viewImage(imageUrl, transactionTitle) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModalLabel').textContent = transactionTitle;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

// Tag data from backend
const incomeTags = JSON.parse('{!! json_encode($incomeTags) !!}');
const expenseTags = JSON.parse('{!! json_encode($expenseTags) !!}');

function initializeTagFiltering() {
    const typeSelect = document.getElementById('type');
    const tagCheckboxes = document.getElementById('tag-checkboxes');
    const tagHelp = document.getElementById('tag-help');

    // Set initial state based on current type selection
    updateTagOptions();

    // Add event listener for type changes
    typeSelect.addEventListener('change', function() {
        updateTagOptions();
    });

    // Handle "Select All" checkbox
    document.getElementById('select-all-tags').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('#tag-options input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
}

function updateTagOptions() {
    const typeSelect = document.getElementById('type');
    const tagCheckboxes = document.getElementById('tag-checkboxes');
    const tagOptions = document.getElementById('tag-options');
    const tagHelp = document.getElementById('tag-help');
    const selectAllCheckbox = document.getElementById('select-all-tags');
    const selectedType = typeSelect.value;

    // Clear existing options
    tagOptions.innerHTML = '';
    selectAllCheckbox.checked = false;

    if (selectedType === 'income') {
        // Show tag checkboxes and populate with income tags
        tagCheckboxes.style.display = 'block';
        tagHelp.style.display = 'none';

        incomeTags.forEach(tag => {
            const div = document.createElement('div');
            div.className = 'form-check form-check-sm';
            div.innerHTML = `
                <input class="form-check-input" type="checkbox" name="tags[]" id="tag-${tag}" value="${tag}">
                <label class="form-check-label" for="tag-${tag}">
                    ${tag}
                </label>
            `;
            tagOptions.appendChild(div);
        });
    } else if (selectedType === 'expense') {
        // Show tag checkboxes and populate with expense tags
        tagCheckboxes.style.display = 'block';
        tagHelp.style.display = 'none';

        expenseTags.forEach(tag => {
            const div = document.createElement('div');
            div.className = 'form-check form-check-sm';
            div.innerHTML = `
                <input class="form-check-input" type="checkbox" name="tags[]" id="tag-${tag}" value="${tag}">
                <label class="form-check-label" for="tag-${tag}">
                    ${tag}
                </label>
            `;
            tagOptions.appendChild(div);
        });
    } else {
        // No type selected, hide tag checkboxes
        tagCheckboxes.style.display = 'none';
        tagHelp.style.display = 'block';
    }
}

// Chart data from backend
const chartDataExpenses = JSON.parse('{!! json_encode($chartDataExpenses) !!}');
const chartDataIncomes = JSON.parse('{!! json_encode($chartDataIncomes) !!}');
const chartDataBoth = JSON.parse('{!! json_encode($chartDataBoth) !!}');
const legendData = {
    'expenses': JSON.parse('{!! json_encode($spendingByTags) !!}'),
    'incomes': JSON.parse('{!! json_encode($incomeByTags) !!}'),
    'both': JSON.parse('{!! json_encode($bothByTags) !!}')
};

let currentChartType = 'expenses';
let spendingChart = null;

function renderChart(type) {
    let data, legendItems;
    if (type === 'expenses') {
        data = chartDataExpenses;
        legendItems = legendData.expenses;
    } else if (type === 'incomes') {
        data = chartDataIncomes;
        legendItems = legendData.incomes;
    } else {
        data = chartDataBoth;
        legendItems = legendData.both;
    }

    // Show/hide no data message
    if (data.labels.length === 0) {
        document.getElementById('spendingChart').classList.add('d-none');
        document.getElementById('chart-legend-rows').innerHTML = '';
        document.getElementById('no-data-message').classList.remove('d-none');
        return;
    } else {
        document.getElementById('spendingChart').classList.remove('d-none');
        document.getElementById('no-data-message').classList.add('d-none');
    }

    // Destroy previous chart
    if (spendingChart) {
        spendingChart.destroy();
    }
    const ctx = document.getElementById('spendingChart').getContext('2d');
    spendingChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.data,
                backgroundColor: data.backgroundColors,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': $' + value.toFixed(2) + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            // Ensure small segments are still visible
            elements: {
                arc: {
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }
            }
        }
    });

    // Render legend - show all items with percentages
    const legendRows = document.getElementById('chart-legend-rows');
    legendRows.innerHTML = '';

    // Create a map of tag names to legend items for easy lookup
    const legendMap = {};
    legendItems.forEach(item => {
        legendMap[item.tag] = item;
    });

    // Calculate total for percentage
    const total = data.data.reduce((sum, value) => sum + value, 0);

    // Render legend items that correspond to chart data
    data.labels.forEach((label, idx) => {
        const legendItem = legendMap[label];
        if (legendItem) {
            const color = data.backgroundColors[idx];
            const tag = label;
            let amount = legendItem.total_amount;
            let amountStr = '';
            let amountClass = '';

            if (currentChartType === 'both') {
                if (amount < 0) {
                    amountStr = '-$' + Math.abs(amount).toFixed(2);
                    amountClass = 'text-danger';
                } else {
                    amountStr = '+$' + Math.abs(amount).toFixed(2);
                    amountClass = 'text-success';
                }
                // Don't show percentage for 'both' filter
                const div = document.createElement('div');
                div.className = 'd-flex align-items-center mb-2';
                div.innerHTML =
                    `<span class=\"badge me-2 chart-tag-badge\" style=\"background-color: ${color};\">${tag}</span><small class=\"text-muted ${amountClass}\">${amountStr}</small>`;
                legendRows.appendChild(div);
            } else {
                // Calculate percentage only for income/expense filters
                const percentage = ((Math.abs(amount) / total) * 100).toFixed(1);
                amountStr = '$' + Math.abs(amount).toFixed(2);
                amountClass = '';

                const div = document.createElement('div');
                div.className = 'd-flex align-items-center mb-2';
                div.innerHTML =
                    `<span class=\"badge me-2 chart-tag-badge\" style=\"background-color: ${color};\">${tag}</span><small class=\"text-muted ${amountClass}\">${amountStr} (${percentage}%)</small>`;
                legendRows.appendChild(div);
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Chart toggle buttons
    document.getElementById('toggle-expenses').addEventListener('click', function() {
        setActiveToggle('expenses');
        renderChart('expenses');
    });
    document.getElementById('toggle-incomes').addEventListener('click', function() {
        setActiveToggle('incomes');
        renderChart('incomes');
    });
    document.getElementById('toggle-both').addEventListener('click', function() {
        setActiveToggle('both');
        renderChart('both');
    });

    // Initialize dynamic tag filtering
    initializeTagFiltering();

    // Initial chart
    renderChart('expenses');
    // Set badge colors for initial legend
    setTimeout(() => {
        document.querySelectorAll('.chart-tag-badge').forEach(function(badge) {
            const color = badge.dataset.color || badge.style.backgroundColor;
            if (color) {
                badge.style.backgroundColor = color;
            }
        });
    }, 100);
    // Dynamic height adjustment
    adjustCardHeights();
    window.addEventListener('resize', adjustCardHeights);
});

function setActiveToggle(type) {
    currentChartType = type;
    document.getElementById('toggle-expenses').classList.toggle('active', type === 'expenses');
    document.getElementById('toggle-incomes').classList.toggle('active', type === 'incomes');
    document.getElementById('toggle-both').classList.toggle('active', type === 'both');
}

function adjustCardHeights() {
    const transactionsCard = document.querySelector('.col-lg-6:first-child .card');
    const rightCol = document.getElementById('right-dashboard-col');
    const quickLinksCard = document.getElementById('quick-links-card');
    const pieChartCard = document.getElementById('pie-chart-card');
    if (transactionsCard && rightCol && quickLinksCard && pieChartCard) {
        const transactionsHeight = transactionsCard.offsetHeight;
        // Set minimum heights
        const minQuickLinks = 80;
        const minPieChart = 320;
        quickLinksCard.style.minHeight = minQuickLinks + 'px';
        pieChartCard.style.minHeight = minPieChart + 'px';
        // Make right column match left card height on desktop
        if (window.innerWidth >= 992) { // lg breakpoint
            rightCol.style.height = transactionsHeight + 'px';
            rightCol.style.maxHeight = transactionsHeight + 'px';
            // Distribute height between quick links and pie chart
            const quickLinksHeight = quickLinksCard.offsetHeight;
            pieChartCard.style.height = (transactionsHeight - quickLinksHeight) + 'px';
        } else {
            rightCol.style.height = 'auto';
            rightCol.style.maxHeight = 'none';
            pieChartCard.style.height = 'auto';
        }
    }
}
</script>

<style>
/* Hover effects for dropdown items */
.dropdown-item:hover {
    background-color: #e9ecef !important;
    /* transform: translateX(2px); */
    transition: all 0.2s ease;
}

/* Special hover effect for delete button */
.delete-btn:hover {
    background-color: #dc3545 !important;
    color: white !important;
    /* transform: translateX(2px); */
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
}

.delete-btn:hover svg {
    fill: white !important;
}

/* Chart tag badges */
.chart-tag-badge {
    color: white;
}

/* Enhanced filter interface styling */
.form-select.rounded-3 {
    border: 1px solid #dee2e6;
    transition: all 0.2s ease;
}

.form-select.rounded-3:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

#tag-checkboxes {
    border: 1px solid #dee2e6 !important;
    background-color: #f8f9fa;
    transition: all 0.2s ease;
}

#tag-checkboxes:hover {
    border-color: #adb5bd !important;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* Enhanced button styling */
.btn.rounded-3 {
    transition: all 0.2s ease;
}

.btn.rounded-3:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-danger.rounded-2 {
    transition: all 0.2s ease;
}

.btn-danger.rounded-2:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .transactions-container {
        max-height: 300px !important;
    }

    .chart-container {
        height: 250px !important;
    }
}

@media (min-width: 992px) {
    .card {
        transition: height 0.3s ease;
    }
}

.piechart-legend-row {
    flex-wrap: wrap;
}

.chart-container-col {
    min-width: 220px;
    max-width: 340px;
}

.legend-container-col {
    min-width: 180px;
}

.legend-vertical-single .legend-list {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.legend-vertical-single .legend-list>div {
    width: 100%;
    justify-content: flex-start;
}

@media (max-width: 991.98px) {
    .legend-vertical-single .legend-list {
        flex-direction: column !important;
        align-items: flex-start !important;
        justify-content: flex-start !important;
    }
}
</style>
@endsection