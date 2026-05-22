@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center my-3">
        {{-- Previous Arrow (Left Side) --}}
        @if ($paginator->onFirstPage())
            <span class="fs-2 text-muted px-3" aria-hidden="true">
                <i class="fas fa-arrow-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" class="fs-2 px-3">
                <i class="fas fa-arrow-left"></i>
            </a>
        @endif

        {{-- You can add page number info or keep it empty for clean look --}}
        <div></div>

        {{-- Next Arrow (Right Side) --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="fs-2 px-3">
                <i class="fas fa-arrow-right"></i>
            </a>
        @else
            <span class="fs-2 text-muted px-3" aria-hidden="true">
                <i class="fas fa-arrow-right"></i>
            </span>
        @endif
    </div>
@endif
