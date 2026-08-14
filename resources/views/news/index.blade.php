@extends('layout')

@section('title', 'Noticias')

@section('extra-styles')
<style>
    .news-list-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
    .news-card { padding: 28px; }
    .news-card-inner { display: flex; gap: 18px; }
    .news-thumb { flex-shrink: 0; width: 92px; height: 92px; border-radius: var(--radius-sm); overflow: hidden; background: var(--bg); }
    .news-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .news-body { min-width: 0; }
    .news-card .news-meta {
        font-size: .82rem;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .news-card h3 { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1.4rem; margin-bottom: 12px; letter-spacing: 0; }
    .news-card p { color: var(--ink-soft); font-size: .93rem; margin-bottom: 16px; }
    .news-card .read-more { font-weight: 700; font-size: .85rem; display: inline-flex; align-items: center; gap: 6px; }
    .news-card .read-more svg { transition: transform .25s ease; }
    .news-card:hover .read-more svg { transform: translateX(4px); }
    @media (max-width: 760px) { .news-list-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="section-head reveal">
    <span class="kicker">Al día</span>
    <h2>Noticias</h2>
</div>

<div class="news-list-grid">
    @forelse($newsItems as $item)
        <article class="card card-hover news-card reveal">
            <div class="news-card-inner">
                @if($item->featured_image)
                    <div class="news-thumb">
                        <img src="{{ asset('storage/' . $item->featured_image) }}" alt="">
                    </div>
                @endif
                <div class="news-body">
                    <div class="news-meta">{{ $item->published_at->format('d/m/Y') }}</div>
                    <h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3>
                    <p>{{ Str::limit($item->content, 220) }}</p>
                    <a href="{{ route('news.show', $item) }}" class="read-more">
                        Leer más
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
            </div>
        </article>
    @empty
        <p class="empty-note" style="grid-column: 1 / -1; text-align:center; padding: 60px 0; color: var(--ink-faint);">Sin noticias que mostrar. ¡Vuelve pronto!</p>
    @endforelse
</div>

<div class="pagination-wrap">
    {{ $newsItems->links() }}
</div>
@endsection
