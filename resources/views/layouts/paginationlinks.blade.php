<nav aria-label="Page navigation">
    <ul class="pagination justify-content-start">
        <li class="page-item disabled"><span class="page-link">{{ $paginator->firstItem() }} -
                {{ $paginator->lastItem() }} of {{ $paginator->total() }}</span></li>
        @if ($paginator->onFirstPage())
        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
        @else
        <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
        </li>
        @endif

        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
        @else
        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
        @endif
        @endforeach
        @endif
        @endforeach

        @if ($paginator->hasMorePages())
        <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a></li>
        @else
        <li class="page-item disabled"><span class="page-link">Next</span></li>
        @endif
    </ul>
</nav>