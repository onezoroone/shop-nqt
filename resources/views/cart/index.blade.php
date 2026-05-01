@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 reveal">
            <h1 class="section-heading text-white">Giỏ hàng</h1>
        </div>

        @if (session('success'))
            <div class="glass-card p-4 mb-6 border-success/30 text-success text-sm reveal">
                {{ session('success') }}
            </div>
        @endif

        @if (count($cartItems) > 0)
            <div id="cart-content">
                <div class="space-y-4 mb-8">
                @foreach ($cartItems as $index => $item)
                    <div class="glass-card p-4 flex items-center gap-4 reveal" style="transition-delay: {{ $index * 0.05 }}s" id="cart-item-{{ $item['product']->id }}">
                        {{-- Thumbnail --}}
                        <div class="w-20 h-14 rounded-lg bg-gradient-to-br from-primary/20 to-accent/20 overflow-hidden flex-shrink-0">
                            @if ($item['product']->thumbnail)
                                <img src="{{$1['product']->thumbnail_url}}" alt="{{ $item['product']->title }}" class="w-full h-full object-cover">
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('products.show', $item['product']) }}" class="text-sm font-bold text-white hover:text-primary transition-colors line-clamp-1">
                                {{ $item['product']->title }}
                            </a>
                            <div class="flex items-center gap-2 mt-1">
                                @if ($item['product']->isOnSale())
                                    <span class="text-sm font-bold text-success">${{ $item['product']->sale_price }}</span>
                                    <span class="text-xs text-gray-500 line-through">${{ $item['product']->price }}</span>
                                @else
                                    <span class="text-sm font-bold text-success">${{ $item['product']->price }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Quantity --}}
                        <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99" class="form-input w-16 text-center py-1.5 text-sm" onchange="this.form.submit()">
                        </form>

                        {{-- Subtotal --}}
                        <div class="text-right">
                            <span class="text-sm font-bold text-white">${{ number_format($item['subtotal'], 2) }}</span>
                        </div>

                        {{-- Remove --}}
                        <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-500 hover:text-danger transition-colors" title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            {{-- Total --}}
            <div class="glass-card p-6 reveal">
                <div class="flex items-center justify-between mb-6">
                    <span class="text-lg font-semibold text-gray-300">Tổng cộng</span>
                    <span class="text-3xl font-black text-success">${{ number_format($total, 2) }}</span>
                </div>
                
                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full btn-primary text-lg justify-center py-4" id="checkout-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                        Tiến hành Đặt hàng
                    </button>
                </form>

                <a href="{{ route('products.index') }}" class="block text-center text-sm text-gray-400 hover:text-primary transition-colors mt-4">← Tiếp tục Mua sắm</a>
            </div>
        @else
            <div class="glass-card p-16 text-center reveal">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                <h3 class="text-xl font-semibold text-white mb-2">Giỏ hàng của bạn đang trống</h3>
                <p class="text-gray-500 mb-6">Hãy xem các sản phẩm của chúng tôi và thêm những gì bạn thích!</p>
                <a href="{{ route('products.index') }}" class="btn-primary" id="browse-products-btn">Khám Phá Sản Phẩm</a>
            </div>
        @endif
    </div>
</section>
@endsection
