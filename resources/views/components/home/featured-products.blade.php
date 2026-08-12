@props([
    'featuredProducts',
])

<section {{ $attributes->merge(['id' => 'shop', 'class' => 'home-release-section']) }} aria-labelledby="home-products-title">
    <div class="home-container">
        <header class="home-section-head reveal" data-reveal-delay="60">
            <div>
                <span>Featured releases</span>
                <h2 id="home-products-title">Code bán được, deploy được, mở rộng được.</h2>
                <p>Các công cụ và template số đang nổi bật trong store.</p>
            </div>
            <a href="{{ route('products.index') }}" id="view-all-products-btn">
                Xem tất cả sản phẩm
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </header>

        @if ($featuredProducts->count())
            <div class="home-release-grid">
                @foreach ($featuredProducts as $index => $product)
                    @php
                        $maxDiscount = null;

                        if ($product->isOnSale() || $product->variants->contains(fn ($variant) => $variant->isOnSale())) {
                            $maxDiscount = $product->hasVariants()
                                ? $product->variants->max(fn ($variant) => $variant->discount_percent)
                                : $product->discount_percent;
                        }
                    @endphp

                    <article
                        class="home-release-product {{ $loop->first ? 'home-release-product--lead' : '' }} reveal spotlight-card"
                        data-reveal-delay="{{ min($index, 3) * 55 }}"
                        id="featured-product-{{ $product->id }}"
                    >
                        <a href="{{ route('products.show', $product) }}" class="home-release-product__media" aria-label="Xem sản phẩm {{ $product->title }}">
                            @if ($product->thumbnail)
                                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" loading="lazy" class="home-image-reveal">
                            @else
                                <div class="home-release-product__placeholder" aria-hidden="true">
                                    <span>Module {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                    </svg>
                                    <small>NQT / Digital asset</small>
                                </div>
                            @endif

                            <span class="home-release-product__number">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>

                            @if ($maxDiscount > 0)
                                <span class="home-release-product__sale">-{{ $maxDiscount }}%</span>
                            @endif
                        </a>

                        <div class="home-release-product__body">
                            <div class="home-release-product__meta">
                                <span>{{ $product->categories->first()?->name ?? 'Không phân loại' }}</span>
                                <span>{{ $product->download_count }} lượt bán</span>
                            </div>

                            <a href="{{ route('products.show', $product) }}">
                                <h3>{{ $product->title }}</h3>
                            </a>

                            @if ($loop->first && $product->excerpt)
                                <p>{{ $product->excerpt }}</p>
                            @endif

                            @if ($loop->first && $product->tech_stack)
                                <div class="home-release-product__stack" aria-label="Công nghệ sử dụng">
                                    @foreach (array_slice($product->tech_stack, 0, 5) as $tech)
                                        <span>{{ $tech }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="home-release-product__footer">
                                <div class="home-release-product__price">
                                    @if ($product->hasVariants())
                                        <span>Từ</span>
                                        <strong>${{ $product->starting_price }}</strong>
                                    @elseif ($product->isOnSale())
                                        <strong>${{ $product->sale_price }}</strong>
                                        <del>${{ $product->price }}</del>
                                    @else
                                        <strong>${{ $product->price }}</strong>
                                    @endif
                                </div>

                                @if ($product->hasVariants())
                                    <a href="{{ route('products.show', $product) }}" class="home-release-product__action" aria-label="Chọn biến thể cho {{ $product->title }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                @else
                                    <button type="button" data-cart-add="{{ route('cart.add', $product) }}" class="home-release-product__action" aria-label="Thêm {{ $product->title }} vào giỏ hàng">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="home-empty-state">Sản phẩm nổi bật sẽ được cập nhật sớm.</p>
        @endif
    </div>
</section>
