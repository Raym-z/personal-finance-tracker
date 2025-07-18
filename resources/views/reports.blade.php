@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: #2563eb;">Reports & Insights</h2>
    <div class="mb-4">
        <p class="text-muted">Visualize your financial trends, category breakdowns, and progress over time. Use the
            filters below to explore your data.</p>
    </div>
    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label for="from" class="form-label">From</label>
            <input type="date" id="from" name="from" class="form-control" value="{{ $filters['from'] }}">
        </div>
        <div class="col-md-3">
            <label for="to" class="form-label">To</label>
            <input type="date" id="to" name="to" class="form-control" value="{{ $filters['to'] }}">
        </div>
        <div class="col-md-2">
            <label for="type" class="form-label">Type</label>
            <select id="type" name="type" class="form-select">
                <option value="both" {{ $filters['type'] == 'both' ? 'selected' : '' }}>Both</option>
                <option value="income" {{ $filters['type'] == 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ $filters['type'] == 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="category" class="form-label">Category</label>
            <select id="category" name="category" class="form-select">
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
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
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
                <div class="card-body flex-grow-1 d-flex flex-column">
                    <h5 class="card-title mb-3 fw-semibold" style="color: #2563eb;">Income & Expense Trends</h5>
                    <canvas id="trendChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 d-flex flex-column">
            <div class="card border-0 shadow-sm rounded-4 mb-4 flex-grow-1 d-flex flex-column"
                id="breakdown-chart-card">
                <div class="card-body flex-grow-1 d-flex flex-column">
                    <h5 class="card-title mb-3 fw-semibold" style="color: #2563eb;">Category Breakdown</h5>
                    <div class="piechart-container d-flex flex-column align-items-center" style="width: 100%;">
                        <canvas id="breakdownChart" height="220"></canvas>
                        <div class="piechart-legend-wrapper mt-3 w-100" style="max-height: 120px; overflow-y: auto;">
                            <div id="breakdownLegend" class="d-flex flex-wrap justify-content-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Trend chart
const trendData = {
    labels: @json($trend['months']),
    datasets: [{
            label: 'Income',
            data: @json($trend['income']),
            borderColor: '#198754',
            backgroundColor: 'rgba(25,135,84,0.1)',
            tension: 0.3,
            fill: true
        },
        {
            label: 'Expenses',
            data: @json($trend['expenses']),
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
    labels: @json($breakdown['labels']),
    datasets: [{
        data: @json($breakdown['data']),
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
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
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
        }
    }
};
// Custom legend for breakdown chart
function renderBreakdownLegend(chart) {
    const legendContainer = document.getElementById('breakdownLegend');
    legendContainer.innerHTML = '';
    const data = chart.data;
    if (!data.labels) return;
    data.labels.forEach(function(label, i) {
        const color = data.datasets[0].backgroundColor[i % data.datasets[0].backgroundColor.length];
        const value = data.datasets[0].data[i];
        const item = document.createElement('div');
        item.className = 'd-flex align-items-center me-3 mb-2';
        item.innerHTML =
            `<span style="display:inline-block;width:16px;height:16px;background:${color};border-radius:3px;margin-right:6px;"></span><span style="font-size:0.95em;">${label}</span>`;
        legendContainer.appendChild(item);
    });
}
const breakdownChart = new Chart(document.getElementById('breakdownChart'), breakdownConfig);
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
    }

    .piechart-container {
        min-height: 320px;
        max-height: 420px;
        width: 100%;
    }

    .piechart-legend-wrapper {
        max-height: 120px;
        overflow-y: auto;
        width: 100%;
    }
}

@media (max-width: 991.98px) {
    .piechart-legend-wrapper {
        max-height: 80px;
    }
}
</style>
@endsection
@endsection