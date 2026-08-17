@extends('layout')

@section('title', 'Bandas de Rock')

@section('extra-styles')
<style>
    .search-bar {
        margin-bottom: 24px;
        padding: 22px 26px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        display: flex;
        gap: 14px;
    }
    .search-bar input { flex-grow: 1; }
    @media (max-width: 560px) { .search-bar { flex-direction: column; } }

    .letter-filter { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 40px; }
    .letter-filter a {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 30px; height: 30px; padding: 0 4px;
        border-radius: var(--radius-sm); border: 1px solid var(--line);
        font-size: .8rem; font-weight: 700; color: var(--ink-soft);
        background: var(--surface);
    }
    .letter-filter a:hover { border-color: var(--accent); color: var(--accent); }
    .letter-filter a.is-active { background: var(--accent); border-color: var(--accent); color: #fff; }

    .bands-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 16px; margin-bottom: 40px; }
    .band-card { padding: 14px; }
    .band-photo { height: 130px; border-radius: var(--radius-sm); background-size: cover; background-position: center; margin-bottom: 12px; background-color: var(--bg); border: 1px solid var(--line); }
    .band-card h3 { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1rem; letter-spacing: 0; margin-bottom: 6px; }
    .band-bio { color: var(--ink-soft); font-size: .8rem; line-height: 1.5; margin-bottom: 10px; }
    .band-links { display: flex; gap: 10px; flex-wrap: wrap; margin: 10px 0; }
    .band-links a { font-size: .74rem; font-weight: 700; color: var(--ink-soft); }
    .band-links a:hover { color: var(--accent); }
    .view-more { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; font-size: .78rem; margin-top: 4px; }
    .view-more svg { width: 12px; height: 12px; transition: transform .25s ease; }
    .band-card:hover .view-more svg { transform: translateX(4px); }

    .section-head-row { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 36px; }

    @media (max-width: 600px) {
        .bands-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .band-card { padding: 10px; }
        .band-photo { height: 100px; margin-bottom: 8px; }
    }
</style>
@endsection

@section('content')
<div class="section-head-row reveal">
    <div class="section-head" style="margin-bottom: 0;">
        <span class="kicker">Directorio</span>
        <h2>Bandas</h2>
    </div>
    <a href="{{ route('bands.submit') }}" class="btn btn-accent">+ Sumá tu Banda</a>
</div>

<form action="{{ route('bands.index') }}" method="GET" class="card search-bar reveal">
    @if(request('letter'))
        <input type="hidden" name="letter" value="{{ request('letter') }}">
    @endif
    <input type="text" name="search" placeholder="Buscar banda por nombre..." value="{{ request('search') }}">
    <button type="submit" class="btn btn-accent">Buscar</button>
</form>

<div class="letter-filter reveal">
    <a href="{{ route('bands.index', request()->except(['letter', 'page'])) }}" class="{{ !request('letter') ? 'is-active' : '' }}">Todas</a>
    @foreach(range('A', 'Z') as $letter)
        <a href="{{ route('bands.index', array_merge(request()->except('page'), ['letter' => $letter])) }}" class="{{ request('letter') === $letter ? 'is-active' : '' }}">{{ $letter }}</a>
    @endforeach
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
        <p style="grid-column: 1 / -1; text-align: center; padding: 60px 0; color: var(--ink-faint);">
            {{ request('search') ? 'No hay bandas que coincidan con "' . request('search') . '".' : 'Sin bandas registradas aún.' }}
        </p>
    @endforelse
</div>

<div class="pagination-wrap">
    {{ $bands->links() }}
</div>
@endsection
