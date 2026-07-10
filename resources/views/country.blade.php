@extends('layouts.app')

@section('title', $country->name . ' - Risk Intelligence')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('dashboard') }}" style="color: #4A4A4A; text-decoration: none;">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
            <h2 class="mt-2" style="color: #4A4A4A;">
                <i class="bi bi-globe2"></i> {{ $country->name }}
                <small class="text-muted fs-6">{{ $country->code }} | {{ $country->region }}</small>
            </h2>
        </div>
    </div>

    <!-- Country Info Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card p-3">
                <p class="text-muted mb-1"><i class="bi bi-building"></i> Capital</p>
                <h5>{{ $country->capital ?? 'N/A' }}</h5>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card p-3">
                <p class="text-muted mb-1"><i class="bi bi-currency-exchange"></i> Currency</p>
                <h5>{{ $country->currency_name ?? 'N/A' }} ({{ $country->currency_code ?? 'N/A' }})</h5>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card p-3">
                <p class="text-muted mb-1"><i class="bi bi-map"></i> Region</p>
                <h5>{{ $country->subregion ?? $country->region ?? 'N/A' }}</h5>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card p-3">
                <p class="text-muted mb-1"><i class="bi bi-geo-alt"></i> Coordinates</p>
                <h5>{{ $country->latitude }}, {{ $country->longitude }}</h5>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Risk Score -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4A4A4A;"><i class="bi bi-shield-check"></i> Risk Score</h5>
                </div>
                <div class="card-body">
                    @php $risk = $country->riskScores->first(); @endphp
                    @if($risk)
                        <div class="text-center mb-3">
                            <h1 style="color: {{ $risk->risk_level == 'Low' ? '#66bb6a' : ($risk->risk_level == 'Medium' ? '#ffa726' : '#ef5350') }}; font-size: 3rem;">
                                {{ $risk->total_risk }}
                            </h1>
                            <span class="badge fs-6 {{ $risk->risk_level == 'Low' ? 'bg-success' : ($risk->risk_level == 'Medium' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $risk->risk_level }} Risk
                            </span>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Weather Risk</small><small>{{ $risk->weather_risk }}</small>
                            </div>
                            <div class="risk-bar"><div class="risk-bar-fill bg-info" style="width: {{ $risk->weather_risk }}%"></div></div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Inflation Risk</small><small>{{ $risk->inflation_risk }}</small>
                            </div>
                            <div class="risk-bar"><div class="risk-bar-fill bg-warning" style="width: {{ $risk->inflation_risk }}%"></div></div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Currency Risk</small><small>{{ $risk->currency_risk }}</small>
                            </div>
                            <div class="risk-bar"><div class="risk-bar-fill bg-primary" style="width: {{ $risk->currency_risk }}%"></div></div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small>News Risk</small><small>{{ $risk->news_risk }}</small>
                            </div>
                            <div class="risk-bar"><div class="risk-bar-fill bg-danger" style="width: {{ $risk->news_risk }}%"></div></div>
                        </div>
                    @else
                        <p class="text-muted text-center">Belum ada risk score</p>
                        <button class="btn btn-sm w-100" style="background-color: #4A4A4A; color: #0f1117;" onclick="calculateRisk('{{ $country->code }}')">
                            <i class="bi bi-calculator"></i> Hitung Risk Score
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Weather Data -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4A4A4A;"><i class="bi bi-cloud-sun"></i> Weather Data</h5>
                </div>
                <div class="card-body">
                    @php $weather = $country->weatherData->first(); @endphp
                    @if($weather)
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <i class="bi bi-thermometer-half" style="font-size: 1.5rem; color: #ffa726;"></i>
                                <p class="mb-0 mt-1">Temperature</p>
                                <h4 style="color: #ffa726;">{{ $weather->temperature }}°C</h4>
                            </div>
                            <div class="col-6 mb-3">
                                <i class="bi bi-cloud-rain" style="font-size: 1.5rem; color: #4A4A4A;"></i>
                                <p class="mb-0 mt-1">Rainfall</p>
                                <h4 style="color: #4A4A4A;">{{ $weather->rainfall }} mm</h4>
                            </div>
                            <div class="col-6 mb-3">
                                <i class="bi bi-wind" style="font-size: 1.5rem; color: #66bb6a;"></i>
                                <p class="mb-0 mt-1">Wind Speed</p>
                                <h4 style="color: #66bb6a;">{{ $weather->wind_speed }} km/h</h4>
                            </div>
                            <div class="col-6 mb-3">
                                <i class="bi bi-exclamation-triangle" style="font-size: 1.5rem; color: #ef5350;"></i>
                                <p class="mb-0 mt-1">Storm Risk</p>
                                <h4 style="color: #ef5350;">{{ $weather->storm_risk }}</h4>
                            </div>
                        </div>
                        <p class="text-muted text-center mb-0"><small>Condition: {{ $weather->weather_condition }}</small></p>
                    @else
                        <p class="text-muted text-center">Belum ada data cuaca</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Economic Data -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4A4A4A;"><i class="bi bi-graph-up"></i> Economic Data</h5>
                </div>
                <div class="card-body">
                    @php $economic = $country->economicData->first(); @endphp
                    @if($economic)
                        <div class="mb-3">
                            <p class="text-muted mb-1">GDP (Current USD)</p>
                            <h5 style="color: #66bb6a;">${{ number_format($economic->gdp / 1e9, 2) }}B</h5>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted mb-1">Inflation Rate</p>
                            <h5 style="color: {{ $economic->inflation_rate > 5 ? '#ef5350' : '#ffa726' }};">{{ $economic->inflation_rate }}%</h5>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted mb-1">Population</p>
                            <h5>{{ number_format($economic->population) }}</h5>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="text-muted mb-1">Exports</p>
                                <h6 style="color: #66bb6a;">${{ number_format($economic->exports_value / 1e9, 2) }}B</h6>
                            </div>
                            <div class="col-6">
                                <p class="text-muted mb-1">Imports</p>
                                <h6 style="color: #ef5350;">${{ number_format($economic->imports_value / 1e9, 2) }}B</h6>
                            </div>
                        </div>
                    @else
                        <p class="text-muted text-center">Belum ada data ekonomi</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Exchange Rate & News -->
    <div class="row mb-4">
        <!-- Exchange Rate -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4A4A4A;"><i class="bi bi-currency-dollar"></i> Exchange Rate</h5>
                </div>
                <div class="card-body">
                    @php $rate = $country->exchangeRates->first(); @endphp
                    @if($rate)
                        <div class="text-center">
                            <p class="text-muted">1 USD =</p>
                            <h2 style="color: #4A4A4A;">{{ number_format($rate->rate, 2) }}</h2>
                            <h5>{{ $rate->target_currency }}</h5>
                        </div>
                    @else
                        <p class="text-muted text-center">Belum ada data kurs</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- News -->
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4A4A4A;"><i class="bi bi-newspaper"></i> Latest News</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($country->newsCache as $news)
                    <div class="p-3" style="border-bottom: 1px solid #2a2d3e;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <a href="{{ $news->url }}" target="_blank" style="color: #e0e0e0; text-decoration: none;">
                                    <p class="mb-1">{{ Str::limit($news->title, 100) }}</p>
                                </a>
                                <small class="text-muted">{{ $news->source }} | {{ $news->published_at?->diffForHumans() }}</small>
                            </div>
                            <span class="badge ms-2 {{ $news->sentiment == 'Positive' ? 'bg-success' : ($news->sentiment == 'Negative' ? 'bg-danger' : 'bg-secondary') }}">
                                {{ $news->sentiment }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted">Belum ada berita untuk negara ini</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calculateRisk(code) {
    fetch(`/api/risk/calculate/${code}`, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Risk score berhasil dihitung! Halaman akan di-refresh.');
                location.reload();
            }
        });
}
</script>
@endpush