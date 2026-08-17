@extends('layout')

@section('title', 'Inicio')

@section('extra-styles')
<style>
    /* Card wrapper: same full-bleed width as the newspaper section and
       Próximos Shows below, instead of the hero sitting narrower than
       everything else on the page. */
    .hero {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 420px;
        grid-template-areas: "intro visual" "details visual";
        gap: 12px 40px;
        align-items: start;
        padding: 40px 44px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        margin-bottom: 50px;
        position: relative;
    }
    /* Cap line length for readability even though the column itself now
       stretches to fill the card. */
    .hero-intro, .hero-details { max-width: 560px; }
    .hero-intro { grid-area: intro; }
    .hero-details { grid-area: details; }
    .hero h1 {
        font-size: clamp(2rem, 4.2vw, 3.2rem);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .hero h1 em { font-style: normal; color: var(--accent); }
    .hero-meta { margin-top: 12px; font-weight: 700; font-size: .88rem; color: var(--accent); }
    .hero p.lead { margin: 16px 0 22px; font-size: 1rem; color: var(--ink-soft); max-width: 46ch; }
    .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }
    /* Real uploaded image: the box just wraps the <img>, so its height
       follows the image's own aspect ratio instead of being stretched
       or cropped to match the text column. */
    .hero-visual {
        grid-area: visual;
        border-radius: var(--radius-lg);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }
    /* Absolutely positioned (inset to the "visual" grid area, since .hero
       is position:relative) instead of align-self:stretch + height:100% —
       a percentage height inside an auto-sized grid row is indeterminate,
       so a tall image would blow up the row instead of being capped by
       it. Insets give it a hard, definite height: exactly from just below
       the "Reseña destacada" kicker down to the bottom of the buttons row,
       never taller than that span no matter how tall the source image is. */
    .hero-visual:not(.hero-visual-fallback) {
        position: absolute;
        top: 32px;
        right: 0;
        bottom: 0;
        left: 0;
    }
    .hero-visual:not(.hero-visual-fallback) img { width: 100%; height: 100%; object-fit: cover; object-position: top; display: block; }
    .hero-photo-credit {
        position: absolute;
        left: 10px;
        bottom: 10px;
        padding: 3px 8px;
        background: rgba(0,0,0,.55);
        color: #F7F4EE;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .2px;
        border-radius: 4px;
    }
    /* Carousel controls: arrows overlaid on the photo, dots in the text
       column so they never sit on top of the image. */
    .hero-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(0,0,0,.5);
        border: none;
        color: #F7F4EE;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        line-height: 1;
        cursor: pointer;
        z-index: 2;
        transition: background .2s ease;
    }
    .hero-arrow:hover { background: rgba(0,0,0,.75); }
    .hero-arrow-prev { left: 10px; }
    .hero-arrow-next { right: 10px; }
    .hero-dots { display: flex; gap: 8px; margin-top: 18px; }
    .hero-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--line); border: none; padding: 0; cursor: pointer; transition: background .2s ease, transform .2s ease; }
    .hero-dot.is-active { background: var(--accent); transform: scale(1.3); }
    /* Fallback (no featured review / no image uploaded): no image to size
       from, so keep a fixed decorative box like before. */
    .hero-visual.hero-visual-fallback {
        min-height: 220px;
        background:
            url('{{ asset('images/logo-full.jpg') }}') center / cover no-repeat,
            #0A0A0A;
    }

    /* ---------- Newspaper-style two-column section ---------- */
    .newspaper-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; margin-bottom: 70px; }
    .newspaper-col { padding: 0 44px; }
    .newspaper-col:first-child { padding-left: 0; }
    .newspaper-col:last-child { padding-right: 0; border-left: 1px solid var(--line); }
    .editorial-item { padding: 22px 0; border-bottom: 1px solid var(--line); }
    .editorial-item:first-child { padding-top: 0; }
    .editorial-item:last-child { border-bottom: none; }
    .editorial-item-inner { display: flex; gap: 16px; }
    .editorial-thumb { flex-shrink: 0; width: 84px; height: 84px; border-radius: var(--radius-sm); overflow: hidden; background: var(--bg); }
    .editorial-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .editorial-body { min-width: 0; }
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
    /* Grid (not flex) so the date/band/venue columns line up across every
       row regardless of how long any single row's venue or city text is. */
    .shows-list { display: grid; grid-template-columns: auto 1fr auto auto; align-items: center; column-gap: 24px; }
    .show-row { display: contents; }
    .show-row > * { padding-top: 18px; padding-bottom: 18px; border-bottom: 1px solid rgba(255,255,255,.12); }
    .show-row:last-child > * { border-bottom: none; }
    .show-row .date { font-weight: 800; color: var(--accent); font-size: .9rem; white-space: nowrap; }
    .show-row .who { font-weight: 700; font-size: 1.05rem; }
    .show-row .where { color: #A7A296; font-size: .85rem; text-align: right; }
    .show-row .btn { white-space: nowrap; }
    .empty-note { color: var(--ink-faint); padding: 30px 0; }

    .band-cta {
        margin-top: 40px;
        padding: 40px 44px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
        flex-wrap: wrap;
    }
    .band-cta h2 { font-size: clamp(1.6rem, 3vw, 2.2rem); text-transform: uppercase; margin-bottom: 8px; }
    .band-cta p { color: var(--ink-soft); max-width: 46ch; }

    @media (max-width: 900px) {
        .hero {
            grid-template-columns: 1fr;
            grid-template-areas: "intro" "visual" "details";
        }
        .hero-visual.hero-visual-fallback { height: auto; min-height: 0; aspect-ratio: 16/9; }
        /* Real uploaded image (not the generic fallback banner): the
           desktop "stretch to fill the card height" trick doesn't apply
           here since visual is its own row now — go back to a plain
           width-driven small image, centered, right after the title. */
        .hero-visual:not(.hero-visual-fallback) {
            /* relative, not static: stays in normal flow same as static
               would, but keeps this box as the positioning root for
               .hero-photo-credit — otherwise that label escapes up to
               .hero and lands wherever, instead of pinned to the photo.
               top/right/bottom/left must be reset too: the desktop rule's
               top:32px/bottom:0 offsets are ignored under position:static,
               but position:relative honors them, which shifted the whole
               photo (and its credit label) 32px down over the text below. */
            position: relative;
            top: auto;
            right: auto;
            bottom: auto;
            left: auto;
            max-width: 260px;
            margin: 0 auto;
        }
        .hero-visual:not(.hero-visual-fallback) img { width: 100%; height: auto; max-width: none; object-fit: initial; }
        .newspaper-grid { grid-template-columns: 1fr; gap: 8px; }
        .newspaper-col { padding: 0; }
        .newspaper-col:last-child { border-left: none; border-top: 1px solid var(--line); padding-top: 20px; margin-top: 12px; }
    }
    @media (max-width: 640px) {
        .shows-list { grid-template-columns: 1fr; }
        .show-row { display: block; padding: 16px 0; border-bottom: 1px solid rgba(255,255,255,.12); }
        .show-row:last-child { border-bottom: none; }
        .show-row > * { display: block; padding-top: 2px; padding-bottom: 2px; border-bottom: none; white-space: normal; }
        .show-row .btn { display: inline-flex; margin-top: 10px; }
        .show-row .where { text-align: left; }
    }
    @media (max-width: 600px) {
        .hero { padding: 28px; }
        .shows-panel { padding: 28px; }
    }
</style>
@endsection

@section('content')
@php
    // Preferimos una foto real del show (galería) antes que la imagen del
    // campo "Imagen del show" (que muchas veces termina siendo un flyer
    // promocional en vez de una foto del show en sí).
    $heroSlides = $featuredReviews->map(function ($review) {
        $imagePath = $review->photos->first()?->photo_url ?? $review->featured_image;

        return [
            'title' => $review->title,
            'url' => route('reviews.show', $review),
            'image' => $imagePath ? asset('storage/' . $imagePath) : null,
            'credit' => $review->photo_credit,
            'meta' => ($review->band?->name ?? 'Lineup variado') . ' · ' . $review->show_date->format('d/m/Y') . ' · ' . $review->venue,
            'lead' => Str::limit($review->content, 180),
        ];
    })->values();
    $firstSlide = $heroSlides->first();
@endphp
<section class="hero reveal" id="heroCarousel">
    @if($firstSlide)
        <div class="hero-intro">
            <span class="kicker">Reseña destacada</span>
            <h1 id="heroTitle">{{ $firstSlide['title'] }}</h1>
        </div>

        <div class="hero-visual {{ $firstSlide['image'] ? '' : 'hero-visual-fallback' }}" id="heroVisual" role="img" aria-label="{{ $firstSlide['title'] }}">
            <img src="{{ $firstSlide['image'] }}" alt="{{ $firstSlide['title'] }}" id="heroImg" style="{{ $firstSlide['image'] ? '' : 'display:none;' }}">
            <span class="hero-photo-credit" id="heroCredit" style="{{ $firstSlide['credit'] ? '' : 'display:none;' }}">{{ $firstSlide['credit'] }}</span>
            @if($heroSlides->count() > 1)
                <button type="button" class="hero-arrow hero-arrow-prev" id="heroPrev" aria-label="Reseña anterior">‹</button>
                <button type="button" class="hero-arrow hero-arrow-next" id="heroNext" aria-label="Reseña siguiente">›</button>
            @endif
        </div>

        <div class="hero-details">
            <p class="hero-meta" id="heroMeta">{{ $firstSlide['meta'] }}</p>
            <p class="lead" id="heroLead">{{ $firstSlide['lead'] }}</p>
            <div class="hero-actions">
                <a href="{{ $firstSlide['url'] }}" class="btn btn-accent" id="heroCta">Leer reseña completa</a>
                <a href="{{ route('reviews.index') }}" class="btn btn-outline">Ver todas las reseñas</a>
            </div>
            @if($heroSlides->count() > 1)
                <div class="hero-dots" id="heroDots"></div>
            @endif
        </div>
    @else
        <div class="hero-intro">
            <span class="kicker">Cobertura en vivo</span>
            <h1>La escena en <em>primera fila</em></h1>
        </div>
        <div class="hero-visual hero-visual-fallback" role="img" aria-label="Hot Rocks Shows"></div>
        <div class="hero-details">
            <p class="lead">Reseñas honestas, agenda actualizada y las bandas que están rompiéndola en cada show. Sin filtros, sin auspicios.</p>
            <div class="hero-actions">
                <a href="{{ route('reviews.index') }}" class="btn btn-accent">Ver reseñas</a>
                <a href="{{ route('agenda.index') }}" class="btn btn-outline">Explorar agenda</a>
            </div>
        </div>
    @endif
</section>

@if($heroSlides->count() > 1)
    <script>
        (function () {
            var slides = @json($heroSlides, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            var index = 0;
            var carousel = document.getElementById('heroCarousel');
            var titleEl = document.getElementById('heroTitle');
            var visualEl = document.getElementById('heroVisual');
            var imgEl = document.getElementById('heroImg');
            var creditEl = document.getElementById('heroCredit');
            var metaEl = document.getElementById('heroMeta');
            var leadEl = document.getElementById('heroLead');
            var ctaEl = document.getElementById('heroCta');
            var dotsWrap = document.getElementById('heroDots');
            var prevBtn = document.getElementById('heroPrev');
            var nextBtn = document.getElementById('heroNext');
            var timer = null;

            var dots = slides.map(function (_, i) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'hero-dot' + (i === 0 ? ' is-active' : '');
                dot.setAttribute('aria-label', 'Ir a reseña destacada ' + (i + 1));
                dot.addEventListener('click', function () { goTo(i); });
                dotsWrap.appendChild(dot);
                return dot;
            });

            function render() {
                var s = slides[index];
                titleEl.textContent = s.title;
                metaEl.textContent = s.meta;
                leadEl.textContent = s.lead;
                ctaEl.href = s.url;
                visualEl.setAttribute('aria-label', s.title);

                if (s.image) {
                    imgEl.src = s.image;
                    imgEl.alt = s.title;
                    imgEl.style.display = '';
                    visualEl.classList.remove('hero-visual-fallback');
                } else {
                    imgEl.style.display = 'none';
                    visualEl.classList.add('hero-visual-fallback');
                }

                if (s.credit) {
                    creditEl.textContent = s.credit;
                    creditEl.style.display = '';
                } else {
                    creditEl.style.display = 'none';
                }

                dots.forEach(function (d, i) { d.classList.toggle('is-active', i === index); });
            }

            function goTo(i) {
                index = (i + slides.length) % slides.length;
                render();
                resetTimer();
            }

            function resetTimer() {
                if (timer) clearInterval(timer);
                timer = setInterval(function () { goTo(index + 1); }, 7000);
            }

            prevBtn.addEventListener('click', function () { goTo(index - 1); });
            nextBtn.addEventListener('click', function () { goTo(index + 1); });
            carousel.addEventListener('mouseenter', function () { if (timer) clearInterval(timer); });
            carousel.addEventListener('mouseleave', resetTimer);

            resetTimer();
        })();
    </script>
@endif

<div class="newspaper-grid">
    <div class="newspaper-col">
        <div class="section-head reveal">
            <span class="kicker">Recién publicadas</span>
            <h2>Últimas Reseñas</h2>
        </div>

        @forelse($latestReviews as $review)
            @php $thumbPath = $review->photos->first()?->photo_url ?? $review->featured_image; @endphp
            <article class="editorial-item reveal">
                <div class="editorial-item-inner">
                    @if($thumbPath)
                        <a href="{{ asset('storage/' . $thumbPath) }}" class="editorial-thumb" data-lightbox="{{ asset('storage/' . $thumbPath) }}" data-lightbox-alt="{{ $review->title }}">
                            <img src="{{ asset('storage/' . $thumbPath) }}" alt="">
                        </a>
                    @endif
                    <div class="editorial-body">
                        <div class="editorial-meta">{{ $review->band?->name ?? 'Lineup variado' }} · {{ $review->show_date->format('d/m/Y') }}</div>
                        <h3><a href="{{ route('reviews.show', $review) }}">{{ $review->title }}</a></h3>
                        <p>{{ Str::limit($review->content, 130) }}</p>
                        <a href="{{ route('reviews.show', $review) }}" class="read-more">
                            Leer reseña
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </div>
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
                <div class="editorial-item-inner">
                    @if($item->featured_image)
                        <a href="{{ asset('storage/' . $item->featured_image) }}" class="editorial-thumb" data-lightbox="{{ asset('storage/' . $item->featured_image) }}" data-lightbox-alt="{{ $item->title }}">
                            <img src="{{ asset('storage/' . $item->featured_image) }}" alt="">
                        </a>
                    @endif
                    <div class="editorial-body">
                        <div class="editorial-meta">{{ $item->published_at->format('d/m/Y') }}</div>
                        <h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3>
                        <p>{{ Str::limit($item->content, 120) }}</p>
                        <a href="{{ route('news.show', $item) }}" class="read-more">
                            Leer más
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <p class="empty-note">Sin noticias aún.</p>
        @endforelse
    </div>
</div>

<section class="shows-panel reveal">
    <span class="kicker">No te los pierdas</span>
    <h2>Próximos Shows</h2>
    <div class="shows-list">
        @forelse($upcomingShows as $show)
            <div class="show-row">
                <span class="date">{{ $show->date->format('d/m/Y') }}</span>
                <span class="who">{{ $show->bands->pluck('name')->join(', ') }}</span>
                <span class="where">{{ $show->venue }} · {{ $show->city }}</span>
                @if($show->ticket_url)
                    <a href="{{ $show->ticket_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-accent">Entradas</a>
                @else
                    <span></span>
                @endif
            </div>
        @empty
            <p class="empty-note">Sin shows confirmados en la agenda.</p>
        @endforelse
    </div>
</section>

@if(!auth()->user()?->isBand())
    <section class="band-cta reveal">
        <div>
            <span class="kicker">¿Tenés una banda?</span>
            <h2>Sumá tu banda a Hot Rocks</h2>
            <p>Creá tu ficha con logo, fotos y redes. No hace falta cuenta — un admin la revisa y en cuanto se aprueba aparece en la enciclopedia.</p>
        </div>
        <a href="{{ route('bands.submit') }}" class="btn btn-accent">Sumar mi banda →</a>
    </section>
@endif
@endsection
