@extends('layout')

@section('title', $band->name)

@section('extra-styles')
<style>
    .back-link { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 28px; font-weight: 700; font-size: .88rem; color: var(--ink-soft); }
    .back-link:hover { color: var(--accent); }
    .band-header { display: grid; grid-template-columns: 320px 1fr; gap: 48px; align-items: start; margin-bottom: 64px; }
    .band-photo { display: block; width: 100%; aspect-ratio: 1; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); overflow: hidden; }
    .band-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .band-photo-empty { aspect-ratio: 1; border-radius: var(--radius-lg); background: var(--surface); border: 1px dashed var(--line); display: flex; align-items: center; justify-content: center; color: var(--ink-faint); }
    .band-info h1 { font-size: clamp(2.4rem, 5vw, 3.6rem); text-transform: uppercase; margin-bottom: 16px; }
    .band-bio { line-height: 1.8; margin: 22px 0 28px; color: var(--ink-soft); font-size: 1.02rem; max-width: 56ch; }
    .band-links { display: flex; gap: 12px; flex-wrap: wrap; }

    .section-title { display: flex; align-items: baseline; gap: 14px; margin: 64px 0 28px; }
    .section-title h2 { font-size: 1.9rem; text-transform: uppercase; }

    .review-card { padding: 22px 26px; margin-bottom: 16px; }
    .review-card h4 { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1.1rem; margin-bottom: 6px; letter-spacing: 0; }
    .review-card .meta { color: var(--accent); font-size: .82rem; font-weight: 700; margin-bottom: 10px; }
    .review-card p { color: var(--ink-soft); font-size: .9rem; margin-bottom: 10px; }

    .show-card { padding: 18px 24px; margin-bottom: 14px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .show-card .when { font-weight: 800; color: var(--accent); }
    .show-card .where { color: var(--ink-soft); font-size: .9rem; }

    @media (max-width: 760px) {
        .band-header { grid-template-columns: 1fr; }
    }

    .carousel { position: relative; margin-bottom: 20px; }
    .carousel-track {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        padding-bottom: 8px;
        -webkit-overflow-scrolling: touch;
    }
    .carousel-track::-webkit-scrollbar { height: 6px; }
    .carousel-track::-webkit-scrollbar-thumb { background: var(--line); border-radius: 3px; }
    .carousel-slide {
        flex: 0 0 auto;
        width: min(420px, 80%);
        scroll-snap-align: start;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--line);
        background: var(--surface);
        box-shadow: var(--shadow-sm);
    }
    .carousel-slide img { width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block; }
    .carousel-caption { padding: 10px 14px; font-size: .85rem; color: var(--ink-soft); }
    .carousel-arrow {
        position: absolute; top: 42%; transform: translateY(-50%);
        width: 44px; height: 44px; border-radius: 50%;
        background: var(--surface); border: 1px solid var(--line);
        box-shadow: var(--shadow-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; cursor: pointer; z-index: 2; color: var(--ink);
    }
    .carousel-arrow:hover { border-color: var(--accent); color: var(--accent); }
    .carousel-prev { left: -8px; }
    .carousel-next { right: -8px; }
    .carousel-dots { display: flex; justify-content: center; gap: 8px; margin-top: 14px; }
    .carousel-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--line); cursor: pointer; border: none; padding: 0; transition: background .2s ease, transform .2s ease; }
    .carousel-dot.is-active { background: var(--accent); transform: scale(1.3); }
    @media (max-width: 640px) {
        .carousel-arrow { display: none; }
        .carousel-slide { width: 82%; }
    }
</style>
@endsection

@section('content')
<a href="{{ route('bands.index') }}" class="back-link reveal">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Volver a bandas
</a>

<div class="band-header reveal">
    @if($band->photo_url)
        <a href="{{ asset('storage/' . $band->photo_url) }}" class="band-photo" data-lightbox="{{ asset('storage/' . $band->photo_url) }}" data-lightbox-alt="{{ $band->name }}">
            <img src="{{ asset('storage/' . $band->photo_url) }}" alt="{{ $band->name }}">
        </a>
    @else
        <div class="band-photo-empty">Sin foto</div>
    @endif

    <div class="band-info">
        <span class="kicker">Perfil de banda</span>
        <h1>{{ $band->name }}</h1>
        @if($band->genre)
            <span class="tag">{{ $band->genre }}</span>
        @endif
        @if($band->biography)
            <p class="band-bio">{{ $band->biography }}</p>
        @endif
        <div class="band-links">
            @if($band->instagram_url)<a href="{{ $band->instagram_url }}" target="_blank" class="btn btn-outline btn-sm">Instagram</a>@endif
            @if($band->spotify_url)<a href="{{ $band->spotify_url }}" target="_blank" class="btn btn-outline btn-sm">Spotify</a>@endif
            @if($band->youtube_url)<a href="{{ $band->youtube_url }}" target="_blank" class="btn btn-outline btn-sm">YouTube</a>@endif
        </div>
    </div>
