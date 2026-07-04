@extends('layouts.app')

@section('title', 'Dashboard - Supply Chain Risk Intelligence')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 style="color: #4fc3f7;"><i class="bi bi-speedometer2"></i> Global Supply Chain Risk Dashboard</h2>
            <p class="text-muted">Real-time monitoring of global supply chain risks</p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left: 4px solid #4fc3f7;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Countries</p>
                            <h3 style="color: #4fc3f7;">{{ $totalCountries }}</h3>
                        </div>
                        <i class="bi bi-globe2" style="font-size: 2rem; color: #4fc3f7; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left: 4px solid #66bb6a;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Ports</p>
                            <h3 style="color: #66bb6a;">{{ $totalPorts }}</h3>
                        </div>
                        <i class="bi bi-anchor" style="font-size: 2rem; color: #66bb6a; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left: 4px solid #ffa726;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">News Analyzed</p>
                            <h3 style="color: #ffa726;">{{ $totalNews }}</h3>
                        </div>
                        <i class="bi bi-newspaper" style="font-size: 2rem; color: #ffa726; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left: 4px solid #ef5350;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Risk Assessments</p>
                            <h3 style="color: #ef5350;">{{ $totalRisks }}</h3>
                        </div>
                        <i class="bi bi-shield-exclamation" style="font-size: 2rem; color: #ef5350; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Risk Score Table -->
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #4fc3f7;"><i class="bi bi-shield-check"></i> Latest Risk Scores</h5>
                    <a href="#" class="btn btn-sm" style="background-color: #252836; color: #4fc3f7; border: 1px solid #4fc3f7;">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Weather</th>
                                <th>Inflation</th>
                                <th>Currency</th>
                                <th>News</th>
                                <th>Total</th>
                                <th>Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestRisks as $risk)
                            <tr>
                                <td>
                                    <a href="{{ route('country.show', $risk->country->code) }}" style="color: #4fc3f7; text-decoration: none;">
                                        {{ $risk->country->name ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>{{ $risk->weather_risk }}</td>
                                <td>{{ $risk->inflation_risk }}</td>
                                <td>{{ $risk->currency_risk }}</td>
                                <td>{{ $risk->news_risk }}</td>
                                <td><strong>{{ $risk->total_risk }}</strong></td>
                                <td>
                                    @if($risk->risk_level == 'Low')
                                        <span class="badge badge-low">Low</span>
                                    @elseif($risk->risk_level == 'Medium')
                                        <span class="badge badge-medium">Medium</span>
                                    @else
                                        <span class="badge badge-high">High</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data risk score. Jalankan <code>php artisan risk:calculate</code></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent News -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4fc3f7;"><i class="bi bi-newspaper"></i> Recent News</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($recentNews as $news)
                    <div class="p-3" style="border-bottom: 1px solid #2a2d3e;">
                        <p class="mb-1" style="font-size: 0.85rem;">{{ Str::limit($news->title, 80) }}</p>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">{{ $news->country->name ?? 'Global' }}</small>
                            <span class="badge {{ $news->sentiment == 'Positive' ? 'bg-success' : ($news->sentiment == 'Negative' ? 'bg-danger' : 'bg-secondary') }}">
                                {{ $news->sentiment }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted">Belum ada berita</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Country Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4fc3f7;"><i class="bi bi-search"></i> Country Risk Lookup</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="countrySearch" class="form-control" 
                                    placeholder="Cari negara (contoh: Indonesia, Germany...)"
                                    style="background-color: #252836; border-color: #2a2d3e; color: #e0e0e0;">
                                <button class="btn" onclick="searchCountry()" 
                                    style="background-color: #4fc3f7; color: #0f1117;">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="searchResult" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchCountry() {
    const query = document.getElementById('countrySearch').value;
    if (!query) return;

    fetch(`/api/countries?search=${query}`)
        .then(res => res.json())
        .then(data => {
            if (data.data.length === 0) {
                document.getElementById('searchResult').innerHTML = '<p class="text-muted">Negara tidak ditemukan.</p>';
                return;
            }

            let html = '<div class="row">';
            data.data.slice(0, 6).forEach(country => {
                html += `
                <div class="col-md-4 mb-2">
                    <a href="/country/${country.code}" style="text-decoration: none;">
                        <div class="card p-3" style="cursor: pointer; transition: all 0.2s;" 
                             onmouseover="this.style.borderColor='#4fc3f7'" 
                             onmouseout="this.style.borderColor='#2a2d3e'">
                            <div class="d-flex align-items-center">
                                <div>
                                    <strong style="color: #4fc3f7;">${country.name}</strong><br>
                                    <small class="text-muted">${country.region ?? ''} | ${country.currency_code ?? ''}</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>`;
            });
            html += '</div>';
            document.getElementById('searchResult').innerHTML = html;
        });
}

document.getElementById('countrySearch').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') searchCountry();
});
</script>
@endpush