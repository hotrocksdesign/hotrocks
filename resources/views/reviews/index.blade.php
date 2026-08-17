@extends('layout')

@section('title', 'Reseñas de Shows')

@section('extra-styles')
<style>
    .search-bar {
        margin-bottom: 48px;
        padding: 22px 26px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        display: flex;
        gap: 14px;
    }
    .search-bar input { flex-grow: 1; }
    .reviews-list-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
    .review-card {
        padding: 28px;
    }
    .review-card-inner { display: flex; gap: 18px; }
    .review-thumb { flex-shrink: 0; width: 92px; height: 92px; border-radius: var(--radius-sm); overflow: hidden; background: var(--bg); }
    .review-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .review-body { min-width: 0; }
    .review-card .review-meta {
        font-size: .82rem;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .review-card h3 { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1.4rem; margin-bottom: 12px; letter-spacing: 0; }
    .review-card p { color: var(--ink-soft); font-size: .93rem; margin-bottom: 16px; }
    .review-card .read-more { font-weight: 700; font-size: .85rem; display: inline-flex; align-items: center; gap: 6px; }
    .review-card .read-more svg { transition: transform .25s ease; }
    .review-card:hover .read-more svg { transform: translateX(4px); }
    @media (max-width: 760px) { .reviews-list-grid { grid-template-columns: 1fr; } .search-bar { flex-direction: column; } }
</style>
@endsection

@section('content')
<div class="section-head reveal">
    <span class="kicker">Archivo</span>
    <h2>Reseñas de Shows</h2>
</div>

<form action="{{ route('reviews.index') }}" method="GET" class="search-bar reveal">
    <input type="text" name="search" placeholder="Buscar por título, banda o contenido..." value="{{ request('search') }}">
    <button type="submit" class="btn btn-accent">Buscar</button>
</form>

<div class="reviews-list-grid">
    @forelse($reviews as $review)
        @php $thumbPath = $review->photos->first()?->photo_url ?? $review->featured_image; @endphp
        <article class="card card-hover review-card reveal">
            <div class="review-card-inner">
                @if($thumbPath)
                    <a href="{{ asset('storage/' . $thumbPath) }}" class="review-thumb" data-lightbox="{{ asset('storage/' . $thumbPath) }}" data-lightbox-alt="{{ $review->title }}">
                        <img src="{{ asset('storage/' . $thumbPath) }}" alt="">
                    </a>
                @endif
                <div class="review-body">
                    <div class="review-meta">{{ $review->band?->name ?? 'Lineup variado' }} · {{ $review->show_date->format('d/m/Y') }} · {{ $review->venue }}</div>
                    <h3><a href="{{ route('reviews.show', $review) }}">{{ $review->title }}</a></h3>
                    <p>{{ Str::limit($review->content, 220) }}</p>
                    <a href="{{ route('reviews.show', $review) }}" class="read-more">
                        Leer reseña completa
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
            </div>
        </article>
    @empty
        <p class="empty-note" style="grid-column: 1 / -1; text-align:center; padding: 60px 0; color: var(--ink-faint);">Sin reseñas que mostrar. ¡Vuelve pronto!</p>
    @endforelse
</div>

<div class="pagination-wrap">
    {{ $reviews->links() }}
</div>
@endsection
