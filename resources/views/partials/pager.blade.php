@if ($paginator->hasPages())
    <nav class="pager" aria-label="Halaman">
        @if ($paginator->onFirstPage())
            <span class="chip chip-disabled">← Sebelumnya</span>
        @else
            <a class="chip" href="{{ $paginator->previousPageUrl() }}">← Sebelumnya</a>
        @endif

        <span class="pager-info">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a class="chip" href="{{ $paginator->nextPageUrl() }}">Berikutnya →</a>
        @else
            <span class="chip chip-disabled">Berikutnya →</span>
        @endif
    </nav>
@endif
