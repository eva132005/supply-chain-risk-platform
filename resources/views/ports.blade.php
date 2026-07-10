@extends('layouts.app')

@section('title', 'Port Monitor - Supply Chain Risk Intelligence')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 style="color: #4A4A4A;"><i class="bi bi-anchor"></i> Port Location Monitor</h2>
            <p class="text-muted">Global port and airport locations with interactive map</p>
        </div>
    </div>

    <!-- Map -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0" style="color: #4A4A4A;"><i class="bi bi-map"></i> Global Port Map</h5>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 450px; border-radius: 0 0 12px 12px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5 mb-2">
                            <input type="text" id="searchPort" class="form-control" placeholder="Cari nama pelabuhan..."
                                style="background-color: #252836; border-color: #2a2d3e; color: #e0e0e0;">
                        </div>
                        <div class="col-md-5 mb-2">
                            <input type="text" id="searchCountry" class="form-control" placeholder="Cari negara (kode, contoh: IDN)..."
                                style="background-color: #252836; border-color: #2a2d3e; color: #e0e0e0;">
                        </div>
                        <div class="col-md-2 mb-2">
                            <button onclick="searchPorts()" class="btn w-100" style="background-color: #4A4A4A; color: #0f1117;">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Port Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #4A4A4A;"><i class="bi bi-list-ul"></i> Port List</h5>
                    <span class="text-muted">Total: {{ $ports->total() }} ports</span>
                </div>
                <div class="card-body p-0" id="portTableContainer">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Port Name</th>
                                <th>Country</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Coordinates</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ports as $port)
                            <tr>
                                <td>{{ $port->name }}</td>
                                <td>{{ $port->country->name ?? $port->country_name ?? 'N/A' }}</td>
                                <td><span class="badge bg-secondary">{{ $port->port_code }}</span></td>
                                <td>{{ $port->harbor_type ?? 'N/A' }}</td>
                                <td><small class="text-muted">{{ $port->latitude }}, {{ $port->longitude }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" style="background-color: #252836; border-top: 1px solid #2a2d3e;">
                    {{ $ports->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.pagination .page-link { background-color: #252836; border-color: #2a2d3e; color: #e0e0e0; }
.pagination .page-item.active .page-link { background-color: #4A4A4A; border-color: #4A4A4A; color: #0f1117; }
</style>
@endpush

@push('scripts')
<script>
// Initialize map
const map = L.map('map', { zoomControl: true }).setView([20, 0], 2);
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '© OpenStreetMap © CartoDB'
}).addTo(map);

// Load ports for map
fetch('/api/ports?search=')
    .then(res => res.json())
    .then(data => {
        data.data.forEach(port => {
            if (port.latitude && port.longitude) {
                L.circleMarker([port.latitude, port.longitude], {
                    radius: 4,
                    fillColor: '#4A4A4A',
                    color: '#fff',
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(map)
                .bindPopup(`<b>${port.name}</b><br>${port.country_name ?? ''}<br>Code: ${port.port_code ?? 'N/A'}`);
            }
        });
    });

function searchPorts() {
    const portName = document.getElementById('searchPort').value;
    const countryCode = document.getElementById('searchCountry').value;

    let url = '/api/ports?';
    if (portName) url += `search=${portName}&`;
    if (countryCode) url += `country=${countryCode}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.data.forEach(port => {
                html += `<tr>
                    <td>${port.name}</td>
                    <td>${port.country?.name ?? port.country_name ?? 'N/A'}</td>
                    <td><span class="badge bg-secondary">${port.port_code ?? ''}</span></td>
                    <td>${port.harbor_type ?? 'N/A'}</td>
                    <td><small class="text-muted">${port.latitude}, ${port.longitude}</small></td>
                </tr>`;
            });
            document.querySelector('#portTableContainer tbody').innerHTML = html;
        });
}

document.getElementById('searchPort').addEventListener('keypress', e => { if(e.key === 'Enter') searchPorts(); });
document.getElementById('searchCountry').addEventListener('keypress', e => { if(e.key === 'Enter') searchPorts(); });
</script>
@endpush