<div class="glass-card-hover group overflow-hidden reveal" style="transition-delay: {{ ($index % 8) * 0.05 }}s" id="product-card-{{ $product->id }}">
    <a href="{{ route('products.show', $product) }}">
        <div class="aspect-[4/3] bg-gradient-to-br from-accent/20 to-primary/20 relative overflow-hidden">
            @if ($product->thumbnail)
                <img src="{{$product->thumbnail_url}}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
            @else
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                </div>
            @endif
            @if ($product->hasVariants())
                <div class="absolute top-3 right-3 px-2 py-1 text-[10px] font-bold bg-primary/90 text-white rounded-full">{{ $product->variants->count() }} biến thể</div>
            @endif
            @if ($product->isOnSale() || $product->variants->contains(fn ($v) => $v->isOnSale()))
                @php
                    $maxDiscount = $product->hasVariants()
                        ? $product->variants->max(fn ($v) => $v->discount_percent)
                        : $product->discount_percent;
                @endphp
                @if ($maxDiscount > 0)
                    <div class="absolute top-3 left-3 sale-badge">-{{ $maxDiscount }}%</div>
                @endif
            @endif
        </div>
    </a>
    <div class="p-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-accent font-medium">{{ $product->categories->pluck('name')->join(', ') }}</span>
            <span class="text-xs text-gray-500">{{ $product->download_count }} lượt bán</span>
        </div>
        <a href="{{ route('products.show', $product) }}">
            <h3 class="text-sm font-bold text-white group-hover:text-primary transition-colors mb-2 line-clamp-2">{{ $product->title }}</h3>
        </a>
        <p class="text-xs text-gray-400 line-clamp-2 mb-3">{{ $product->excerpt }}</p>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                @if ($product->hasVariants())
                    <span class="text-xs text-gray-400">Từ</span>
                    <span class="text-lg font-bold text-success">${{ $product->starting_price }}</span>
                @elseif ($product->isOnSale())
                    <span class="text-lg font-bold text-success">${{ $product->sale_price }}</span>
                    <span class="text-xs text-gray-500 line-through">${{ $product->price }}</span>
                @else
                    <span class="text-lg font-bold text-success">${{ $product->price }}</span>
                @endif
            </div>
            @if ($product->hasVariants())
                <a href="{{ route('products.show', $product) }}" class="p-2 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all" title="Chọn biến thể">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </a>
            @else
                <button data-cart-add="{{ route('cart.add', $product) }}" class="p-2 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all" title="Thêm vào Giỏ hàng">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                </button>
            @endif
        </div>
    </div>
</div>
