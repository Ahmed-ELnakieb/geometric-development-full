@if ($paginator->hasPages())
    <div class="bs-pagination">
        {{-- First Page Link --}}
        @if ($paginator->currentPage() > 1)
            <a class="pagi-elm" href="{{ $paginator->url(1) }}" aria-label="First">
                <i class="fa-solid fa-angles-left"></i>
            </a>
        @else
            <a class="pagi-elm is-active" href="#" aria-label="First" aria-disabled="true">
                <i class="fa-solid fa-angles-left"></i>
            </a>
        @endif

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a class="pagi-elm is-active" href="#" aria-label="Previous" aria-disabled="true">
                <i class="fa-solid fa-angle-left"></i>
            </a>
        @else
            <a class="pagi-elm" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                <i class="fa-solid fa-angle-left"></i>
            </a>
        @endif

        {{-- Pagination Elements (Limited to 3 pages) --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $start = max(1, $currentPage - 1);
            $end = min($lastPage, $currentPage + 1);
            
            // Adjust to always show 3 pages if possible
            if ($end - $start < 2) {
                if ($start == 1) {
                    $end = min($lastPage, $start + 2);
                } else {
                    $start = max(1, $end - 2);
                }
            }
        @endphp

        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $currentPage)
                <a class="pagi-elm is-active" href="#" aria-label="Page {{ $page }}" aria-current="page">
                    {{ $page }}
                </a>
            @else
                <a class="pagi-elm" href="{{ $paginator->url($page) }}" aria-label="Page {{ $page }}">
                    {{ $page }}
                </a>
            @endif
        @endfor

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="pagi-elm" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                <i class="fa-solid fa-angle-right"></i>
            </a>
        @else
            <a class="pagi-elm is-active" href="#" aria-label="Next" aria-disabled="true">
                <i class="fa-solid fa-angle-right"></i>
            </a>
        @endif

        {{-- Last Page Link --}}
        @if ($paginator->currentPage() < $paginator->lastPage())
            <a class="pagi-elm" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Last">
                <i class="fa-solid fa-angles-right"></i>
            </a>
        @else
            <a class="pagi-elm is-active" href="#" aria-label="Last" aria-disabled="true">
                <i class="fa-solid fa-angles-right"></i>
            </a>
        @endif
    </div>
@endif
