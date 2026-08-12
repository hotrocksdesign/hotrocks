@extends('layout')

@section('title', 'Bandas de Rock')

@section('extra-styles')
<style>
    .bands-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr)); gap: 26px; margin-bottom: 40px; }
    .band-card { padding: 22px; }
    .band-photo { height: 190px; border-radius: var(--radius-sm); background-size: cover; background-position: center; margin-bottom: 18px; background-color: var(--bg); border: 1px solid var(--line); }
    .band-card h3 { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1.25rem; letter-spacing: 0; margin-bottom: 10px; }
    .band-bio { color: var(--ink-soft); font-size: .88rem; line-height: 1.55; margin-bottom: 16px; }
    .band-links { display: flex; gap: 12px; flex-wrap: wrap; margin: 14px 0; }
    .band-links a { font-size: .8rem; font-weight: 700; color: var(--ink-soft); }
    .band-links a:hover { color: var(--accent); }
    .view-more { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: .85rem; margin-top: 8px; }
    .view-more svg { transition: transform .25s ease; }
    .band-card:hover .view-more svg { transform: translateX(4px); }
</style>
@endsection

@section('content')
<div class="section-head reveal">
    <span class="kicker">Directorio</span>
    <h2>Bandas</h2>
</div>

<div class="bands-grid">
    @forelse($bands as $band)
        <div class="card card-hover band-card reveal">
            @if($band->photo_url)
                <div class="band-photo" style="background-image: url('{{ asset('storage/' . $band->photo_url) }}')"></div>
            @endif
            <h3>{{ $band->name }}</h3>
            @if($band->genre)
                <span class="tag">{{ $band->genre }}</span>
            @endif
            @if($band->biography)
                <p class="band-bio">{{ Str::limit($band->biography, 100) }}</p>
            @endif
            <div class="band-links">
                @if($band->instagram_url)<a href="{{ $band->instagram_url }}" target="_blank">Instagram</a>@endif
                @if($band->spotify_url)<a href="{{ $band->spotify_url }}" target="_blank">Spotify</a>@endif
                @if($band->youtube_url)<a href="{{ $band->youtube_url }}" target="_blank">YouTube</a>@endif
            </div>
            <a href="{{ route('bands.show', $band) }}" class="view-more">
                Ver perfil completo
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>
    @empty
        <p style="grid-column: 1 / -1; text-align: center; padding: 60px 0; color: var(--ink-faint);">Sin bandas registradas aún.</p>
    @endforelse
</div>

<div class="pagination-wrap">
    {{ $bands->links() }}
</div>
@endsection