</div>

@if($band->photos->count() > 0)
    <div class="section-title reveal"><span class="kicker" style="margin:0;">Galería</span><h2>Fotos</h2></div>
    <div class="carousel reveal" data-carousel>
        <button type="button" class="carousel-arrow carousel-prev" aria-label="Foto anterior">‹</button>
        <div class="carousel-track">
            @foreach($band->photos as $photo)
                <div class="carousel-slide">
                    <a href="{{ asset('storage/' . $photo->photo_url) }}" data-lightbox="{{ asset('storage/' . $photo->photo_url) }}" data-lightbox-alt="{{ $photo->caption ?? $band->name }}">
                        <img src="{{ asset('storage/' . $photo->photo_url) }}" alt="{{ $photo->caption ?? $band->name }}">
                    </a>
                    @if($photo->caption)
                        <p class="carousel-caption">{{ $photo->caption }}</p>
                    @endif
                </div>
            @endforeach
        </div>
        <button type="button" class="carousel-arrow carousel-next" aria-label="Foto siguiente">›</button>
        <div class="carousel-dots"></div>
    </div>
@endif

@if($reviews->count() > 0)
    <div class="section-title reveal"><span class="kicker" style="margin:0;">Cobertura</span><h2>Reseñas de Shows</h2></div>
    @foreach($reviews as $review)
        <article class="card review-card reveal">
            <div class="meta">{{ $review->show_date->format('d/m/Y') }} · {{ $review->venue }}</div>
            <h4><a href="{{ route('reviews.show', $review) }}">{{ $review->title }}</a></h4>
            <p>{{ Str::limit($review->content, 200) }}</p>
            <a href="{{ route('reviews.show', $review) }}" style="font-weight:700; font-size:.85rem;">Leer más →</a>
        </article>
    @endforeach
    <div class="pagination-wrap">{{ $reviews->links() }}</div>
@endif

@if($shows->count() > 0)
    <div class="section-title reveal"><span class="kicker" style="margin:0;">Agenda</span><h2>Próximos Shows</h2></div>
    @foreach($shows as $show)
        <div class="card show-card reveal">
            <div>
                <div class="when">{{ $show->date->format('d/m/Y \\a \\l\\a\\s H:i') }}</div>
                <div class="where">{{ $show->venue }} ({{ $show->city }})</div>
            </div>
            @if($show->ticket_url)
                <a href="{{ $show->ticket_url }}" target="_blank" class="btn btn-accent btn-sm">Entradas</a>
            @endif
        </div>
    @endforeach
    <div class="pagination-wrap">{{ $shows->links() }}</div>
@endif
@endsection

@section('extra-scripts')
<script>
    document.querySelectorAll('[data-carousel]').forEach(function (carousel) {
        var track = carousel.querySelector('.carousel-track');
        var slides = Array.from(track.children);
        var dotsWrap = carousel.querySelector('.carousel-dots');
        var prevBtn = carousel.querySelector('.carousel-prev');
        var nextBtn = carousel.querySelector('.carousel-next');

        slides.forEach(function (_, i) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'carousel-dot' + (i === 0 ? ' is-active' : '');
            dot.setAttribute('aria-label', 'Ir a foto ' + (i + 1));
            dot.addEventListener('click', function () {
                slides[i].scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' });
            });
            dotsWrap.appendChild(dot);
        });
        var dots = Array.from(dotsWrap.children);

        function scrollBySlide(dir) {
            var slideWidth = slides[0].getBoundingClientRect().width + 16;
            track.scrollBy({ left: dir * slideWidth, behavior: 'smooth' });
        }
        prevBtn.addEventListener('click', function () { scrollBySlide(-1); });
        nextBtn.addEventListener('click', function () { scrollBySlide(1); });

        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    var idx = slides.indexOf(entry.target);
                    if (entry.isIntersecting && idx > -1) {
                        dots.forEach(function (d) { d.classList.remove('is-active'); });
                        dots[idx].classList.add('is-active');
                    }
                });
            }, { root: track, threshold: 0.6 });
            slides.forEach(function (s) { io.observe(s); });
        }
    });
</script>
@endsection
