@extends('layouts.app')

@section('title', 'Country Comparison - Supply Chain Risk Intelligence')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 style="color: #4fc3f7;"><i class="bi bi-bar-chart-line"></i> Country Comparison Engine</h2>
            <p class="text-muted">Compare risk indicators between two countries</p>
        </div>
    </div>

    <!-- Country Selector -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-5 mb-2">
                            <label class="text-muted mb-1">Country A</label>
                            <select id="countryA" class="form-select"
                                style="background-color: #252836; border-color: #2a2d3e; color: #e0e0e0;">
                                <option value="">-- Pilih Negara --</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->code }}">{{ $country->name }} ({{ $country->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 text-center mb-2">
                            <span style="color: #4fc3f7; font-size: 1.5rem; font-weight: bold;">VS</span>
                        </div>
                        <div class="col-md-5 mb-2">
                            <label class="text-muted mb-1">Country B</label>
                            <select id="countryB" class="form-select"
                                style="background-color: #252836; border-color: #2a2d3e; color: #e0e0e0;">
                                <option value="">-- Pilih Negara --</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->code }}">{{ $country->name }} ({{ $country->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button onclick="compareCountries()" class="btn px-5"
                            style="background-color: #4fc3f7; color: #0f1117;">
                            <i class="bi bi-arrow-left-right"></i> Compare
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comparison Result -->
    <div id="comparisonResult" style="display:none;">
        <div class="row mb-4">
            <!-- Country A -->
            <div class="col-md-6 mb-3">
                <div class="card" id="cardA">
                    <div class="card-header text-center">
                        <h4 style="color: #4fc3f7;" id="nameA">Country A</h4>
                    </div>
                    <div class="card-body" id="dataA"></div>
                </div>
            </div>
            <!-- Country B -->
            <div class="col-md-6 mb-3">
                <div class="card" id="cardB">
                    <div class="card-header text-center">
                        <h4 style="color: #ffa726;" id="nameB">Country B</h4>
                    </div>
                    <div class="card-body" id="dataB"></div>
                </div>
            </div>
        </div>

        <!-- Comparison Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0" style="color: #4fc3f7;"><i class="bi bi-bar-chart"></i> Risk Score Comparison</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="comparisonChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let comparisonChart = null;

function compareCountries() {
    const codeA = document.getElementById('countryA').value;
    const codeB = document.getElementById('countryB').value;

    if (!codeA || !codeB) {
        alert('Pilih dua negara terlebih dahulu!');
        return;
    }

    Promise.all([
        fetch(`/api/countries/${codeA}`).then(r => r.json()),
        fetch(`/api/countries/${codeB}`).then(r => r.json()),
        fetch(`/api/risk/${codeA}`).then(r => r.json()),
        fetch(`/api/risk/${codeB}`).then(r => r.json()),
        fetch(`/api/economic/${codeA}`).then(r => r.json()),
        fetch(`/api/economic/${codeB}`).then(r => r.json()),
    ]).then(([countryA, countryB, riskA, riskB, ecoA, ecoB]) => {
        document.getElementById('comparisonResult').style.display = 'block';

        document.getElementById('nameA').textContent = countryA.data?.name ?? codeA;
        document.getElementById('nameB').textContent = countryB.data?.name ?? codeB;

        document.getElementById('dataA').innerHTML = renderCountryData(countryA.data, riskA.data, ecoA.data, '#4fc3f7');
        document.getElementById('dataB').innerHTML = renderCountryData(countryB.data, riskB.data, ecoB.data, '#ffa726');

        renderChart(
            countryA.data?.name ?? codeA,
            countryB.data?.name ?? codeB,
            riskA.data,
            riskB.data
        );
    });
}

function renderCountryData(country, risk, eco, color) {
    if (!country) return '<p class="text-muted text-center">Data tidak tersedia</p>';

    return `
        <div class="mb-3">
            <p class="text-muted mb-1">Region</p>
            <h6>${country.region ?? 'N/A'} - ${country.subregion ?? ''}</h6>
        </div>
        <div class="mb-3">
            <p class="text-muted mb-1">Currency</p>
            <h6>${country.currency_name ?? 'N/A'} (${country.currency_code ?? 'N/A'})</h6>
        </div>
        ${eco ? `
        <div class="mb-3">
            <p class="text-muted mb-1">GDP</p>
            <h6 style="color: #66bb6a;">$${(eco.gdp / 1e9).toFixed(2)}B</h6>
        </div>
        <div class="mb-3">
            <p class="text-muted mb-1">Inflation Rate</p>
            <h6 style="color: ${eco.inflation_rate > 5 ? '#ef5350' : '#ffa726'};">${eco.inflation_rate}%</h6>
        </div>
        <div class="mb-3">
            <p class="text-muted mb-1">Population</p>
            <h6>${parseInt(eco.population).toLocaleString()}</h6>
        </div>` : '<p class="text-muted">Data ekonomi belum tersedia</p>'}
        ${risk ? `
        <div class="mb-2 mt-3">
            <p class="text-muted mb-1">Total Risk Score</p>
            <h3 style="color: ${color};">${risk.total_risk} 
                <span class="badge ${risk.risk_level == 'Low' ? 'bg-success' : (risk.risk_level == 'Medium' ? 'bg-warning' : 'bg-danger')}">${risk.risk_level}</span>
            </h3>
        </div>` : '<p class="text-muted">Risk score belum tersedia</p>'}
    `;
}

function renderChart(nameA, nameB, riskA, riskB) {
    const ctx = document.getElementById('comparisonChart').getContext('2d');
    if (comparisonChart) comparisonChart.destroy();

    comparisonChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Weather Risk', 'Inflation Risk', 'Currency Risk', 'News Risk', 'Total Risk'],
            datasets: [
                {
                    label: nameA,
                    data: riskA ? [riskA.weather_risk, riskA.inflation_risk, riskA.currency_risk, riskA.news_risk, riskA.total_risk] : [0,0,0,0,0],
                    backgroundColor: 'rgba(79, 195, 247, 0.7)',
                    borderColor: '#4fc3f7',
                    borderWidth: 1
                },
                {
                    label: nameB,
                    data: riskB ? [riskB.weather_risk, riskB.inflation_risk, riskB.currency_risk, riskB.news_risk, riskB.total_risk] : [0,0,0,0,0],
                    backgroundColor: 'rgba(255, 167, 38, 0.7)',
                    borderColor: '#ffa726',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#e0e0e0' } }
            },
            scales: {
                x: { ticks: { color: '#e0e0e0' }, grid: { color: '#2a2d3e' } },
                y: { ticks: { color: '#e0e0e0' }, grid: { color: '#2a2d3e' }, min: 0, max: 100 }
            }
        }
    });
}
</script>
@endpush