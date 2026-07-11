@extends('layouts.app')

@section('title', 'Article Management - Admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.dashboard') }}" style="color: #4A4A4A; text-decoration: none;">
                <i class="bi bi-arrow-left"></i> Back to Admin Dashboard
            </a>
            <h2 class="mt-2"><i class="bi bi-file-text"></i> Article Management</h2>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Article Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Article</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.articles.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control mt-1" 
                            placeholder="Article title" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Category</label>
                        <select name="category" class="form-select mt-1">
                            <option value="analysis">Analysis</option>
                            <option value="report">Report</option>
                            <option value="news">News</option>
                            <option value="research">Research</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Country (Optional)</label>
                        <select name="country_id" class="form-select mt-1">
                            <option value="">-- Global --</option>
                            @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label>Content</label>
                        <textarea name="content" class="form-control mt-1" rows="5" 
                            placeholder="Article content..." required></textarea>
                    </div>
                </div>
                <button type="submit" class="btn" style="background-color: #4A4A4A; color: #FFFFFF;">
                    <i class="bi bi-save"></i> Save Article
                </button>
            </form>
        </div>
    </div>

    <!-- Articles List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> All Articles</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Country</th>
                        <th>Author</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td>{{ Str::limit($article->title, 50) }}</td>
                        <td><span class="badge bg-secondary">{{ $article->category }}</span></td>
                        <td>{{ $article->country->name ?? 'Global' }}</td>
                        <td>{{ $article->user->name ?? 'N/A' }}</td>
                        <td>{{ $article->created_at->diffForHumans() }}</td>
                        <td>
                            <form action="{{ route('admin.articles.delete', $article) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus artikel ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada artikel</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer" style="background-color: #F0F0EB; border-top: 1px solid #E8E8E3;">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection