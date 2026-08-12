@if ($paginator->hasPages())
    <nav class="store-pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="store-pagination__summary" aria-live="polite">
            @if ($paginator->firstItem())
                <span>Hiển thị</span>
                <strong>{{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}</strong>
                <span>trên</span>
                <strong>{{ $paginator->total() }}</strong>
                <span>kết quả</span>
            @else
                <span>{{ $paginator->count() }} kết quả</span>
            @endif
        </div>

        <div class="store-pagination__mobile">
            @if ($paginator->onFirstPage())
                <span class="store-pagination__button store-pagination__button--wide is-disabled" aria-disabled="true">
                    <svg aria-hidden="true" class="store-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 0 1 0 1.414L9.414 10l3.293 3.293a1 1 0 0 1-1.414 1.414l-4-4a1 1 0 0 1 0-1.414l4-4a1 1 0 0 1 1.414 0Z" clip-rule="evenodd" />
                    </svg>
                    Trước
                </span>
            @else
                <a class="store-pagination__button store-pagination__button--wide" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <svg aria-hidden="true" class="store-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 0 1 0 1.414L9.414 10l3.293 3.293a1 1 0 0 1-1.414 1.414l-4-4a1 1 0 0 1 0-1.414l4-4a1 1 0 0 1 1.414 0Z" clip-rule="evenodd" />
                    </svg>
                    Trước
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="store-pagination__button store-pagination__button--wide" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    Sau
                    <svg aria-hidden="true" class="store-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 0 1 0-1.414L10.586 10 7.293 6.707a1 1 0 0 1 1.414-1.414l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0Z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="store-pagination__button store-pagination__button--wide is-disabled" aria-disabled="true">
                    Sau
                    <svg aria-hidden="true" class="store-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 0 1 0-1.414L10.586 10 7.293 6.707a1 1 0 0 1 1.414-1.414l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0Z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>

        <div class="store-pagination__desktop" aria-label="Các trang">
            @if ($paginator->onFirstPage())
                <span class="store-pagination__button is-disabled" aria-disabled="true" aria-label="Trang trước">
                    <svg aria-hidden="true" class="store-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 0 1 0 1.414L9.414 10l3.293 3.293a1 1 0 0 1-1.414 1.414l-4-4a1 1 0 0 1 0-1.414l4-4a1 1 0 0 1 1.414 0Z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a class="store-pagination__button" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Trang trước">
                    <svg aria-hidden="true" class="store-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 0 1 0 1.414L9.414 10l3.293 3.293a1 1 0 0 1-1.414 1.414l-4-4a1 1 0 0 1 0-1.414l4-4a1 1 0 0 1 1.414 0Z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="store-pagination__ellipsis" aria-hidden="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="store-pagination__button is-active" aria-current="page">
                                <span class="sr-only">Trang hiện tại</span>
                                {{ $page }}
                            </span>
                        @else
                            <a class="store-pagination__button" href="{{ $url }}" aria-label="Đến trang {{ $page }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="store-pagination__button" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Trang sau">
                    <svg aria-hidden="true" class="store-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 0 1 0-1.414L10.586 10 7.293 6.707a1 1 0 0 1 1.414-1.414l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0Z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="store-pagination__button is-disabled" aria-disabled="true" aria-label="Trang sau">
                    <svg aria-hidden="true" class="store-pagination__icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 0 1 0-1.414L10.586 10 7.293 6.707a1 1 0 0 1 1.414-1.414l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0Z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
