@extends('layouts.app')

@section('title', 'Data Visualization - Supply Chain Risk Intelligence')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 style="color: #4A4A4A;"><i class="bi bi-graph-up"></i> Data Visualization Dashboard</h2>
            <p class="text-muted">GDP, Inflation, Currency & Risk trends analysis</p>
        </div>
    </div>

    <!-- Country Selector -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-2">
                            <select id="countrySelect" class="form-select">
                                <option value="">-- Pilih Negara untuk Visualisasi --</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button onclick="loadCharts()" class="btn w-100"
                                style="background-color: #4A4A4A; color: #FFFFFF;">
                                <i class="bi bi-bar-chart"></i> Load Charts
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <!-- GDP Chart -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-currency-dollar"></i> GDP Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="gdpChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Inflation Chart -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Inflation Rate</h5>
                </div>
                <div class="card-body">
                    <canvas id="inflationChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Currency Chart -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-currency-exchange"></i> Currency Rate (vs USD)</h5>
                </div>
                <div class="card-body">
                    <canvas id="currencyChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Risk Chart -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-shield-exclamation"></i> Risk Score Breakdown</h5>
                </div>
                <div class="card-body">
                    <canvas id="riskChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Trade Balance Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-arrow-left-right"></i> Trade Balance (Exports vs Imports)</h5>
                </div>
                <div class="card-body">
                    <canvas id="tradeChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let gdpChart, inflationChart, currencyChart, riskChart, tradeChart;

// Load countries for dropdown
fetch('/api/countries')
    .then(r => r.json())
    .then(data => {
        const select = document.getElementById('countrySelect');
        data.data.forEach(country => {
            const option = document.createElement('option');
            option.value = country.code;
            option.textContent = `${country.name} (${country.code})`;
            select.appendChild(option);
        });
    });

function loadCharts() {
    const code = document.getElementById('countrySelect').value;
    if (!code) { alert('Pilih negara terlebih dahulu!'); return; }

    Promise.all([
        fetch(`/api/economic/${code}`).then(r => r.json()),
        fetch(`/api/currency/${code}`).then(r => r.json()),
        fetch(`/api/risk/${code}`).then(r => r.json()),
        fetch(`/api/countries/${code}`).then(r => r.json()),
    ]).then(([eco, currency, risk, country]) => {
        const countryName = country.data?.name ?? code;
        renderGdpChart(countryName, eco.data);
        renderInflationChart(countryName, eco.data);
        renderCurrencyChart(countryName, currency.data);
        renderRiskChart(countryName, risk.data);
        renderTradeChart(countryName, eco.data);
    });
}

function renderGdpChart(name, eco) {
    if (gdpChart) gdpChart.destroy();
    gdpChart = new Chart(document.getElementById('gdpChart'), {
        type: 'bar',
        data: {
            labels: [name],
            datasets: [{
                label: 'GDP (Billion USD)',
                data: [eco ? (eco.gdp / 1e9).toFixed(2) : 0],
                backgroundColor: 'rgba(172, 189, 170, 0.7)',
                borderColor: '#ACBDAA',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#4A4A4A' } } },
            scales: {
                x: { ticks: { color: '#4A4A4A' }, grid: { color: '#E8E8E3' } },
                y: { ticks: { color: '#4A4A4A' }, grid: { color: '#E8E8E3' } }
            }
        }
    });
}

function renderInflationChart(name, eco) {
    if (inflationChart) inflationChart.destroy();
    const rate = eco?.inflation_rate ?? 0;
    const color = rate > 10 ? '#ef5350' : rate > 5 ? '#ffa726' : '#66bb6a';
    inflationChart = new Chart(document.getElementById('inflationChart'), {
        type: 'doughnut',
        data: {
            labels: ['Inflation Rate', 'Remaining'],
            datasets: [{
                data: [Math.min(rate, 100), Math.max(0, 100 - rate)],
                backgroundColor: [color, '#E8E8E3'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#4A4A4A' } },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ctx.label === 'Inflation Rate' ? `${rate}%` : ''
                    }
                }
            }
        }
    });
}

function renderCurrencyChart(name, currency) {
    if (currencyChart) currencyChart.destroy();
    currencyChart = new Chart(document.getElementById('currencyChart'), {
        type: 'bar',
        data: {
            labels: [`1 USD = ? ${currency?.target_currency ?? 'N/A'}`],
            datasets: [{
                label: `Exchange Rate`,
                data: [currency?.rate ?? 0],
                backgroundColor: 'rgba(206, 192, 187, 0.7)',
                borderColor: '#CECOBB',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#4A4A4A' } } },
            scales: {
                x: { ticks: { color: '#4A4A4A' }, grid: { color: '#E8E8E3' } },
                y: { ticks: { color: '#4A4A4A' }, grid: { color: '#E8E8E3' } }
            }
        }
    });
}

function renderRiskChart(name, risk) {
    if (riskChart) riskChart.destroy();
    riskChart = new Chart(document.getElementById('riskChart'), {
        type: 'radar',
        data: {
            labels: ['Weather Risk', 'Inflation Risk', 'Currency Risk', 'News Risk'],
            datasets: [{
                label: name,
                data: risk ? [risk.weather_risk, risk.inflation_risk, risk.currency_risk, risk.news_risk] : [0,0,0,0],
                backgroundColor: 'rgba(172, 189, 170, 0.3)',
                borderColor: '#ACBDAA',
                borderWidth: 2,
                pointBackgroundColor: '#ACBDAA',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#4A4A4A' } } },
            scales: {
                r: {
                    ticks: { color: '#4A4A4A', backdropColor: 'transparent' },
                    grid: { color: '#E8E8E3' },
                    pointLabels: { color: '#4A4A4A' },
                    min: 0, max: 100
                }
            }
        }
    });
}

function renderTradeChart(name, eco) {
    if (tradeChart) tradeChart.destroy();
    tradeChart = new Chart(document.getElementById('tradeChart'), {
        type: 'bar',
        data: {
            labels: [name],
            datasets: [
                {
                    label: 'Exports (Billion USD)',
                    data: [eco ? (eco.exports_value / 1e9).toFixed(2) : 0],
                    backgroundColor: 'rgba(172, 189, 170, 0.7)',
                    borderColor: '#ACBDAA',
                    borderWidth: 2,
                    borderRadius: 8,
                },
                {
                    label: 'Imports (Billion USD)',
                    data: [eco ? (eco.imports_value / 1e9).toFixed(2) : 0],
                    backgroundColor: 'rgba(239, 83, 80, 0.4)',
                    borderColor: '#ef5350',
                    borderWidth: 2,
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#4A4A4A' } } },
            scales: {
                x: { ticks: { color: '#4A4A4A' }, grid: { color: '#E8E8E3' } },
                y: { ticks: { color: '#4A4A4A' }, grid: { color: '#E8E8E3' } }
            }
        }
    });
}
</script>
@endpush