@extends('layouts.app')

@section('title', 'User Management - Admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.dashboard') }}" style="color: #4A4A4A; text-decoration: none;">
                <i class="bi bi-arrow-left"></i> Back to Admin Dashboard
            </a>
            <h2 class="mt-2"><i class="bi bi-people"></i> User Management</h2>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> All Users</h5>
            <span class="text-muted">Total: {{ $users->total() }} users</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-secondary' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->diffForHumans() }}</td>
                        <td>
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <select name="role" onchange="this.form.submit()" 
                                    class="form-select form-select-sm d-inline w-auto"
                                    style="background-color: #F0F0EB; border-color: #E8E8E3; color: #1E2D4C;">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline ms-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Hapus user ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer" style="background-color: #F0F0EB; border-top: 1px solid #E8E8E3;">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection