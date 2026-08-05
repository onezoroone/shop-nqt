@props([
    'featuredProducts',
])

@php
    $leadProduct = $featuredProducts->first();
@endphp

<section {{ $attributes->merge(['id' => 'shop', 'class' => 'store-section store-products']) }} aria-labelledby="home-products-title">
    <div class="home-container">
        <div class="store-section-heading reveal" data-reveal-delay="60">
            <span class="store-section-kicker">Digital Product Shelf</span>
            <h2 id="home-products-title" class="store-section-title text-split">Code bán được, deploy được, mở rộng được.</h2>
            <p>Các công cụ và template số đang nổi bật trong store.</p>
        </div>

        @if ($leadProduct)
            <div class="store-shelf">
                @php
                    $leadDiscount = null;

                    if ($leadProduct->isOnSale() || $leadProduct->variants->contains(fn ($variant) => $variant->isOnSale())) {
                        $leadDiscount = $leadProduct->hasVariants()
                            ? $leadProduct->variants->max(fn ($variant) => $variant->discount_percent)
                            : $leadProduct->discount_percent;
                    }
                @endphp

                <article class="store-product-lead spotlight-card reveal" data-reveal-delay="120" id="featured-product-{{ $leadProduct->id }}">
                    <a href="{{ route('products.show', $leadProduct) }}" class="store-product-lead__media" aria-label="Xem sản phẩm {{ $leadProduct->title }}">
                        @if ($leadProduct->thumbnail)
                            <img src="{{ $leadProduct->thumbnail_url }}" alt="{{ $leadProduct->title }}" loading="lazy" class="home-image-reveal">
                        @else
                            <div class="store-product-placeholder">
                                <svg aria-hidden="true" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.9">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                </svg>
                            </div>
                        @endif
                        <span class="store-chip-label">Featured module</span>
                    </a>

                    <div class="store-product-lead__body">
                        <div class="store-card-meta">
                            <span>{{ $leadProduct->categories->first()?->name ?? 'Không phân loại' }}</span>
                            <span>{{ $leadProduct->download_count }} lượt bán</span>
                        </div>

                        <a href="{{ route('products.show', $leadProduct) }}">
                            <h3>{{ $leadProduct->title }}</h3>
                        </a>

                        @if ($leadProduct->excerpt)
                            <p>{{ $leadProduct->excerpt }}</p>
                        @endif

                        <div class="store-tech-row">
                            @foreach (array_slice($leadProduct->tech_stack ?? [], 0, 5) as $tech)
                                <span>{{ $tech }}</span>
                            @endforeach
                        </div>

                        <div class="store-product-actions">
                            <div class="home-price">
                                @if ($leadProduct->hasVariants())
                                    <span>Từ</span>
                                    <strong>${{ $leadProduct->starting_price }}</strong>
                                @elseif ($leadProduct->isOnSale())
                                    <strong>${{ $leadProduct->sale_price }}</strong>
                                    <del>${{ $leadProduct->price }}</del>
                                @else
                                    <strong>${{ $leadProduct->price }}</strong>
                                @endif
                            </div>

                            @if ($leadProduct->hasVariants())
                                <a href="{{ route('products.show', $leadProduct) }}" class="home-button home-button--primary">
                                    Chọn Biến Thể
                                </a>
                            @else
                                <button type="button" data-cart-add="{{ route('cart.add', $leadProduct) }}" class="home-button home-button--primary">
                                    Thêm vào Giỏ
                                </button>
                            @endif
                        </div>

                        <div class="store-badge-row">
                            @if ($leadProduct->hasVariants())
                                <span>{{ $leadProduct->variants->count() }} biến thể</span>
                            @endif

                            @if ($leadDiscount > 0)
                                <span>-{{ $leadDiscount }}%</span>
                            @endif
                        </div>
                    </div>
                </article>

                <div class="store-product-stack">
                    @foreach ($featuredProducts->skip(1) as $index => $product)
                        @php
                            $maxDiscount = null;

                            if ($product->isOnSale() || $product->variants->contains(fn ($variant) => $variant->isOnSale())) {
                                $maxDiscount = $product->hasVariants()
                                    ? $product->variants->max(fn ($variant) => $variant->discount_percent)
                                    : $product->discount_percent;
                            }
                        @endphp

                        <article class="store-product-cartridge spotlight-card reveal" data-reveal-delay="{{ 170 + ($index * 70) }}" id="featured-product-{{ $product->id }}">
                            <a href="{{ route('products.show', $product) }}" class="store-product-cartridge__media" aria-label="Xem sản phẩm {{ $product->title }}">
                                @if ($product->thumbnail)
                                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" loading="lazy" class="home-image-reveal">
                                @else
                                    <div class="store-product-placeholder">
                                        <svg aria-hidden="true" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.9">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            <div class="store-product-cartridge__body">
                                <div class="store-card-meta">
                                    <span>{{ $product->categories->first()?->name ?? 'Không phân loại' }}</span>
                                    @if ($maxDiscount > 0)
                                        <span>-{{ $maxDiscount }}%</span>
                                    @endif
                                </div>

                                <a href="{{ route('products.show', $product) }}">
                                    <h3>{{ $product->title }}</h3>
                                </a>

                                <div class="store-product-cartridge__footer">
                                    <div class="home-price">
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
                                        <a href="{{ route('products.show', $product) }}" class="home-icon-action" aria-label="Chọn biến thể cho {{ $product->title }}">
                                            <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5 15.75 12l-7.5 7.5" />
                                            </svg>
                                        </a>
                                    @else
                                        <button type="button" data-cart-add="{{ route('cart.add', $product) }}" class="home-icon-action" aria-label="Thêm {{ $product->title }} vào giỏ hàng">
                                            <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="reveal store-section-cta" data-reveal-delay="180">
            <a href="{{ route('products.index') }}" class="home-button home-button--secondary" id="view-all-products-btn">
                Xem Tất Cả Sản Phẩm
                <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
</section>
