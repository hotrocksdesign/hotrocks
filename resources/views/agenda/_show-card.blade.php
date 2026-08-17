{{-- Expects $show --}}
<article class="show-row-item reveal">
    <div class="show-row-inner">
        @if($show->flyer_url)
            <a href="{{ asset('storage/' . $show->flyer_url) }}" class="show-date" data-lightbox="{{ asset('storage/' . $show->flyer_url) }}" data-lightbox-alt="Flyer {{ $show->venue }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
                {{ $show->date->format('d/m/Y H:i') }}
            </a>
        @else
            <span class="show-date">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
                {{ $show->date->format('d/m/Y H:i') }}
            </span>
        @endif

        <div class="show-info">
            <span class="show-venue">
                @foreach($show->bands as $band)
                    <a href="{{ route('bands.show', $band) }}">{{ $band->name }}</a>{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </span>
            <span class="show-city">{{ $show->venue }} · {{ $show->city }}</span>
        </div>

        <div class="show-buttons">
            @if($show->ticket_url)
                <a href="{{ $show->ticket_url }}" target="_blank" class="btn btn-accent btn-sm">Entradas</a>
            @endif
            @if($show->review)
                <a href="{{ route('reviews.show', $show->review) }}" class="btn btn-outline btn-sm">Ver reseña</a>
            @endif
        </div>
    </div>
</article>
