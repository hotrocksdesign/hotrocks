{{-- Overrides Laravel's default pagination view, which ships styled for
     Tailwind — a framework this project doesn't load, so those classes
     rendered as plain unstyled links. Reuses the site's own .btn system. --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination-nav">
        @if ($paginator->onFirstPage())
            <span class="btn btn-sm btn-outline pagination-disabled" aria-disabled="true">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm btn-outline" rel="prev">&laquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pagination-dots">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="btn btn-sm btn-accent" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="btn btn-sm btn-outline">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm btn-outline" rel="next">&raquo;</a>
        @else
            <span class="btn btn-sm btn-outline pagination-disabled" aria-disabled="true">&raquo;</span>
        @endif
    </nav>
@endif
