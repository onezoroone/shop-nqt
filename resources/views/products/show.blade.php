@extends('layouts.app')

@section('title', $product->title)
@section('meta_description', $product->excerpt)

@section('content')
<section class="py-12 store-view store-product-detail-view">
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
                @php
                    $hasGallery = is_array($product->gallery) && count($product->gallery) > 0;
                    $lightboxImages = [];
                    if ($product->thumbnail) {
                        $lightboxImages[] = ['url' => $product->thumbnail_url, 'alt' => $product->title];
                    }
                    if ($hasGallery) {
                        foreach ($product->gallery as $imagePath) {
                            $lightboxImages[] = [
                                'url' => asset('storage/' . $imagePath),
                                'alt' => $product->title . ' - ảnh ' . (count($lightboxImages) + 1),
                            ];
                        }
                    }
                    $activeGalleryImage = $lightboxImages[0] ?? null;
                    $totalSlides = count($lightboxImages);
                @endphp
                <div class="product-detail-gallery glass-card" data-product-gallery>
                    <script type="application/json" data-product-gallery-images>@json($lightboxImages)</script>

                    <div class="product-gallery-main product-gallery-swiper">
                        <div class="product-gallery-meta">
                            <span>Product Preview</span>
                            @if ($totalSlides > 0)
                                <strong data-gallery-counter>1 / {{ $totalSlides }}</strong>
                            @else
                                <strong>No media</strong>
                            @endif
                        </div>

                        @if ($activeGalleryImage)
                            <button type="button" class="product-gallery-stage gallery-lightbox-trigger swiper-slide" data-gallery-open data-lightbox-index="0" aria-label="Xem {{ $product->title }} toàn màn hình">
                                <img src="{{ $activeGalleryImage['url'] }}" alt="{{ $activeGalleryImage['alt'] }}" class="product-gallery-image" data-gallery-image fetchpriority="high">
                                <span class="product-gallery-zoom">
                                    <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A8.25 8.25 0 1 0 5.025 5.025 8.25 8.25 0 0 0 16.65 16.65ZM10.5 7.5v6m3-3h-6" />
                                    </svg>
                                    Phóng to
                                </span>
                            </button>
                        @else
                            <div class="product-gallery-stage product-gallery-stage--empty">
                                <svg aria-hidden="true" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                </svg>
                                <span>Preview đang được cập nhật</span>
                            </div>
                        @endif

                        @if ($totalSlides > 1)
                            <button type="button" class="product-gallery-nav product-gallery-nav--prev swiper-button-prev" data-gallery-prev aria-label="Ảnh trước">
                                <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                            </button>
                            <button type="button" class="product-gallery-nav product-gallery-nav--next swiper-button-next" data-gallery-next aria-label="Ảnh sau">
                                <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    @if ($totalSlides > 1)
                        <div class="product-gallery-thumbs product-thumbs-swiper" role="listbox" aria-label="Chọn ảnh xem trước">
                            @foreach ($lightboxImages as $image)
                                <button type="button" class="product-gallery-thumb swiper-slide {{ $loop->first ? 'is-active swiper-slide-thumb-active' : '' }}" data-gallery-thumb data-gallery-index="{{ $loop->index }}" role="option" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-label="Xem ảnh {{ $loop->iteration }} của {{ $product->title }}">
                                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right: Info --}}
            <div class="lg:col-span-2 reveal" data-reveal-delay="150">
                <div class="product-buy-panel glass-card p-6 sticky top-24">
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach ($product->categories as $cat)
                            <span class="tech-tag">{{ $cat->name }}</span>
                        @endforeach
                    </div>
                    <h1 class="text-2xl font-black text-white mb-3">{{ $product->title }}</h1>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">{{ $product->excerpt }}</p>

                    @if ($product->hasVariants())
                        {{-- ===== VARIANT SELECTOR ===== --}}
                        <div id="variant-selector" class="mb-6">
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Chọn phiên bản</h4>
                            <div class="space-y-2">
                                @foreach ($product->variants as $variant)
                                    @php
                                        $variantIsOnSale = $variant->isOnSale() && $variant->discount_percent > 0;
                                    @endphp
                                    <label class="variant-option group cursor-pointer block" data-variant-id="{{ $variant->id }}">
                                        <input type="radio" name="variant" value="{{ $variant->id }}"
                                            class="sr-only"
                                            data-price="{{ $variant->price }}"
                                            data-sale-price="{{ $variant->sale_price ?? '' }}"
                                            data-is-on-sale="{{ $variantIsOnSale ? '1' : '0' }}"
                                            data-discount="{{ $variantIsOnSale ? $variant->discount_percent : '' }}"
                                            data-name="{{ $variant->name }}"
                                            data-demo-url="{{ $variant->demo_url ?? '' }}"
                                            {{ $variant->is_default ? 'checked' : '' }}>
                                        <div class="variant-option__surface flex items-center justify-between p-3 border-2
                                            {{ $variant->is_default ? 'border-primary bg-primary/10' : 'border-white/10 hover:border-white/30 bg-white/5' }}">
                                            <div class="flex items-center gap-3">
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors
                                                    {{ $variant->is_default ? 'border-primary' : 'border-gray-500' }}">
                                                    <div class="w-2.5 h-2.5 rounded-full transition-colors
                                                        {{ $variant->is_default ? 'bg-primary' : 'bg-transparent' }}"></div>
                                                </div>
                                                <div>
                                                    <span class="text-sm font-bold text-white">{{ $variant->name }}</span>
                                                    @if ($variant->sku)
                                                        <span class="text-[10px] text-gray-500 ml-2">{{ $variant->sku }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                @if ($variantIsOnSale)
                                                    <span class="text-sm font-bold text-success">${{ $variant->sale_price }}</span>
                                                    <span class="text-xs text-gray-500 line-through ml-1">${{ $variant->price }}</span>
                                                @else
                                                    <span class="text-sm font-bold text-success">${{ $variant->price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Dynamic Price Display --}}
                        <div id="variant-price" class="flex items-center gap-3 mb-6">
                            @php
                                $defaultVariant = $product->getDefaultVariant();
                                $defaultVariantIsOnSale = $defaultVariant?->isOnSale() && $defaultVariant->discount_percent > 0;
                            @endphp
                            @if ($defaultVariantIsOnSale)
                                <span class="text-3xl font-black text-success" id="display-price">${{ $defaultVariant->sale_price }}</span>
                                <span class="text-xl text-gray-500 line-through" id="display-original-price">${{ $defaultVariant->price }}</span>
                                <span class="sale-badge" id="display-discount">-{{ $defaultVariant->discount_percent }}%</span>
                            @else
                                <span class="text-3xl font-black text-success" id="display-price">${{ $defaultVariant?->price ?? $product->price }}</span>
                                <span class="text-xl text-gray-500 line-through hidden" id="display-original-price"></span>
                                <span class="sale-badge hidden" id="display-discount"></span>
                            @endif
                        </div>
                    @else
                        {{-- Standard Price (no variants) --}}
                        <div class="flex items-center gap-3 mb-6">
                            @php
                                $productIsOnSale = $product->isOnSale() && $product->discount_percent > 0;
                            @endphp
                            @if ($productIsOnSale)
                                <span class="text-3xl font-black text-success">${{ $product->sale_price }}</span>
                                <span class="text-xl text-gray-500 line-through">${{ $product->price }}</span>
                                <span class="sale-badge">-{{ $product->discount_percent }}%</span>
                            @else
                                <span class="text-3xl font-black text-success">${{ $product->price }}</span>
                            @endif
                        </div>
                    @endif

                    {{-- Stats --}}
                    <div class="flex flex-wrap items-center gap-5 mb-6 text-sm text-gray-400">
                        <span class="inline-flex items-center gap-1.5">
                            <svg aria-hidden="true" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            {{ $product->download_count }} lượt tải
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg aria-hidden="true" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A1.5 1.5 0 0 1 18.75 20.25H5.25A1.5 1.5 0 0 1 3.75 18.75v-12A1.5 1.5 0 0 1 5.25 5.25Z" />
                            </svg>
                            {{ $product->published_at?->format('m/Y') }}
                        </span>
                    </div>

                    {{-- Add to Cart --}}
                    <form action="{{ route('cart.add', $product) }}" method="POST" id="add-to-cart-form">
                        @csrf
                        @if ($product->hasVariants())
                            <input type="hidden" name="variant_id" id="selected-variant-id" value="{{ $product->getDefaultVariant()?->id }}">
                        @endif
                        <button type="submit" class="w-full btn-primary text-lg justify-center py-4" id="add-to-cart-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                            Thêm vào Giỏ hàng
                        </button>
                    </form>

                    @if ($product->hasVariants())
                        @php
                            $defaultDemoUrl = $product->getDefaultVariant()?->demo_url ?? $product->demo_url;
                        @endphp
                        <a href="{{ $defaultDemoUrl ?? '#' }}" target="_blank" rel="noopener"
                            class="w-full btn-outline text-sm justify-center py-3 mt-3 {{ $defaultDemoUrl ? '' : 'hidden' }}"
                            id="product-demo-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            <span id="demo-btn-text">Xem Trước{{ $defaultDemoUrl && $product->getDefaultVariant() ? ' (' . $product->getDefaultVariant()->name . ')' : '' }}</span>
                        </a>
                    @elseif ($product->demo_url)
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
                                    <a href="{{ route('products.index', ['tech' => $tech]) }}" class="tech-tag text-[10px] hover:bg-primary/30 hover:border-primary/50 transition-colors" title="Xem tất cả sản phẩm dùng {{ $tech }}">{{ $tech }}</a>
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

        @if (count($lightboxImages) > 0)
            <div id="product-lightbox" class="product-lightbox fixed inset-0 z-[200] hidden items-center justify-center p-4 sm:p-8" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Xem ảnh toàn màn hình">
                <button type="button" class="product-lightbox__backdrop absolute inset-0 cursor-zoom-out" data-lightbox-close aria-label="Đóng"></button>

                <button type="button" class="product-lightbox__close absolute top-4 right-4 z-10 flex items-center justify-center" data-lightbox-close aria-label="Đóng">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>

                @if (count($lightboxImages) > 1)
                    <button type="button" class="product-lightbox__nav product-lightbox__nav--prev absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 z-10 flex items-center justify-center" data-lightbox-prev aria-label="Ảnh trước">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    </button>
                    <button type="button" class="product-lightbox__nav product-lightbox__nav--next absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 z-10 flex items-center justify-center" data-lightbox-next aria-label="Ảnh sau">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                    </button>
                    <span id="product-lightbox-counter" class="product-lightbox__counter absolute bottom-4 left-1/2 -translate-x-1/2 z-10 tabular-nums"></span>
                @endif

                <figure class="product-lightbox__figure relative z-[1]">
                    <img id="product-lightbox-img" src="" alt="" class="product-lightbox__image max-w-full object-contain select-none pointer-events-none">
                    <figcaption id="product-lightbox-caption" class="sr-only"></figcaption>
                </figure>
            </div>
        @endif

        {{-- Content Tabs --}}
        <div class="mt-10 reveal" id="product-tabs">
            <div class="glass-card overflow-hidden">
                <div class="flex border-b border-white/10" role="tablist" aria-label="Thông tin sản phẩm">
                    <button type="button" class="tab-btn active px-6 py-4 text-sm font-bold text-white border-b-2 border-primary hover:bg-white/5 transition-colors" data-target="tab-description" role="tab" aria-selected="true" aria-controls="tab-description">
                        Chi Tiết Sản Phẩm
                    </button>
                    @if ($product->changelog)
                    <button type="button" class="tab-btn px-6 py-4 text-sm font-bold text-gray-400 border-b-2 border-transparent hover:text-white hover:bg-white/5 transition-colors" data-target="tab-changelog" role="tab" aria-selected="false" aria-controls="tab-changelog">
                        Lịch Sử Cập Nhật (Changelog)
                    </button>
                    @endif
                </div>

                <div class="p-8">
                    <div id="tab-description" class="tab-content prose-custom max-w-none" role="tabpanel">
                        {!! html_entity_decode($product->description) !!}
                    </div>

                    @if ($product->changelog)
                    <div id="tab-changelog" class="tab-content prose-custom max-w-none hidden" role="tabpanel">
                        {!! $product->changelog !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if ($relatedProducts->count())
            <div class="mt-14 reveal">
                <h3 class="text-xl font-bold text-white mb-6">Sản Phẩm Liên Quan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($relatedProducts as $i => $related)
                        <x-product.card :product="$related" :index="$i" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
