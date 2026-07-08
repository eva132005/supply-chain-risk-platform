<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Global Supply Chain Risk Intelligence Platform')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    
    <style>
        body { background-color: #0f1117; color: #e0e0e0; }
        .sidebar { background-color: #1a1d27; min-height: 100vh; width: 250px; position: fixed; left: 0; top: 0; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 20px; }
        .navbar-brand { color: #4fc3f7 !important; font-weight: bold; font-size: 1rem; }
        .nav-link { color: #9e9e9e !important; padding: 10px 20px; }
        .nav-link:hover, .nav-link.active { color: #4fc3f7 !important; background-color: #252836; border-left: 3px solid #4fc3f7; }
        .card { background-color: #1a1d27; border: 1px solid #2a2d3e; border-radius: 12px; }
        .card-header { background-color: #252836; border-bottom: 1px solid #2a2d3e; }
        .stat-card { border-radius: 12px; padding: 20px; }
        .badge-low { background-color: #1b5e20; color: #a5d6a7; }
        .badge-medium { background-color: #e65100; color: #ffcc80; }
        .badge-high { background-color: #b71c1c; color: #ef9a9a; }
        .table { color: #e0e0e0; }
        .table thead th { background-color: #252836; border-color: #2a2d3e; }
        .table td { border-color: #2a2d3e; }
        #map { height: 400px; border-radius: 12px; }
        .risk-bar { height: 8px; border-radius: 4px; background-color: #2a2d3e; }
        .risk-bar-fill { height: 100%; border-radius: 4px; }
        .card-body { color: #e0e0e0; }
        .card p { color: #e0e0e0; }
        h1, h2, h3, h4, h5, h6 { color: #e0e0e0; }
        p { color: #e0e0e0; }
        small { color: #9e9e9e; }
        .text-muted { color: #9e9e9e !important; }
        th, td { color: #e0e0e0 !important; }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="px-3 mb-4">
            <span class="navbar-brand">
                <i class="bi bi-globe2"></i> SupplyChain<br>
                <small style="color: #4fc3f7; font-size: 0.7rem;">Risk Intelligence Platform</small>
            </span>
        </div>
        <nav>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ports') ? 'active' : '' }}" href="{{ route('ports') }}">
                        <i class="bi bi-anchor me-2"></i> Port Monitor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('news') ? 'active' : '' }}" href="{{ route('news') }}">
                        <i class="bi bi-newspaper me-2"></i> News Intelligence
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('comparison') ? 'active' : '' }}" href="{{ route('comparison') }}">
                        <i class="bi bi-bar-chart-line me-2"></i> Country Comparison
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('watchlist') ? 'active' : '' }}" href="{{ route('watchlist') }}">
                        <i class="bi bi-star me-2"></i> Watchlist
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>
</html>