@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-shield-lock"></i> Admin Dashboard</h2>
            <p class="text-muted">Manage users, articles, and port data</p>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left: 4px solid #4A4A4A;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Users</p>
                            <h3>{{ $totalUsers }}</h3>
                        </div>
                        <i class="bi bi-people" style="font-size: 2rem; color: #4A4A4A; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left: 4px solid #ACBDAA;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Ports</p>
                            <h3>{{ $totalPorts }}</h3>
                        </div>
                        <i class="bi bi-anchor" style="font-size: 2rem; color: #ACBDAA; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left: 4px solid #858585;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Articles</p>
                            <h3>{{ $totalArticles }}</h3>
                        </div>
                        <i class="bi bi-file-text" style="font-size: 2rem; color: #858585; opacity: 0.5;"></i>
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
                            <h3>{{ $totalRisks }}</h3>
                        </div>
                        <i class="bi bi-shield-exclamation" style="font-size: 2rem; color: #ef5350; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center p-4">
                    <i class="bi bi-people" style="font-size: 3rem; color: #4A4A4A;"></i>
                    <h5 class="mt-3">User Management</h5>
                    <p class="text-muted">Manage user accounts and roles</p>
                    <a href="{{ route('admin.users') }}" class="btn w-100" style="background-color: #4A4A4A; color: #FFFFFF;">
                        <i class="bi bi-arrow-right"></i> Manage Users
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center p-4">
                    <i class="bi bi-file-text" style="font-size: 3rem; color: #ACBDAA;"></i>
                    <h5 class="mt-3">Article Management</h5>
                    <p class="text-muted">Manage analysis articles</p>
                    <a href="{{ route('admin.articles') }}" class="btn w-100" style="background-color: #ACBDAA; color: #1E2D4C;">
                        <i class="bi bi-arrow-right"></i> Manage Articles
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center p-4">
                    <i class="bi bi-anchor" style="font-size: 3rem; color: #858585;"></i>
                    <h5 class="mt-3">Port Management</h5>
                    <p class="text-muted">Manage port dataset</p>
                    <a href="{{ route('admin.ports') }}" class="btn w-100" style="background-color: #858585; color: #FFFFFF;">
                        <i class="bi bi-arrow-right"></i> Manage Ports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Recent Users</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-secondary' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection