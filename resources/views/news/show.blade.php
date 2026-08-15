@extends('layout')

@section('title', $news->title)

@section('extra-styles')
<style>
    .back-link { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 28px; font-weight: 700; font-size: .88rem; color: var(--ink-soft); }
    .back-link:hover { color: var(--accent); }
    .news-header { margin-bottom: 40px; max-width: 920px; }
    .news-header h1 { font-size: clamp(2.2rem, 5vw, 3.4rem); text-transform: uppercase; margin-bottom: 14px; }
    .news-header .news-meta { font-weight: 700; font-size: .9rem; color: var(--ink-soft); }

    /* Text on the left, the full (uncropped) photo scaled down to a small
       column on the right — click it to see it at full size. */
    .news-layout { display: grid; grid-template-columns: 1fr 260px; align-items: start; gap: 40px; margin: 12px 0 44px; }
    .news-content { line-height: 1.9; font-size: 1.08rem; color: #26241E; }
    .news-content p { margin-bottom: 1.2em; }
    .news-image-thumb { display: block; border-radius: var(--radius); overflow: hidden; border: 1px solid var(--line); box-shadow: var(--shadow-sm); }
    .news-image-thumb img { width: 100%; height: auto; display: block; }

    @media (max-width: 760px) {
        .news-layout { grid-template-columns: 1fr; }
        .news-image-thumb { max-width: 260px; margin: 0 auto; }
    }
    .share-panel { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; padding: 22px 26px; background: var(--ink); border-radius: var(--radius); color: #F7F4EE; }
    .share-panel strong { font-size: .85rem; letter-spacing: .5px; }
    .share-panel .btn-ghost { background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.18); color: #F7F4EE; }
    .share-panel .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }
</style>
@endsection

@section('content')
<a href="{{ route('news.index') }}" class="back-link reveal">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Volver a noticias
</a>

<article class="news-header reveal">
    <span class="kicker">Noticia</span>
    <h1>{{ $news->title }}</h1>
    <div class="news-meta">{{ $news->published_at->format('d/m/Y') }} · {{ $news->user->name }}</div>
</article>

<div class="news-layout">
    <div class="news-content reveal">
        {!! nl2br(e($news->content)) !!}
    </div>

    @if($news->featured_image)
        <a href="{{ asset('storage/' . $news->featured_image) }}" class="news-image-thumb reveal" data-lightbox="{{ asset('storage/' . $news->featured_image) }}" data-lightbox-alt="{{ $news->title }}">
            <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}">
        </a>
    @endif
</div>

<div class="share-panel reveal">
    <strong>Compartir:</strong>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-sm btn-ghost">Facebook</a>
    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($news->title) }}" target="_blank" class="btn btn-sm btn-ghost">Twitter</a>
    <a href="https://www.instagram.com/" target="_blank" class="btn btn-sm btn-ghost">Instagram</a>
</div>
@endsection
