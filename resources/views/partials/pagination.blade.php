@if ($paginator->hasPages())
  <nav class="mps-pagination" aria-label="Pagination">
    @if ($paginator->onFirstPage())
      <span class="mps-page disabled" aria-disabled="true">&laquo;</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" class="mps-page" rel="prev" aria-label="Previous page">&laquo;</a>
    @endif

    @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
      @if ($page == $paginator->currentPage())
        <span class="mps-page active" aria-current="page">{{ $page }}</span>
      @else
        <a href="{{ $url }}" class="mps-page">{{ $page }}</a>
      @endif
    @endforeach

    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" class="mps-page" rel="next" aria-label="Next page">&raquo;</a>
    @else
      <span class="mps-page disabled" aria-disabled="true">&raquo;</span>
    @endif
  </nav>
@endif
