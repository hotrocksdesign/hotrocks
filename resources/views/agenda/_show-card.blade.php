{{-- Expects $show --}}
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
