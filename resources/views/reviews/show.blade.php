@extends('layout')

@section('title', $review->title)

@section('extra-styles')
<style>
    .back-link { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 28px; font-weight: 700; font-size: .88rem; color: var(--ink-soft); }
    .back-link:hover { color: var(--accent); }
    .review-header { margin-bottom: 40px; max-width: 920px; }
    .review-header h1 { font-size: clamp(2.2rem, 5vw, 3.4rem); text-transform: uppercase; margin-bottom: 22px; }
    .meta-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 18px; padding: 22px 26px; background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); }
    .meta-grid .label { font-size: .72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--ink-faint); font-weight: 700; margin-bottom: 4px; }
    .meta-grid .value { font-weight: 700; }
    .meta-grid .value a:hover { color: var(--accent); }

    /* Content + photo gallery side by side on desktop: text on the left,
       small stacked thumbnails on the right that open the lightbox. */
    .review-layout { display: grid; grid-template-columns: 1fr 200px; align-items: start; gap: 40px; margin: 44px 0; }
    .review-content { line-height: 1.9; font-size: 1.08rem; color: #26241E; text-align: justify; }
    .review-content p { margin-bottom: 1.2em; }

    .review-gallery { display: flex; flex-direction: column; gap: 14px; }
    .review-gallery .gallery-kicker { font-size: .72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--ink-faint); font-weight: 700; }
    .gallery-thumb { display: block; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--line); box-shadow: var(--shadow-sm); }
    .gallery-thumb img { width: 100%; height: auto; display: block; transition: transform .3s ease; }
    .gallery-thumb:hover img { transform: scale(1.06); }

    section.block { margin: 52px 0; }

    .video-wrap { border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md); }
    .instagram-embed-wrap { display: flex; justify-content: center; }
    .instagram-embed-wrap .instagram-media { box-shadow: var(--shadow-md) !important; border-radius: var(--radius-lg) !important; }
    .setlist-block img { border-radius: var(--radius); border: 1px solid var(--line); }

    .tags-row { display: flex; flex-wrap: wrap; gap: 8px; margin: 32px 0; }
    .share-panel { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; padding: 22px 26px; background: var(--ink); border-radius: var(--radius); color: #F7F4EE; }
    .share-panel strong { font-size: .85rem; letter-spacing: .5px; }
    .share-panel .btn-ghost { background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.18); color: #F7F4EE; }
    .share-panel .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }

    @media (max-width: 800px) {
        .review-layout { grid-template-columns: 1fr; }
        .review-gallery { flex-direction: row; flex-wrap: wrap; }
        .review-gallery .gallery-thumb { width: calc(33.33% - 10px); }
    }
</style>
@endsection

@section('content')
<a href="{{ route('reviews.index') }}" class="back-link reveal">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
    Volver a reseñas
</a>

<article class="review-header reveal">
    <span class="kicker">Reseña</span>
    <h1>{{ $review->title }}</h1>
    <div class="meta-grid">
        <div>
            <div class="label">Banda</div>
            <div class="value">
                @if($review->band)
                    <a href="{{ route('bands.show', $review->band) }}">{{ $review->band->name }}</a>
                @else
                    Lineup variado
                @endif
            </div>
        </div>
        <div>
            <div class="label">Fecha del show</div>
            <div class="value">{{ $review->show_date->format('d/m/Y') }}</div>
        </div>
        <div>
            <div class="label">Lugar</div>
            <div class="value">{{ $review->venue }}</div>
        </div>
        <div>
            <div class="label">Reseña por</div>
            <div class="value">{{ $review->user->name }}</div>
        </div>
    </div>
</article>

<div class="review-layout">
    <div class="review-main">
        <div class="review-content reveal">
            {!! nl2br(e($review->content)) !!}
        </div>

        @if($review->setlist_image)
            <section class="block setlist-block reveal">
                <div class="section-head" style="margin-bottom:24px;"><span class="kicker">Setlist</span><h2 style="font-size:1.8rem;">Qué sonó esa noche</h2></div>
                <a href="{{ asset('storage/' . $review->setlist_image) }}" data-lightbox="{{ asset('storage/' . $review->setlist_image) }}" data-lightbox-alt="Setlist">
                    <img src="{{ asset('storage/' . $review->setlist_image) }}" alt="Setlist">
                </a>
            </section>
        @endif

        @if($review->video_url)
            <section class="block reveal">
                <div class="section-head" style="margin-bottom:24px;"><span class="kicker">Video</span><h2 style="font-size:1.8rem;">Mirá el show</h2></div>
                @if(str_contains($review->video_url, 'youtube') || str_contains($review->video_url, 'youtu.be'))
                    <div class="video-wrap" style="position: relative; padding-bottom: 56.25%; height: 0;">
                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border:0;" src="https://www.youtube.com/embed/{{ preg_replace('/^.*(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]*).*/i', '$1', $review->video_url) }}" allowfullscreen></iframe>
                    </div>
                @elseif(str_contains($review->video_url, 'instagram.com'))
                    <div class="instagram-embed-wrap">
                        <blockquote class="instagram-media" data-instgrm-permalink="{{ $review->video_url }}" data-instgrm-version="14" style="width:100%; max-width:540px;"></blockquote>
                    </div>
                    <script async src="//www.instagram.com/embed.js"></script>
                @else
                    <a href="{{ $review->video_url }}" target="_blank" class="btn btn-outline">Ver video →</a>
                @endif
            </section>
        @endif

        @if($review->tags->count() > 0)
            <div class="tags-row reveal">
                @foreach($review->tags as $tag)
                    <span class="tag">#{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </div>

    @if($review->photos->count() > 0)
        <aside class="review-gallery reveal">
            <span class="gallery-kicker">Fotos del show</span>
            @foreach($review->photos as $photo)
                <a href="{{ asset('storage/' . $photo->photo_url) }}" class="gallery-thumb" data-lightbox="{{ asset('storage/' . $photo->photo_url) }}" data-lightbox-alt="{{ $photo->caption }}">
                    <img src="{{ asset('storage/' . $photo->photo_url) }}" alt="{{ $photo->caption }}">
                </a>
            @endforeach
        </aside>
    @endif
</div>

<div class="share-panel reveal">
    <strong>Compartir:</strong>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-sm btn-ghost">Facebook</a>
    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($review->title) }}" target="_blank" class="btn btn-sm btn-ghost">Twitter</a>
    <a href="https://www.instagram.com/" target="_blank" class="btn btn-sm btn-ghost">Instagram</a>
</div>
@endsection
