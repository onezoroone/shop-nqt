@extends('layouts.app')

@section('title', 'Cửa hàng')
@section('meta_description', 'Khám phá các sản phẩm số cao cấp — Gói Laravel, giao diện WordPress và hơn thế nữa.')

@section('content')
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 reveal">
            <span class="text-accent font-semibold text-sm uppercase tracking-wider">Cửa hàng</span>
            <h1 class="section-heading text-white mt-2">Sản Phẩm <span class="gradient-text">Số</span></h1>
            <p class="text-gray-400 text-lg">Các công cụ, giao diện và mẫu cao cấp</p>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 reveal">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('products.index', array_filter(['sort' => $currentSort, 'search' => $search])) }}" class="px-4 py-2 text-sm font-medium rounded-full transition-all {{ !$currentCategory ? 'bg-accent text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' }}" id="shop-filter-all">Tất cả</a>
                @foreach ($categories as $cat)
                    <a href="{{ route('products.index', array_filter(['category' => $cat->slug, 'sort' => $currentSort, 'search' => $search])) }}" class="px-4 py-2 text-sm font-medium rounded-full transition-all {{ $currentCategory === $cat->slug ? 'bg-accent text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' }}">{{ $cat->name }} ({{ $cat->products_count }})</a>
                @endforeach
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('products.index') }}" method="GET" class="relative">
                    @if ($currentCategory)<input type="hidden" name="category" value="{{ $currentCategory }}">@endif
                    <input type="text" name="search" value="{{ $search }}" placeholder="Tìm kiếm..." class="form-input pl-10 py-2 text-sm w-48">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                </form>
                <select onchange="window.location.href=this.value" class="form-input py-2 text-sm w-36">
                    <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'newest'])) }}" {{ $currentSort === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'popular'])) }}" {{ $currentSort === 'popular' ? 'selected' : '' }}>Phổ biến</option>
                    <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'price_low'])) }}" {{ $currentSort === 'price_low' ? 'selected' : '' }}>Giá: Thấp - Cao</option>
                    <option value="{{ route('products.index', array_filter(['category' => $currentCategory, 'search' => $search, 'sort' => 'price_high'])) }}" {{ $currentSort === 'price_high' ? 'selected' : '' }}>Giá: Cao - Thấp</option>
                </select>
            </div>
        </div>

        @if ($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($products as $i => $product)
                    @include('products._card', ['product' => $product, 'index' => $i])
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
