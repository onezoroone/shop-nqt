@extends('layouts.app')

@section('title', 'Cửa hàng')
@section('meta_description', 'Khám phá các sản phẩm số cao cấp — Gói Laravel, giao diện WordPress và hơn thế nữa.')

@section('content')
<section class="store-view shop-catalog" aria-labelledby="shop-title">
    <div class="shop-catalog__shell">
        <header class="shop-catalog__masthead reveal">
            <div class="shop-catalog__heading">
                <span class="store-section-kicker">Digital Product Store</span>
                <h1 id="shop-title">Sản phẩm <span>số.</span></h1>
                <p>Các công cụ, giao diện và mẫu code cao cấp cho web bán hàng, nội dung và automation.</p>
            </div>

            <dl class="shop-catalog__stats" aria-label="Thông tin danh mục">
                <div>
                    <dt>Kết quả hiện tại</dt>
                    <dd>{{ $products->total() }}</dd>
                </div>
                <div>
                    <dt>Danh mục</dt>
                    <dd>{{ $categories->count() }}</dd>
                </div>
            </dl>
        </header>

        <div class="shop-catalog__workspace">
            <aside class="shop-catalog__rail reveal" data-reveal-delay="60">
                <div class="shop-catalog__rail-head">
                    <span>Catalogue index</span>
                    <span>{{ str_pad((string) ($categories->count() + 1), 2, '0', STR_PAD_LEFT) }} mục</span>
                </div>

                <nav class="shop-catalog__categories" aria-label="Lọc danh mục sản phẩm">
                    <a
                        href="{{ route('products.index', array_filter(['sort' => $currentSort, 'search' => $search])) }}"
                        class="shop-category {{ ! $currentCategory ? 'is-active' : '' }}"
                        id="shop-filter-all"
                        @if (! $currentCategory) aria-current="page" @endif
                    >
                        <span class="shop-category__index">00</span>
                        <strong>Tất cả</strong>
                        <small>All</small>
                    </a>

                    @foreach ($categories as $cat)
                        <a
                            href="{{ route('products.index', array_filter(['category' => $cat->slug, 'sort' => $currentSort, 'search' => $search])) }}"
                            class="shop-category {{ $currentCategory === $cat->slug ? 'is-active' : '' }}"
                            @if ($currentCategory === $cat->slug) aria-current="page" @endif
                        >
                            <span class="shop-category__index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <strong>{{ $cat->name }}</strong>
                            <small>{{ $cat->products_count }}</small>
                        </a>
                    @endforeach
                </nav>

                @if (! empty($currentTech))
                    <div class="shop-catalog__active-filter">
                        <span>Công nghệ</span>
                        <strong>{{ $currentTech }}</strong>
                        <a
                            href="{{ route('products.index', array_filter(['category' => $currentCategory, 'sort' => $currentSort, 'search' => $search])) }}"
                            title="Bỏ lọc công nghệ"
                            aria-label="Bỏ lọc công nghệ {{ $currentTech }}"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>
                @endif
            </aside>

            <div class="shop-catalog__main">
                <div class="shop-catalog__tools reveal" data-reveal-delay="100">
                    <div class="shop-catalog__result-copy" aria-live="polite">
                        <span>Catalogue / {{ str_pad((string) $products->currentPage(), 2, '0', STR_PAD_LEFT) }}</span>
                        <strong>{{ $products->total() }} sản phẩm</strong>
                        @if ($search)
                            <small>cho “{{ $search }}”</small>
                        @endif
                    </div>

                    <div class="shop-catalog__controls">
                        <form action="{{ route('products.index') }}" method="GET" class="shop-search-form" role="search">
                            @if ($currentCategory)
                                <input type="hidden" name="category" value="{{ $currentCategory }}">
                            @endif
                            <label for="shop-search" class="sr-only">Tìm sản phẩm</label>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <input id="shop-search" type="search" name="search" value="{{ $search }}" placeholder="Tìm Laravel kit, theme..." autocomplete="off">
                            <button type="submit" title="Tìm sản phẩm" aria-label="Tìm sản phẩm">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </form>

                        <div class="shop-sort-control">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M6 12h12m-9 6h6" />
                            </svg>
                            <label for="shop-sort" class="sr-only">Sắp xếp sản phẩm</label>
                            <select id="shop-sort" onchange="window.location.href=this.value">
                                <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'newest'])) }}" {{ $currentSort === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'popular'])) }}" {{ $currentSort === 'popular' ? 'selected' : '' }}>Phổ biến</option>
                                <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'price_low'])) }}" {{ $currentSort === 'price_low' ? 'selected' : '' }}>Giá: Thấp - Cao</option>
                                <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'price_high'])) }}" {{ $currentSort === 'price_high' ? 'selected' : '' }}>Giá: Cao - Thấp</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if ($products->count())
                    <div class="shop-catalog__products store-catalog-grid">
                        @foreach ($products as $i => $product)
                            <x-product.card :product="$product" :index="$i" />
                        @endforeach
                    </div>

                    <div class="shop-catalog__pagination">{{ $products->withQueryString()->links() }}</div>
                @else
                    <div class="shop-catalog__empty reveal" role="status">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.1-5.4a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.75 11.25h5" />
                        </svg>
                        <span>Không có kết quả</span>
                        <h2>Không tìm thấy sản phẩm nào</h2>
                        <p>Hãy thử điều chỉnh tìm kiếm hoặc bộ lọc của bạn.</p>
                        <a href="{{ route('products.index') }}">Xem tất cả sản phẩm</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
