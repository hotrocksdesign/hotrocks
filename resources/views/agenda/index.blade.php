@extends('layout')

@section('title', 'Agenda de Shows')

@section('extra-styles')
<style>
    .search-filters { padding: 30px; margin-bottom: 48px; }
    .search-filters h3 { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1rem; letter-spacing: .5px; margin-bottom: 20px; }
    .filter-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-bottom: 18px; }

    /* List layout, one show per row, everything on a single line — same
       grid pattern as the home page's "Próximos Shows" panel (date | bands
       | venue/city | button). No flyer thumbnail; clicking the date opens
       the flyer (if there is one) in the lightbox instead. */
    .shows-list { display: flex; flex-direction: column; }
    .show-row-item { padding: 12px 0; border-bottom: 1px solid var(--line); }
    .show-row-item:first-child { padding-top: 0; }
    .show-row-item:last-child { border-bottom: none; }
    .show-row-inner { display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 16px; }
    .show-date { display: inline-flex; align-items: center; gap: 6px; color: var(--accent); font-weight: 800; font-size: .8rem; white-space: nowrap; }
    .show-date svg { width: 13px; height: 13px; flex-shrink: 0; }
    a.show-date[data-lightbox] { cursor: zoom-in; }
    a.show-date[data-lightbox]:hover { text-decoration: underline; }
    .show-info { display: flex; align-items: baseline; gap: 8px; min-width: 0; overflow: hidden; }
    .show-venue { font-size: .92rem; font-weight: 800; font-family: 'Inter', sans-serif; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .show-venue a:hover { color: var(--accent); }
    .show-city { color: var(--ink-soft); font-size: .78rem; white-space: nowrap; flex-shrink: 0; }
    .show-buttons { display: flex; gap: 8px; flex-shrink: 0; }
    .empty-message { text-align: center; padding: 70px 20px; color: var(--ink-faint); }

    @media (max-width: 640px) {
        .show-row-inner { grid-template-columns: 1fr; row-gap: 6px; }
        .show-info { flex-wrap: wrap; }
    }

    /* Past shows: same card grid, tucked away behind a toggle so the
       page opens on what's actually coming up, not what already happened. */
    .past-shows-toggle { text-align: center; margin: 48px 0; }
    .past-shows-toggle .btn svg { transition: transform .25s ease; }
    .past-shows-toggle .btn.is-open svg { transform: rotate(180deg); }
    #pastShowsSection .section-head { margin-top: 8px; }
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

@if($upcomingShows->count())
    <div class="shows-list">
        @foreach($upcomingShows as $show)
            @include('agenda._show-card', ['show' => $show])
        @endforeach
    </div>
    <div class="pagination-wrap">
        {{ $upcomingShows->links() }}
    </div>
@else
    <div class="empty-message">
        <p style="font-size: 1.1rem;">Sin shows próximos con esos criterios.</p>
    </div>
@endif

@if($pastShows->count())
    <div class="past-shows-toggle reveal">
        <button type="button" class="btn btn-outline" id="pastShowsToggle">
            Ver shows anteriores ({{ $pastShows->total() }})
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
        </button>
    </div>

    <div id="pastShowsSection" style="display: none;">
        <div class="section-head reveal">
            <span class="kicker">Ya pasaron</span>
            <h2>Shows Anteriores</h2>
        </div>
        <div class="shows-list">
            @foreach($pastShows as $show)
                @include('agenda._show-card', ['show' => $show])
            @endforeach
        </div>
        <div class="pagination-wrap">
            {{ $pastShows->links() }}
        </div>
    </div>
@endif
@endsection

@section('extra-scripts')
<script>
    (function () {
        var toggle = document.getElementById('pastShowsToggle');
        var section = document.getElementById('pastShowsSection');
        if (!toggle || !section) return;

        // If we're paginating within the past-shows section, open it up
        // straight away instead of hiding the page the user asked for.
        if (new URLSearchParams(window.location.search).has('past_page')) {
            section.style.display = '';
            toggle.classList.add('is-open');
        }

        toggle.addEventListener('click', function () {
            var isOpen = section.style.display !== 'none';
            section.style.display = isOpen ? 'none' : '';
            toggle.classList.toggle('is-open', !isOpen);
        });
    })();
</script>
@endsection
