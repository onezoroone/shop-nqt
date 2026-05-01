@extends('layouts.app')

@section('title', $product->title)
@section('meta_description', $product->excerpt)

@section('content')
<section class="py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8 reveal">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-primary transition-colors">Cửa hàng</a>
            <span>/</span>
            <span class="text-gray-300">{{ $product->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
            {{-- Left: Images --}}
            <div class="lg:col-span-3 reveal">
                <div class="glass-card overflow-hidden">
                    <div class="aspect-video bg-gradient-to-br from-accent/20 to-primary/20 relative">
                        @if ($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-white/5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($product->images->count())
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        @foreach ($product->images as $image)
                            <div class="glass-card overflow-hidden aspect-video">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text }}" class="w-full h-full object-cover" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Right: Info --}}
            <div class="lg:col-span-2 reveal" style="transition-delay: 0.15s">
                <div class="glass-card p-6 sticky top-24">
                    <span class="tech-tag mb-3 inline-block">{{ $product->category->name }}</span>
                    <h1 class="text-2xl font-black text-white mb-3">{{ $product->title }}</h1>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">{{ $product->excerpt }}</p>

                    {{-- Price --}}
                    <div class="flex items-center gap-3 mb-6">
                        @if ($product->isOnSale())
                            <span class="text-3xl font-black text-success">${{ $product->sale_price }}</span>
                            <span class="text-xl text-gray-500 line-through">${{ $product->price }}</span>
                            <span class="sale-badge">-{{ $product->discount_percent }}%</span>
                        @else
                            <span class="text-3xl font-black text-success">${{ $product->price }}</span>
                        @endif
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center gap-6 mb-6 text-sm text-gray-400">
                        <span>📥 {{ $product->download_count }} lượt tải</span>
                        <span>📅 {{ $product->published_at?->format('m/Y') }}</span>
                    </div>

                    {{-- Add to Cart --}}
                    <form action="{{ route('cart.add', $product) }}" method="POST" id="add-to-cart-form">
                        @csrf
                        <button type="submit" class="w-full btn-primary text-lg justify-center py-4 animate-pulse-glow" id="add-to-cart-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                            Thêm vào Giỏ hàng
                        </button>
                    </form>

                    @if ($product->demo_url)
                        <a href="{{ $product->demo_url }}" target="_blank" rel="noopener" class="w-full btn-outline text-sm justify-center py-3 mt-3" id="product-demo-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            Xem Trước
                        </a>
                    @endif

                    {{-- Tech Stack --}}
                    @if ($product->tech_stack)
                        <div class="mt-6 pt-6 border-t border-white/5">
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Được xây dựng bằng</h4>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($product->tech_stack as $tech)
                                    <span class="tech-tag text-[10px]">{{ $tech }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Features --}}
                    @if ($product->features)
                        <div class="mt-6 pt-6 border-t border-white/5">
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Tính Năng</h4>
                            <div class="space-y-2">
                                @foreach ($product->features as $feature)
                                    <div class="flex items-center gap-2 text-sm text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-success flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                        {{ $feature }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="mt-10 reveal">
            <div class="glass-card p-8">
                <h2 class="text-xl font-bold text-white mb-6">Chi Tiết Sản Phẩm</h2>
                <div class="prose-custom max-w-none">
                    {!! $product->description !!}
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if ($relatedProducts->count())
            <div class="mt-14 reveal">
                <h3 class="text-xl font-bold text-white mb-6">Sản Phẩm Liên Quan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($relatedProducts as $i => $related)
                        @include('products._card', ['product' => $related, 'index' => $i])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
