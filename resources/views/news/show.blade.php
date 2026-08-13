@extends('layout')

@section('title', $news->title)

@section('extra-styles')
<style>
    .back-link { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 28px; font-weight: 700; font-size: .88rem; color: var(--ink-soft); }
    .back-link:hover { color: var(--accent); }
    .news-header { margin-bottom: 40px; max-width: 760px; }
    .news-header h1 { font-size: clamp(2.2rem, 5vw, 3.4rem); text-transform: uppercase; margin-bottom: 14px; }
    .news-header .news-meta { font-weight: 700; font-size: .9rem; color: var(--ink-soft); }
    .news-image { border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md); margin-bottom: 44px; }
    .news-image img { width: 100%; max-height: 460px; object-fit: cover; display: block; }
    .news-content { line-height: 1.9; font-size: 1.08rem; margin: 44px 0; max-width: 760px; color: #26241E; }
    .news-content p { margin-bottom: 1.2em; }
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

@if($news->featured_image)
    <div class="news-image reveal">
        <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}">
    </div>
@endif

<div class="news-content reveal">
    {!! nl2br(e($news->content)) !!}
</div>

<div class="share-panel reveal">
    <strong>Compartir:</strong>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-sm btn-ghost">Facebook</a>
    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($news->title) }}" target="_blank" class="btn btn-sm btn-ghost">Twitter</a>
    <a href="https://www.instagram.com/" target="_blank" class="btn btn-sm btn-ghost">Instagram</a>
</div>
@endsection
