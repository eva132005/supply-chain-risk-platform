@extends('layouts.app')

@section('title', 'News Intelligence - Supply Chain Risk Intelligence')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 style="color: #4fc3f7;"><i class="bi bi-newspaper"></i> News Intelligence</h2>
            <p class="text-muted">Supply chain news with sentiment analysis</p>
        </div>
    </div>

    <!-- Sentiment Summary -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card p-3" style="border-left: 4px solid #66bb6a;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Positive News</p>
                        <h3 style="color: #66bb6a;">{{ $news->where('sentiment', 'Positive')->count() }}</h3>
                    </div>
                    <i class="bi bi-emoji-smile" style="font-size: 2rem; color: #66bb6a; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card p-3" style="border-left: 4px solid #9e9e9e;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Neutral News</p>
                        <h3 style="color: #9e9e9e;">{{ $news->where('sentiment', 'Neutral')->count() }}</h3>
                    </div>
                    <i class="bi bi-emoji-neutral" style="font-size: 2rem; color: #9e9e9e; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card p-3" style="border-left: 4px solid #ef5350;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Negative News</p>
                        <h3 style="color: #ef5350;">{{ $news->where('sentiment', 'Negative')->count() }}</h3>
                    </div>
                    <i class="bi bi-emoji-frown" style="font-size: 2rem; color: #ef5350; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="text" id="filterCountry" class="form-control" placeholder="Filter by country code (IDN, DEU...)"
                                style="background-color: #252836; border-color: #2a2d3e; color: #e0e0e0;">
                        </div>
                        <div class="col-md-4 mb-2">
                            <select id="filterSentiment" class="form-select"
                                style="background-color: #252836; border-color: #2a2d3e; color: #e0e0e0;">
                                <option value="">All Sentiments</option>
                                <option value="Positive">Positive</option>
                                <option value="Neutral">Neutral</option>
                                <option value="Negative">Negative</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button onclick="filterNews()" class="btn w-100" style="background-color: #4fc3f7; color: #0f1117;">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- News List -->
    <div class="row" id="newsContainer">
        @foreach($news as $article)
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-secondary">{{ $article->source }}</span>
                        <span class="badge {{ $article->sentiment == 'Positive' ? 'bg-success' : ($article->sentiment == 'Negative' ? 'bg-danger' : 'bg-secondary') }}">
                            {{ $article->sentiment }}
                        </span>
                    </div>
                    <h6 class="mb-2">
    <a href="{{ $article->url }}" target="_blank" style="color: #1E2D4C; text-decoration: none;">
                        </a>
                    </h6>
                    <p style="font-size: 0.85rem; color: #1E2D4C;">{{ Str::limit($article->description, 120) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-globe2"></i> {{ $article->country->name ?? 'Global' }}
                        </small>
                        <small class="text-muted">{{ $article->published_at?->diffForHumans() }}</small>
                    </div>
                    <div class="mt-2">
                        <small style="color: #66bb6a;">+{{ $article->positive_score }}</small>
                        <small class="mx-1 text-muted">/</small>
                        <small style="color: #ef5350;">-{{ $article->negative_score }}</small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="row mt-3">
        <div class="col-12">
            {{ $news->links() }}
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
function filterNews() {
    const country = document.getElementById('filterCountry').value;
    const sentiment = document.getElementById('filterSentiment').value;

    let url = '/api/news?';
    if (country) url += `country=${country}&`;
    if (sentiment) url += `sentiment=${sentiment}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.data.forEach(article => {
                const sentimentClass = article.sentiment == 'Positive' ? 'bg-success' : (article.sentiment == 'Negative' ? 'bg-danger' : 'bg-secondary');
                html += `
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-secondary">${article.source ?? 'N/A'}</span>
                                <span class="badge ${sentimentClass}">${article.sentiment}</span>
                            </div>
                            <h6 class="mb-2">
                                <a href="${article.url}" target="_blank" style="color: #e0e0e0; text-decoration: none;">
                                    ${article.title.substring(0, 100)}
                                </a>
                            </h6>
                            <div class="mt-2">
                                <small style="color: #66bb6a;">+${article.positive_score}</small>
                                <small class="mx-1 text-muted">/</small>
                                <small style="color: #ef5350;">-${article.negative_score}</small>
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            document.getElementById('newsContainer').innerHTML = html || '<div class="col-12 text-center text-muted">Tidak ada berita ditemukan</div>';
        });
}
</script>
@endpush