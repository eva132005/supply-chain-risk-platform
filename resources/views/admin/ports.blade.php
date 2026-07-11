@extends('layouts.app')

@section('title', 'Port Management - Admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.dashboard') }}" style="color: #4A4A4A; text-decoration: none;">
                <i class="bi bi-arrow-left"></i> Back to Admin Dashboard
            </a>
            <h2 class="mt-2"><i class="bi bi-anchor"></i> Port Management</h2>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> All Ports</h5>
            <span class="text-muted">Total: {{ $ports->total() }} ports</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Port Name</th>
                        <th>Country</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ports as $port)
                    <tr>
                        <td>{{ $port->name }}</td>
                        <td>{{ $port->country->name ?? $port->country_name ?? 'N/A' }}</td>
                        <td><span class="badge bg-secondary">{{ $port->port_code }}</span></td>
                        <td>{{ $port->harbor_type ?? 'N/A' }}</td>
                        <td>
                            <form action="{{ route('admin.ports.delete', $port) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus port ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer" style="background-color: #F0F0EB; border-top: 1px solid #E8E8E3;">
            {{ $ports->links() }}
        </div>
    </div>
</div>
@endsection