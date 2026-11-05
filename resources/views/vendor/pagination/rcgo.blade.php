@if ($paginator->hasPages())
    <style>
        .rcgo-pg-wrap { margin-top: 56px; padding-top: 32px; border-top: 1px solid #1f2937; }
        @media (min-width: 768px) { .rcgo-pg-wrap { margin-top: 64px; } }
        .rcgo-pg { display: flex; flex-direction: column; gap: 16px; align-items: center; }
        @media (min-width: 640px) { .rcgo-pg { flex-direction: row; justify-content: space-between; } }
        .rcgo-pg-summary { font-size: 14px; line-height: 1.5; color: #9ca3af; margin: 0; }
        .rcgo-pg-summary b { color: #d1d5db; font-weight: 600; }
        .rcgo-pg-pages { display: inline-flex; gap: 8px; align-items: center; flex-wrap: wrap; justify-content: center; }
        .rcgo-pg-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px; border: 1px solid #374151; background: #111827; color: #d1d5db; border-radius: 12px; font-size: 14px; font-weight: 600; line-height: 1; text-decoration: none; transition: border-color .15s, color .15s, background .15s; }
        a.rcgo-pg-btn:hover { border-color: #f97316; color: #fb923c; }
        .rcgo-pg-btn[aria-current="page"] { background: #f97316; border-color: #f97316; color: #ffffff; }
        span.rcgo-pg-btn { color: #4b5563; cursor: not-allowed; }
        .rcgo-pg-dots { color: #6b7280; padding: 0 4px; font-size: 14px; }
    </style>
    <div class="rcgo-pg-wrap">
        <nav role="navigation" aria-label="Navigasi halaman" class="rcgo-pg">
            <p class="rcgo-pg-summary">
                Menampilkan
                @if ($paginator->firstItem())
                    <b>{{ $paginator->firstItem() }}</b>
                    sampai
                    <b>{{ $paginator->lastItem() }}</b>
                @else
                    {{ $paginator->count() }}
                @endif
                dari
                <b>{{ $paginator->total() }}</b>
                hasil
            </p>

            <div class="rcgo-pg-pages">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="rcgo-pg-btn" aria-disabled="true" aria-label="Halaman sebelumnya">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rcgo-pg-btn" aria-label="Halaman sebelumnya">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="rcgo-pg-dots" aria-disabled="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="rcgo-pg-btn" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="rcgo-pg-btn" aria-label="Ke halaman {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rcgo-pg-btn" aria-label="Halaman berikutnya">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span class="rcgo-pg-btn" aria-disabled="true" aria-label="Halaman berikutnya">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </nav>
    </div>
@endif
