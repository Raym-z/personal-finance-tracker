@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-2" style="color: #2563eb;">Reports & Insights</h2>
    <div class="mb-4">
        <p class="text-muted">Visualize your financial trends, category breakdowns, and progress over time. Use the
            filters below to explore your data.</p>
    </div>
    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label for="from" class="form-label">From</label>
            <input type="date" id="from" name="from" class="form-control form-control-sm rounded-3 w-100 filter-input"
                value="{{ $filters['from'] }}">
        </div>
        <div class="col-md-3">
            <label for="to" class="form-label">To</label>
            <input type="date" id="to" name="to" class="form-control form-control-sm rounded-3 w-100 filter-input"
                value="{{ $filters['to'] }}">
        </div>
        <div class="col-md-2">
            <label for="type" class="form-label">Type</label>
            <select id="type" name="type" class="form-select form-select-sm rounded-3 w-100 filter-input">
                <option value="both" {{ $filters['type'] == 'both' ? 'selected' : '' }}>Both</option>
                <option value="income" {{ $filters['type'] == 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ $filters['type'] == 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="category" class="form-label">Category</label>
            <select id="category" name="category" class="form-select form-select-sm rounded-3 w-100 filter-input">
                <option value="">All</option>
                @php
                $allTags = array_unique(array_merge(
                \App\Models\Transaction::getAllTags(Auth::id())['income'],
                \App\Models\Transaction::getAllTags(Auth::id())['expense']
                ));
                sort($allTags);
                @endphp
                @foreach($allTags as $tag)
                <option value="{{ $tag }}" {{ $filters['category'] == $tag ? 'selected' : '' }}>{{ $tag }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-sm rounded-3 w-100 ms-2"
                style="height:38px;">Filter</button>
        </div>
    </form>
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-4">
                <div class="fw-semibold text-secondary mb-2">Net Worth</div>
                <div class="fs-4 fw-bold">${{ number_format($summary['netWorth'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-4">
                <div class="fw-semibold text-secondary mb-2">Total Income</div>
                <div class="fs-5 fw-bold text-success">${{ number_format($summary['totalIncome'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-4">
                <div class="fw-semibold text-secondary mb-2">Total Expenses</div>
                <div class="fs-5 fw-bold text-danger">${{ number_format($summary['totalExpenses'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-4">
                <div class="fw-semibold text-secondary mb-2">Savings Rate</div>
                <div class="fs-5 fw-bold">{{ $summary['savingsRate'] }}%</div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4 align-items-stretch" id="reports-charts-row">
        <div class="col-lg-8 d-flex flex-column">
            <div class="card border-0 shadow-sm rounded-4 mb-4 flex-grow-1 d-flex flex-column" id="trend-chart-card">
                <div class="card-body flex-grow-1 d-flex flex-column" style="height:100%;">
                    <h5 class="card-title mb-3 fw-semibold" style="color: #2563eb;">Income & Expense Trends</h5>
                    <div class="chart-responsive-wrapper flex-grow-1" style="height:100%;">
                        <canvas id="trendChart" style="width:100%;height:100%;display:block;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 d-flex flex-column">
            <div class="card border-0 shadow-sm rounded-4 mb-4 flex-grow-1 d-flex flex-column"
                id="breakdown-chart-card">
                <div class="card-body flex-grow-1 d-flex flex-column" style="height:100%;">
                    <h5 class="card-title mb-3 fw-semibold" style="color: #2563eb;">Category Breakdown</h5>
                    <div class="row flex-grow-1 g-0 flex-nowrap piechart-legend-row">
                        <div class="col-12 col-md-7 d-flex align-items-center justify-content-center chart-container-col"
                            style="min-height: 180px; max-width: 240px;">
                            <div class="chart-container w-100"
                                style="position: relative; min-height: 180px; height: 1px; max-width: 240px;">
                                <canvas id="breakdownChart"></canvas>
                            </div>
                        </div>
                        <div class="col-12 col-md-5 legend-container-col d-flex flex-column" style="height: 100%;">
                            <div class="chart-legend flex-grow-1 legend-vertical-single d-flex flex-column"
                                style="overflow-y: auto; width: 100%; min-height: 120px; max-height: 340px;">
                                <div class="legend-list" id="breakdownLegend">
                                    {{-- Legend will be rendered by JS --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Parse backend data as JSON strings
const trendMonths = JSON.parse('@json($trend["months"])');
const trendIncome = JSON.parse('@json($trend["income"])');
const trendExpenses = JSON.parse('@json($trend["expenses"])');
const breakdownLabels = JSON.parse('@json($breakdown["labels"])');
const breakdownDataArr = JSON.parse('@json($breakdown["data"])');

// Trend chart
const trendData = {
    labels: trendMonths,
    datasets: [{
            label: 'Income',
            data: trendIncome,
            borderColor: '#198754',
            backgroundColor: 'rgba(25,135,84,0.1)',
            tension: 0.3,
            fill: true
        },
        {
            label: 'Expenses',
            data: trendExpenses,
            borderColor: '#dc3545',
            backgroundColor: 'rgba(220,53,69,0.1)',
            tension: 0.3,
            fill: true
        }
    ]
};
const trendConfig = {
    type: 'line',
    data: trendData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};
const trendChart = new Chart(document.getElementById('trendChart'), trendConfig);

// Breakdown chart
const breakdownData = {
    labels: breakdownLabels,
    datasets: [{
        data: breakdownDataArr,
        backgroundColor: [
            '#0d6efd', '#198754', '#dc3545', '#ffc107', '#0dcaf0', '#6c757d', '#212529', '#fd7e14',
            '#6610f2', '#20c997', '#e83e8c', '#adb5bd'
        ],
        borderWidth: 2,
        borderColor: '#fff'
    }]
};
const breakdownConfig = {
    type: 'doughnut',
    data: breakdownData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed;
                        return label + ': $' + value.toFixed(2);
                    }
                }
            }
        },
        cutout: '65%', // Make the doughnut a bit thicker
    }
};
const breakdownChart = new Chart(document.getElementById('breakdownChart'), breakdownConfig);

// Custom legend for breakdown chart (side legend)
function renderBreakdownLegend(chart) {
    const legendContainer = document.getElementById('breakdownLegend');
    legendContainer.innerHTML = '';
    const data = chart.data;
    if (!data.labels) return;
    data.labels.forEach(function(label, i) {
        const color = data.datasets[0].backgroundColor[i % data.datasets[0].backgroundColor.length];
        const value = data.datasets[0].data[i];
        const item = document.createElement('div');
        item.className = 'd-flex align-items-center mb-2';
        item.innerHTML =
            `<span class=\"badge me-2 chart-tag-badge\" style=\"background-color: ${color};\">${label}</span><small class=\"text-muted\">$${parseFloat(value).toFixed(2)}</small>`;
        legendContainer.appendChild(item);
    });
}
renderBreakdownLegend(breakdownChart);

// Dynamic height adjustment for cards
function adjustReportCardHeights() {
    if (window.innerWidth >= 992) {
        const pieCard = document.getElementById('breakdown-chart-card');
        const trendCard = document.getElementById('trend-chart-card');
        if (pieCard && trendCard) {
            const pieHeight = pieCard.offsetHeight;
            trendCard.style.height = pieHeight + 'px';
        }
    } else {
        document.getElementById('trend-chart-card').style.height = 'auto';
    }
}
adjustReportCardHeights();
window.addEventListener('resize', adjustReportCardHeights);
</script>
<style>
@media (min-width: 992px) {
    #reports-charts-row {
        display: flex;
    }

    #trend-chart-card,
    #breakdown-chart-card {
        min-height: 420px;
        max-height: 520px;
        display: flex;
        flex-direction: column;
    }

    #trend-chart-card .card-body,
    #breakdown-chart-card .card-body {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: stretch;
        height: 100%;
    }

    .chart-responsive-wrapper,
    .piechart-container {
        flex: 1 1 auto;
        min-height: 320px;
        max-height: 420px;
        width: 100%;
        height: 100%;
        position: relative;
    }

    .piechart-legend-wrapper {
        /* Remove max-height, let flexbox control height */
        overflow-y: auto;
        width: 100%;
        min-height: 120px;
        max-height: 340px;
    }

    .piechart-legend-row {
        display: flex;
        flex-wrap: nowrap;
    }

    .chart-container-col {
        min-width: 180px;
        max-width: 240px;
    }

    .legend-container-col {
        min-width: 140px;
        max-width: 180px;
    }

    .legend-vertical-single .legend-list {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        width: 100%;
    }

    .legend-vertical-single .legend-list>div {
        width: 100%;
        justify-content: flex-start;
    }
}

@media (max-width: 991.98px) {
    .piechart-legend-row {
        flex-direction: column !important;
        flex-wrap: wrap !important;
    }

    .chart-container-col {
        max-width: 100% !important;
        min-width: 120px !important;
    }

    .legend-container-col {
        max-width: 100% !important;
        min-width: 100px !important;
    }

    .legend-vertical-single .legend-list {
        flex-direction: row !important;
        flex-wrap: wrap !important;
        align-items: flex-start !important;
        justify-content: flex-start !important;
    }
}

.chart-tag-badge {
    color: white;
}

.filter-input {
    height: 38px !important;
    font-size: 0.95rem !important;
    padding-top: 0.375rem !important;
    padding-bottom: 0.375rem !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    /* matches rounded-3 */
    background: #fff !important;
    box-shadow: none !important;
}

.filter-input:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25) !important;
    outline: none !important;
}
</style>
@endsection