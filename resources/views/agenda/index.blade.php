@extends('layout')

@section('title', 'Agenda de Shows')

@section('extra-styles')
<style>
    .search-filters { padding: 30px; margin-bottom: 48px; }
    .search-filters h3 { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1rem; letter-spacing: .5px; margin-bottom: 20px; }
    .filter-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-bottom: 18px; }

    .shows-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .show-card { padding: 16px; }
    .show-card-inner { display: flex; gap: 14px; align-items: flex-start; }
    .show-flyer { flex-shrink: 0; width: 72px; }
    .show-flyer img { width: 100%; aspect-ratio: 3/4; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--line); box-shadow: var(--shadow-sm); }
    .show-card-body { flex-grow: 1; min-width: 0; }
    @media (max-width: 760px) {
        .shows-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 560px) {
        .show-card-inner { flex-direction: column; }
        .show-flyer { width: 90px; }
    }
    .show-date { display: inline-flex; align-items: center; gap: 6px; color: var(--accent); font-weight: 800; font-size: .82rem; margin-bottom: 8px; }
    .show-date svg { width: 13px; height: 13px; }
    .show-venue { font-size: .98rem; font-weight: 800; font-family: 'Inter', sans-serif; margin-bottom: 4px; }
    .show-venue a:hover { color: var(--accent); }
    .show-city { color: var(--ink-soft); font-size: .78rem; margin-bottom: 8px; }
    .show-description { color: var(--ink-soft); line-height: 1.5; margin: 8px 0; font-size: .8rem; }
    .show-buttons { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; }
    .empty-message { text-align: center; padding: 70px 20px; color: var(--ink-faint); }
</style>
@endsection

@section('content')
<div class="section-head reveal">
    <span class="kicker">En vivo</span>
    <h2>Agenda de Shows</h2>
</div>

<form method="GET" action="{{ route('agenda.index') }}" class="card search-filters reveal">
    <h3>Buscar shows</h3>
    <div class="filter-row">
        <input type="text" name="band" placeholder="Buscar por banda..." value="{{ request('band') }}">
        <input type="text" name="venue" placeholder="Buscar por lugar/sala..." value="{{ request('venue') }}">
        <input type="text" name="city" placeholder="Ciudad..." value="{{ request('city') }}">
    </div>
    <button type="submit" class="btn btn-accent">Buscar</button>
</form>

@if($shows->count())
    <div class="shows-grid">
        @foreach($shows as $show)
            <article class="card card-hover show-card reveal">
                <div class="show-card-inner">
                    @if($show->flyer_url)
                        <a href="{{ asset('storage/' . $show->flyer_url) }}" class="show-flyer" data-lightbox="{{ asset('storage/' . $show->flyer_url) }}" data-lightbox-alt="Flyer {{ $show->venue }}">
                            <img src="{{ asset('storage/' . $show->flyer_url) }}" alt="Flyer {{ $show->venue }}">
                        </a>
                    @endif
                    <div class="show-card-body">
                        <div class="show-date">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
                            {{ $show->date->format('d \\d\\e F \\d\\e Y \\a \\l\\a\\s H:i') }}
                        </div>

                        <div class="show-venue">
                            @foreach($show->bands as $band)
                                <a href="{{ route('bands.show', $band) }}">{{ $band->name }}</a>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>

                        <div class="show-city">{{ $show->venue }} · {{ $show->city }}</div>

                        @if($show->description)
                            <div class="show-description">{{ Str::limit($show->description, 90) }}</div>
                        @endif

                        <div class="show-buttons">
                            @if($show->ticket_url)
                                <a href="{{ $show->ticket_url }}" target="_blank" class="btn btn-accent btn-sm">Entradas</a>
                            @endif
                            @if($show->review)
                                <a href="{{ route('reviews.show', $show->review) }}" class="btn btn-outline btn-sm">Ver reseña</a>
                            @endif
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@else
    <div class="empty-message">
        <p style="font-size: 1.1rem;">Sin shows disponibles con esos criterios.</p>
    </div>
@endif

<div class="pagination-wrap">
    {{ $shows->links() }}
</div>
@endsection
