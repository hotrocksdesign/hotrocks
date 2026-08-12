@extends('layout')

@section('title', 'Inicio')

@section('extra-styles')
<style>
    .hero {
        display: grid;
        grid-template-columns: 1.15fr .85fr;
        gap: 56px;
        align-items: stretch;
        padding: 20px 0 64px;
    }
    .hero h1 {
        font-size: clamp(2.6rem, 6vw, 4.8rem);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .hero h1 em { font-style: normal; color: var(--accent); }
    .hero-meta { margin-top: 14px; font-weight: 700; font-size: .92rem; color: var(--accent); }
    .hero p.lead { margin: 22px 0 30px; font-size: 1.1rem; color: var(--ink-soft); max-width: 46ch; }
    .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }
    .hero-visual {
        height: 100%;
        min-height: 340px;
        border-radius: var(--radius-lg);
        background:
            url('{{ asset('images/logo-full.jpg') }}') center / cover no-repeat,
            #0A0A0A;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        border-top: 1px solid var(--line);
        border-bottom: 1px solid var(--line);
        margin-bottom: 64px;
    }
    .stat { text-align: center; padding: 28px 12px; border-right: 1px solid var(--line); }
    .stat:last-child { border-right: none; }
    .stat .num { font-family: 'Bebas Neue', sans-serif; font-size: 3rem; color: var(--accent); line-height: 1; }
    .stat .label { margin-top: 6px; font-size: .78rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--ink-soft); font-weight: 700; }

    .reviews-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; margin-bottom: 70px; }
    .review-card { padding: 26px; display: flex; flex-direction: column; height: 100%; }
    .review-card .review-meta { display: flex; align-items: center; gap: 8px; font-size: .82rem; font-weight: 700; color: var(--accent); margin-bottom: 12px; }
    .review-card h3 { font-size: 1.5rem; text-transform: none; letter-spacing: 0; margin-bottom: 12px; font-family: 'Inter', sans-serif; font-weight: 800; }
    .review-card p { color: var(--ink-soft); font-size: .92rem; flex-grow: 1; }
    .review-card .read-more { margin-top: 18px; font-weight: 700; font-size: .85rem; display: inline-flex; align-items: center; gap: 6px; color: var(--ink); }
    .review-card .read-more svg { transition: transform .25s ease; }
    .review-card:hover .read-more svg { transform: translateX(4px); }

    .shows-panel {
        background: var(--ink);
        color: #F7F4EE;
        border-radius: var(--radius-lg);
        padding: 44px;
        overflow: hidden;
        position: relative;
    }
    .shows-panel .kicker { color: var(--accent); }
    .shows-panel .kicker::before { background: var(--accent); }
    .shows-panel h2 { color: #F7F4EE; text-transform: uppercase; font-size: clamp(1.8rem, 3.5vw, 2.6rem); margin-bottom: 26px; }
    .show-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 18px 0;
        border-bottom: 1px solid rgba(255,255,255,.12);
        flex-wrap: wrap;
    }
    .show-row:last-child { border-bottom: none; }
    .show-row .date { font-weight: 800; color: var(--accent); min-width: 90px; font-size: .9rem; }
    .show-row .who { font-weight: 700; font-size: 1.05rem; }
    .show-row .where { color: #A7A296; font-size: .85rem; }
    .empty-note { color: var(--ink-faint); padding: 30px 0; }

    @media (max-width: 900px) {
        .hero { grid-template-columns: 1fr; }
        .hero-visual { height: auto; min-height: 0; aspect-ratio: 16/9; order: -1; }
        .reviews-grid { grid-template-columns: 1fr 1fr; }
        .stats-row { grid-template-columns: 1fr; }
        .stat { border-right: none; border-bottom: 1px solid var(--line); }
        .stat:last-child { border-bottom: none; }
    }
    @media (max-width: 600px) {
        .reviews-grid { grid-template-columns: 1fr; }
        .shows-panel { padding: 28px; }
    }
</style>
@endsection

@section('content')
<section class="hero reveal">
    @if($featuredReview)
        <div>
            <span class="kicker">Reseña destacada</span>
            <h1>{{ $featuredReview->title }}</h1>
            <p class="hero-meta">{{ $featuredReview->band?->name ?? 'Lineup variado' }} · {{ $featuredReview->show_date->format('d/m/Y') }} · {{ $featuredReview->venue }}</p>
            <p class="lead">{{ Str::limit($featuredReview->content, 180) }}</p>
            <div class="hero-actions">
                <a href="{{ route('reviews.show', $featuredReview) }}" class="btn btn-accent">Leer reseña completa</a>
                <a href="{{ route('reviews.index') }}" class="btn btn-outline">Ver todas las reseñas</a>
            </div>
        </div>
        <div class="hero-visual" role="img" aria-label="{{ $featuredReview->title }}"
             @if($featuredReview->featured_image) style="background-image: url('{{ asset('storage/' . $featuredReview->featured_image) }}')" @endif></div>
    @else
        <div>
            <span class="kicker">Cobertura en vivo</span>
            <h1>La escena en <em>primera fila</em></h1>
            <p class="lead">Reseñas honestas, agenda actualizada y las bandas que están rompiéndola en cada show. Sin filtros, sin auspicios.</p>
            <div class="hero-actions">
                <a href="{{ route('reviews.index') }}" class="btn btn-accent">Ver reseñas</a>
                <a href="{{ route('agenda.index') }}" class="btn btn-outline">Explorar agenda</a>
            </div>
        </div>
        <div class="hero-visual" role="img" aria-label="Hot Rocks Shows"></div>
    @endif
</section>

<div class="stats-row reveal">
    <div class="stat"><div class="num"><span data-count-to="{{ $reviewsCount }}">0</span></div><div class="label">Reseñas</div></div>
    <div class="stat"><div class="num"><span data-count-to="{{ $bandsCount }}">0</span></div><div class="label">Bandas</div></div>
    <div class="stat"><div class="num"><span data-count-to="{{ $showsCount }}">0</span></div><div class="label">Shows</div></div>
</div>

<div class="section-head reveal">
    <span class="kicker">Recién publicadas</span>
    <h2>Últimas Reseñas</h2>
</div>

<div class="reviews-grid">
    @forelse($latestReviews as $review)
        <article class="card card-hover review-card reveal">
            <div class="review-meta">{{ $review->band?->name ?? 'Lineup variado' }} · {{ $review->show_date->format('d/m/Y') }}</div>
            <h3><a href="{{ route('reviews.show', $review) }}">{{ $review->title }}</a></h3>
            <p>{{ Str::limit($review->content, 130) }}</p>
            <a href="{{ route('reviews.show', $review) }}" class="read-more">
                Leer reseña
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </article>
    @empty
        <p class="empty-note">Sin reseñas aún. ¡Pronto habrá contenido!</p>
    @endforelse
</div>

<section class="shows-panel reveal">
    <span class="kicker">No te los pierdas</span>
    <h2>Próximos Shows</h2>
    @forelse($upcomingShows as $show)
        <div class="show-row">
            <span class="date">{{ $show->date->format('d/m/Y') }}</span>
            <span class="who">{{ $show->bands->pluck('name')->join(', ') }}</span>
            <span class="where">{{ $show->venue }} · {{ $show->city }}</span>
        </div>
    @empty
        <p class="empty-note">Sin shows confirmados en la agenda.</p>
    @endforelse
</section>
@endsection
