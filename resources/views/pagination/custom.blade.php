@if ($paginator->hasPages())
    @php
        $base = 'inline-flex items-center justify-center h-9 min-w-9 px-3 text-xs font-semibold rounded-lg transition-colors';
        $idle = 'bg-white dark:bg-[#1a1d27] border border-slate-200 dark:border-slate-700/60 text-slate-600 dark:text-slate-300 hover:border-indigo-400 hover:text-indigo-600 dark:hover:border-indigo-500/60 dark:hover:text-indigo-400';
        $muted = 'bg-slate-100/60 dark:bg-slate-800/40 text-slate-300 dark:text-slate-600 cursor-not-allowed';
    @endphp

    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-xs text-slate-500 dark:text-slate-400">
            Menampilkan
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
            &ndash;
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->total() }}</span>
        </p>

        <div class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="{{ $base }} {{ $muted }}">« Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $base }} {{ $idle }}">« Prev</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="{{ $base }} {{ $muted }}">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="{{ $base }} bg-indigo-600 text-white border border-indigo-600 shadow-md shadow-indigo-500/25">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="{{ $base }} {{ $idle }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $base }} {{ $idle }}">Next »</a>
            @else
                <span aria-disabled="true" class="{{ $base }} {{ $muted }}">Next »</span>
            @endif
        </div>
    </nav>
@endif
