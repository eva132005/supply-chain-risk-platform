@extends('layouts.app')

@section('title', 'Watchlist - Supply Chain Risk Intelligence')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 style="color: #4fc3f7;"><i class="bi bi-star"></i> Favorite Monitoring List</h2>
            <p class="text-muted">Monitor your favorite countries</p>
        </div>
    </div>

    <!-- Add to Watchlist -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4fc3f7;"><i class="bi bi-plus-circle"></i> Add Country to Watchlist</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <select id="countrySelect" class="form-select"
                                style="background-color: #252836; border-color: #2a2d3e; color: #e0e0e0;">
                                <option value="">-- Pilih Negara --</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->code }}">{{ $country->name }} ({{ $country->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button onclick="addToWatchlist()" class="btn w-100"
                                style="background-color: #4fc3f7; color: #0f1117;">
                                <i class="bi bi-star-fill"></i> Add to Watchlist
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Watchlist -->
    <div class="row" id="watchlistContainer">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4fc3f7;"><i class="bi bi-star"></i> My Watchlist</h5>
                </div>
                <div class="card-body" id="watchlistItems">
                    <p class="text-muted text-center" id="emptyMessage">Belum ada negara di watchlist. Tambahkan negara di atas!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load watchlist from localStorage
let watchlist = JSON.parse(localStorage.getItem('watchlist') || '[]');
renderWatchlist();

function addToWatchlist() {
    const code = document.getElementById('countrySelect').value;
    const name = document.getElementById('countrySelect').options[document.getElementById('countrySelect').selectedIndex].text;

    if (!code) { alert('Pilih negara terlebih dahulu!'); return; }
    if (watchlist.find(w => w.code === code)) { alert('Negara sudah ada di watchlist!'); return; }

    watchlist.push({ code, name });
    localStorage.setItem('watchlist', JSON.stringify(watchlist));
    renderWatchlist();
}

function removeFromWatchlist(code) {
    watchlist = watchlist.filter(w => w.code !== code);
    localStorage.setItem('watchlist', JSON.stringify(watchlist));
    renderWatchlist();
}

function renderWatchlist() {
    const container = document.getElementById('watchlistItems');
    const emptyMsg = document.getElementById('emptyMessage');

    if (watchlist.length === 0) {
        container.innerHTML = '<p class="text-muted text-center">Belum ada negara di watchlist. Tambahkan negara di atas!</p>';
        return;
    }

    let html = '<div class="row">';
    watchlist.forEach(item => {
        html += `
        <div class="col-md-4 mb-3" id="watch-${item.code}">
            <div class="card" style="border: 1px solid #4fc3f7;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 style="color: #4fc3f7;" class="mb-0">${item.name}</h5>
                        <button onclick="removeFromWatchlist('${item.code}')" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <p class="text-muted mb-2"><small>${item.code}</small></p>
                    <div id="risk-${item.code}">
                        <div class="spinner-border spinner-border-sm text-info" role="status"></div>
                        <small class="text-muted ms-2">Loading risk data...</small>
                    </div>
                    <a href="/country/${item.code}" class="btn btn-sm mt-2 w-100"
                        style="background-color: #252836; border: 1px solid #4fc3f7; color: #4fc3f7;">
                        <i class="bi bi-eye"></i> View Detail
                    </a>
                </div>
            </div>
        </div>`;
    });
    html += '</div>';
    container.innerHTML = html;

    // Load risk data for each country
    watchlist.forEach(item => {
        fetch(`/api/risk/${item.code}`)
            .then(r => r.json())
            .then(data => {
                const el = document.getElementById(`risk-${item.code}`);
                if (data.success && data.data) {
                    const risk = data.data;
                    const color = risk.risk_level == 'Low' ? '#66bb6a' : (risk.risk_level == 'Medium' ? '#ffa726' : '#ef5350');
                    el.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Risk Score</span>
                            <span style="color: ${color}; font-weight: bold;">${risk.total_risk} 
                                <span class="badge ${risk.risk_level == 'Low' ? 'bg-success' : (risk.risk_level == 'Medium' ? 'bg-warning' : 'bg-danger')}">${risk.risk_level}</span>
                            </span>
                        </div>`;
                } else {
                    el.innerHTML = '<small class="text-muted">Risk data belum tersedia</small>';
                }
            });
    });
}
</script>
@endpush