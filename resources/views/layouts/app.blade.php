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
        body { background-color: #F5F5F0; color: #1E2D4C; }
        .sidebar { background-color: #4A4A4A; min-height: 100vh; width: 250px; position: fixed; left: 0; top: 0; z-index: 100; padding-top: 20px; }
        .main-content { margin-left: 250px; padding: 20px; }
        .navbar-brand { color: #FFFFFF !important; font-weight: bold; font-size: 1rem; }
        .nav-link { color: #E0E0E0 !important; padding: 10px 20px; }
        .nav-link:hover, .nav-link.active { color: #FFFFFF !important; background-color: #606060; border-left: 3px solid #FFFFFF; }
        .card { background-color: #FFFFFF; border: 1px solid #E8E8E3; border-radius: 16px; box-shadow: 0 2px 12px rgba(30,45,76,0.07); }
        .card-header { background-color: #F0F0EB; border-bottom: 1px solid #E8E8E3; border-radius: 16px 16px 0 0 !important; }
        .stat-card { border-radius: 16px; padding: 20px; }
        .badge-low { background-color: #ACBDAA; color: #1E2D4C; }
        .badge-medium { background-color: #e65100; color: #fff; }
        .badge-high { background-color: #b71c1c; color: #fff; }
        .table { color: #1E2D4C; }
        .table thead th { background-color: #F0F0EB; border-color: #E8E8E3; color: #1E2D4C !important; }
        .table td { border-color: #E8E8E3; color: #1E2D4C !important; }
        .table-hover tbody tr:hover { background-color: #F5F5F0; }
        #map { height: 400px; border-radius: 12px; }
        .risk-bar { height: 8px; border-radius: 4px; background-color: #E8E8E3; }
        .risk-bar-fill { height: 100%; border-radius: 4px; }
        .card-body { color: #1E2D4C; }
        h1, h2, h3, h4, h5, h6 { color: #1E2D4C; }
        p { color: #1E2D4C; }
        small { color: #858585; }
        .text-muted { color: #858585 !important; }
        th, td { color: #1E2D4C !important; }
        input::placeholder { color: #858585 !important; }
        input:focus { background-color: #F0F0EB !important; color: #1E2D4C !important; box-shadow: none !important; border-color: #ACBDAA !important; }
        select option { background-color: #FFFFFF; color: #1E2D4C; }
        .form-select { color: #1E2D4C !important; background-color: #F0F0EB; border-color: #E8E8E3; }
        label { color: #858585; }
        .form-control { background-color: #F0F0EB; border-color: #E8E8E3; color: #1E2D4C; }
        a { color: #1E2D4C; }
        a:hover { color: #ACBDAA; }
        .btn { border-radius: 10px; }
        code { color: #1E2D4C; background-color: #F0F0EB; padding: 2px 6px; border-radius: 4px; }
        input[type="text"], input[type="search"], select, textarea {
            background-color: #F0F0EB !important;
            border-color: #E8E8E3 !important;
            color: #1E2D4C !important;
        }
        input[type="text"]::placeholder, input[type="search"]::placeholder {
            color: #858585 !important;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="px-3 mb-4">
            <span class="navbar-brand">
                <i class="bi bi-globe2"></i> SupplyChain<br>
                <small style="color: #ACBDAA; font-size: 0.7rem;">Risk Intelligence Platform</small>
            </span>
        </div>
        <nav>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                @if(auth()->check() && auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-shield-lock me-2"></i> Admin Panel
                    </a>
                </li>
                @endif
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
                    <a class="nav-link {{ request()->routeIs('visualization') ? 'active' : '' }}" href="{{ route('visualization') }}">
                        <i class="bi bi-graph-up me-2"></i> Data Visualization
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('watchlist') ? 'active' : '' }}" href="{{ route('watchlist') }}">
                        <i class="bi bi-star me-2"></i> Watchlist
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Logout -->
        <div class="px-3 mt-4" style="position: absolute; bottom: 20px; width: 210px;">
            @auth
            <div class="mb-2" style="color: #E0E0E0; font-size: 0.85rem;">
                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                <span class="badge bg-secondary ms-1" style="font-size: 0.7rem;">{{ auth()->user()->role }}</span>
            </div>
            @endauth
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="btn w-100" style="background-color: #606060; color: #FFFFFF; border-radius: 10px;">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
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