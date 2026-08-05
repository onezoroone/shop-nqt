@extends('layouts.app')

@section('title', 'Cửa hàng')
@section('meta_description', 'Khám phá các sản phẩm số cao cấp — Gói Laravel, giao diện WordPress và hơn thế nữa.')

@section('content')
<section class="py-12 store-view store-catalog-view">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 reveal">
            <span class="store-section-kicker">Digital Product Store</span>
            <h1 class="section-heading text-white mt-2">Sản phẩm <span class="gradient-text">số</span></h1>
            <p class="text-gray-400 text-lg">Các công cụ, giao diện và mẫu code cao cấp cho web bán hàng, nội dung và automation.</p>
        </div>

        <div class="glass-card p-4 md:p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 reveal">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('products.index', array_filter(['sort' => $currentSort, 'search' => $search])) }}" class="px-4 py-2 text-sm font-medium rounded-full transition-all {{ !$currentCategory ? 'bg-accent text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' }}" id="shop-filter-all">Tất cả</a>
                @foreach ($categories as $cat)
                    <a href="{{ route('products.index', array_filter(['category' => $cat->slug, 'sort' => $currentSort, 'search' => $search])) }}" class="px-4 py-2 text-sm font-medium rounded-full transition-all {{ $currentCategory === $cat->slug ? 'bg-accent text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' }}">{{ $cat->name }} ({{ $cat->products_count }})</a>
                @endforeach
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <form action="{{ route('products.index') }}" method="GET" class="relative">
                    @if ($currentCategory)<input type="hidden" name="category" value="{{ $currentCategory }}">@endif
                    <label for="shop-search" class="sr-only">Tìm sản phẩm</label>
                    <input id="shop-search" type="search" name="search" value="{{ $search }}" placeholder="Tìm Laravel kit, theme, crawler..." class="form-input !pl-10 py-2 text-sm sm:w-64" autocomplete="off">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                </form>
                <label for="shop-sort" class="sr-only">Sắp xếp sản phẩm</label>
                <select id="shop-sort" onchange="window.location.href=this.value" class="form-input py-2 text-sm sm:w-44">
                    <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'newest'])) }}" {{ $currentSort === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'popular'])) }}" {{ $currentSort === 'popular' ? 'selected' : '' }}>Phổ biến</option>
                    <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'price_low'])) }}" {{ $currentSort === 'price_low' ? 'selected' : '' }}>Giá: Thấp - Cao</option>
                    <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'price_high'])) }}" {{ $currentSort === 'price_high' ? 'selected' : '' }}>Giá: Cao - Thấp</option>
                </select>
            </div>
        </div>

        @if (!empty($currentTech))
            <div class="flex items-center gap-3 mb-6 reveal">
                <span class="text-sm text-gray-400">Đang lọc theo công nghệ:</span>
                <span class="tech-tag bg-primary/20 border-primary/40 text-primary">{{ $currentTech }}</span>
                <a href="{{ route('products.index', array_filter(['category' => $currentCategory, 'sort' => $currentSort, 'search' => $search])) }}" class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-danger transition-colors ml-1" title="Bỏ lọc">
                    <svg aria-hidden="true" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Xóa bộ lọc
                </a>
            </div>
        @endif

        @if ($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($products as $i => $product)
                    <x-product.card :product="$product" :index="$i" />
                @endforeach
            </div>
            <div class="mt-10">{{ $products->withQueryString()->links() }}</div>
        @else
            <div class="glass-card p-16 text-center">
                <h3 class="text-lg font-semibold text-white mb-2">Không tìm thấy sản phẩm nào</h3>
                <p class="text-gray-500">Hãy thử điều chỉnh tìm kiếm hoặc bộ lọc của bạn</p>
            </div>
        @endif
    </div>
</section>
@endsection
