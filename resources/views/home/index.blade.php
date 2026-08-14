@extends('layout')

@section('title', 'Inicio')

@section('extra-styles')
<style>
    .hero {
        display: grid;
        grid-template-columns: 1.15fr .85fr;
        gap: 40px;
        align-items: stretch;
        padding: 20px 0 40px;
    }
    .hero h1 {
        font-size: clamp(2rem, 4.2vw, 3.2rem);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .hero h1 em { font-style: normal; color: var(--accent); }
    .hero-meta { margin-top: 12px; font-weight: 700; font-size: .88rem; color: var(--accent); }
    .hero p.lead { margin: 16px 0 22px; font-size: 1rem; color: var(--ink-soft); max-width: 46ch; }
    .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }
    .hero-visual {
        height: 100%;
        min-height: 220px;
        border-radius: var(--radius-lg);
        background:
            url('{{ asset('images/logo-full.jpg') }}') center / cover no-repeat,
            #0A0A0A;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    /* ---------- Newspaper-style two-column section ---------- */
    .newspaper-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; margin-bottom: 70px; }
    .newspaper-col { padding: 0 44px; }
    .newspaper-col:first-child { padding-left: 0; }
    .newspaper-col:last-child { padding-right: 0; border-left: 1px solid var(--line); }
    .editorial-item { padding: 22px 0; border-bottom: 1px solid var(--line); }
    .editorial-item:first-child { padding-top: 0; }
    .editorial-item:last-child { border-bottom: none; }
    .editorial-item .editorial-meta { font-size: .78rem; font-weight: 700; color: var(--accent); margin-bottom: 8px; text-transform: uppercase; letter-spacing: .5px; }
    .editorial-item h3 { font-size: 1.2rem; text-transform: none; letter-spacing: 0; margin-bottom: 8px; font-family: 'Inter', sans-serif; font-weight: 800; line-height: 1.25; }
    .editorial-item p { color: var(--ink-soft); font-size: .88rem; margin-bottom: 10px; }
    .editorial-item .read-more { font-weight: 700; font-size: .82rem; display: inline-flex; align-items: center; gap: 6px; color: var(--ink); }
    .editorial-item .read-more svg { transition: transform .25s ease; }
    .editorial-item:hover .read-more svg { transform: translateX(4px); }

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
        .newspaper-grid { grid-template-columns: 1fr; gap: 8px; }
        .newspaper-col { padding: 0; }
        .newspaper-col:last-child { border-left: none; border-top: 1px solid var(--line); padding-top: 20px; margin-top: 12px; }
    }
    @media (max-width: 600px) {
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

<div class="newspaper-grid">
    <div class="newspaper-col">
        <div class="section-head reveal">
            <span class="kicker">Recién publicadas</span>
            <h2>Últimas Reseñas</h2>
        </div>

        @forelse($latestReviews as $review)
            <article class="editorial-item reveal">
                <div class="editorial-meta">{{ $review->band?->name ?? 'Lineup variado' }} · {{ $review->show_date->format('d/m/Y') }}</div>
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

    <div class="newspaper-col">
        <div class="section-head reveal">
            <span class="kicker">Al día</span>
            <h2>Noticias</h2>
        </div>

        @forelse($latestNews as $item)
            <article class="editorial-item reveal">
                <div class="editorial-meta">{{ $item->published_at->format('d/m/Y') }}</div>
                <h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3>
                <p>{{ Str::limit($item->content, 120) }}</p>
                <a href="{{ route('news.show', $item) }}" class="read-more">
                    Leer más
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </article>
        @empty
            <p class="empty-note">Sin noticias aún.</p>
        @endforelse
    </div>
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
