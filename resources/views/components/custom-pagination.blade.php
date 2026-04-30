@if ($paginator->hasPages())
    <nav>
        <ul class="pagination mb-0">

            <!-- 🔹 Previous -->
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link"
                   href="{{ $paginator->onFirstPage() ? 'javascript:void(0);' : $paginator->previousPageUrl() }}">
                    ← Previous
                </a>
            </li>

            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();

                $start = max($current - 2, 1);
                $end = min($current + 2, $last);
            @endphp

            <!-- 🔹 First Page -->
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>

                @if ($start > 2)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endif

            <!-- 🔹 Middle Pages -->
            @for ($i = $start; $i <= $end; $i++)
                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) }}">
                        {{ $i }}
                    </a>
                </li>
            @endfor

            <!-- 🔹 Last Page -->
            @if ($end < $last)
                @if ($end < $last - 1)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif

                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($last) }}">
                        {{ $last }}
                    </a>
                </li>
            @endif

            <!-- 🔹 Next -->
            <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link"
                   href="{{ !$paginator->hasMorePages() ? 'javascript:void(0);' : $paginator->nextPageUrl() }}">
                    Next →
                </a>
            </li>

        </ul>
    </nav>

    <!-- 🔥 Info Text -->
    <div class="mt-2 text-muted small">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }}
        of {{ $paginator->total() }} entries
    </div>
@endif
