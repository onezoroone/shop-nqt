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
                @php
                    $hasGallery = is_array($product->gallery) && count($product->gallery) > 0;
                    $lightboxImages = [];
                    if ($product->thumbnail) {
                        $lightboxImages[] = ['url' => $product->thumbnail_url, 'alt' => $product->title];
                    }
                    if ($hasGallery) {
                        foreach ($product->gallery as $imagePath) {
                            $lightboxImages[] = ['url' => asset('storage/' . $imagePath), 'alt' => $product->title];
                        }
                    }
                    $lightboxIndex = 0;
                @endphp
                <div class="glass-card p-2">
                    {{-- Main Slider --}}
                    <div class="swiper product-gallery-swiper rounded-lg overflow-hidden">
                        <div class="swiper-wrapper">
                            @if ($product->thumbnail)
                                <div class="swiper-slide aspect-video bg-surface-dark relative">
                                    <img src="{{$product->thumbnail_url}}" alt="{{ $product->title }}"
                                        class="w-full h-full object-cover cursor-zoom-in gallery-lightbox-trigger"
                                        data-lightbox-index="{{ $lightboxIndex++ }}" role="button" tabindex="0">
                                </div>
                            @else
                                <div class="swiper-slide aspect-video bg-gradient-to-br from-accent/20 to-primary/20 flex items-center justify-center relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-white/5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                                </div>
                            @endif

                            @if ($hasGallery)
                                @foreach ($product->gallery as $imagePath)
                                    <div class="swiper-slide aspect-video bg-surface-dark relative">
                                        <img src="{{ asset('storage/' . $imagePath) }}" class="w-full h-full object-cover cursor-zoom-in gallery-lightbox-trigger" loading="lazy" alt="Gallery image"
                                            data-lightbox-index="{{ $lightboxIndex++ }}" role="button" tabindex="0">
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Navigation -->
                        <div class="swiper-button-prev !text-primary drop-shadow-md"></div>
                        <div class="swiper-button-next !text-primary drop-shadow-md"></div>
                    </div>

                    {{-- Thumbnail Strip --}}
                    @php
                        $thumbLightboxIndex = 0;
                        $totalSlides = ($product->thumbnail ? 1 : 1) + ($hasGallery ? count($product->gallery) : 0);
                    @endphp
                    @if ($totalSlides > 1)
                        <div class="swiper product-thumbs-swiper mt-2 rounded-lg overflow-hidden">
                            <div class="swiper-wrapper">
                                @if ($product->thumbnail)
                                    <div class="swiper-slide !w-20 !h-14 rounded-md overflow-hidden cursor-pointer opacity-50 border-2 border-transparent transition-all">
                                        <img src="{{$product->thumbnail_url}}" alt="Thumb" class="w-full h-full object-cover gallery-lightbox-trigger"
                                            data-lightbox-index="{{ $thumbLightboxIndex++ }}" role="button" tabindex="0">
                                    </div>
                                @else
                                    <div class="swiper-slide !w-20 !h-14 rounded-md overflow-hidden cursor-pointer opacity-50 border-2 border-transparent bg-gradient-to-br from-accent/20 to-primary/20 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                                    </div>
                                @endif

                                @if ($hasGallery)
                                    @foreach ($product->gallery as $imagePath)
                                        <div class="swiper-slide !w-20 !h-14 rounded-md overflow-hidden cursor-pointer opacity-50 border-2 border-transparent transition-all">
                                            <img src="{{ asset('storage/' . $imagePath) }}" class="w-full h-full object-cover gallery-lightbox-trigger" loading="lazy" alt="Thumb"
                                                data-lightbox-index="{{ $thumbLightboxIndex++ }}" role="button" tabindex="0">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                @if (count($lightboxImages) > 0)
                    <div id="product-lightbox" class="fixed inset-0 z-[200] hidden items-center justify-center p-4 sm:p-8" role="dialog" aria-modal="true" aria-label="Xem ảnh toàn màn hình">
                        <button type="button" class="absolute inset-0 bg-black/95 cursor-zoom-out" data-lightbox-close aria-label="Đóng"></button>

                        <button type="button" class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors flex items-center justify-center" data-lightbox-close aria-label="Đóng">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </button>

                        @if (count($lightboxImages) > 1)
                            <button type="button" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors flex items-center justify-center" data-lightbox-prev aria-label="Ảnh trước">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                            </button>
                            <button type="button" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors flex items-center justify-center" data-lightbox-next aria-label="Ảnh sau">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </button>
                            <span id="product-lightbox-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 text-sm text-white/70 tabular-nums"></span>
                        @endif

                        <img id="product-lightbox-img" src="" alt="" class="relative z-[1] max-w-full max-h-[90vh] object-contain select-none pointer-events-none">
                    </div>
                @endif
            </div>

            {{-- Right: Info --}}
            <div class="lg:col-span-2 reveal" style="transition-delay: 0.15s">
                <div class="glass-card p-6 sticky top-24">
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
                                    <label class="variant-option group cursor-pointer block" data-variant-id="{{ $variant->id }}">
                                        <input type="radio" name="variant" value="{{ $variant->id }}"
                                            class="sr-only"
                                            data-price="{{ $variant->price }}"
                                            data-sale-price="{{ $variant->sale_price ?? '' }}"
                                            data-is-on-sale="{{ $variant->isOnSale() ? '1' : '0' }}"
                                            data-discount="{{ $variant->discount_percent }}"
                                            data-name="{{ $variant->name }}"
                                            data-demo-url="{{ $variant->demo_url ?? '' }}"
                                            {{ $variant->is_default ? 'checked' : '' }}>
                                        <div class="flex items-center justify-between p-3 rounded-xl border-2 transition-all duration-200
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
                                                @if ($variant->isOnSale())
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
                            @php $defaultVariant = $product->getDefaultVariant(); @endphp
                            @if ($defaultVariant->isOnSale())
                                <span class="text-3xl font-black text-success" id="display-price">${{ $defaultVariant->sale_price }}</span>
                                <span class="text-xl text-gray-500 line-through" id="display-original-price">${{ $defaultVariant->price }}</span>
                                <span class="sale-badge" id="display-discount">-{{ $defaultVariant->discount_percent }}%</span>
                            @else
                                <span class="text-3xl font-black text-success" id="display-price">${{ $defaultVariant->price }}</span>
                                <span class="text-xl text-gray-500 line-through hidden" id="display-original-price"></span>
                                <span class="sale-badge hidden" id="display-discount" style="display:none"></span>
                            @endif
                        </div>
                    @else
                        {{-- Standard Price (no variants) --}}
                        <div class="flex items-center gap-3 mb-6">
                            @if ($product->isOnSale())
                                <span class="text-3xl font-black text-success">${{ $product->sale_price }}</span>
                                <span class="text-xl text-gray-500 line-through">${{ $product->price }}</span>
                                <span class="sale-badge">-{{ $product->discount_percent }}%</span>
                            @else
                                <span class="text-3xl font-black text-success">${{ $product->price }}</span>
                            @endif
                        </div>
                    @endif

                    {{-- Stats --}}
                    <div class="flex items-center gap-6 mb-6 text-sm text-gray-400">
                        <span>📥 {{ $product->download_count }} lượt tải</span>
                        <span>📅 {{ $product->published_at?->format('m/Y') }}</span>
                    </div>

                    {{-- Add to Cart --}}
                    <form action="{{ route('cart.add', $product) }}" method="POST" id="add-to-cart-form">
                        @csrf
                        @if ($product->hasVariants())
                            <input type="hidden" name="variant_id" id="selected-variant-id" value="{{ $product->getDefaultVariant()?->id }}">
                        @endif
                        <button type="submit" class="w-full btn-primary text-lg justify-center py-4 animate-pulse-glow" id="add-to-cart-btn">
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

        {{-- Content Tabs --}}
        <div class="mt-10 reveal" id="product-tabs">
            <div class="glass-card overflow-hidden">
                <div class="flex border-b border-white/10">
                    <button class="tab-btn active px-6 py-4 text-sm font-bold text-white border-b-2 border-primary hover:bg-white/5 transition-colors" data-target="tab-description">
                        Chi Tiết Sản Phẩm
                    </button>
                    @if ($product->changelog)
                    <button class="tab-btn px-6 py-4 text-sm font-bold text-gray-400 border-b-2 border-transparent hover:text-white hover:bg-white/5 transition-colors" data-target="tab-changelog">
                        Lịch Sử Cập Nhật (Changelog)
                    </button>
                    @endif
                </div>

                <div class="p-8">
                    <div id="tab-description" class="tab-content prose-custom max-w-none">
                        {!! $product->description !!}
                    </div>

                    @if ($product->changelog)
                    <div id="tab-changelog" class="tab-content prose-custom max-w-none hidden">
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
                        @include('products._card', ['product' => $related, 'index' => $i])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Swiper JS & CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .product-thumbs-swiper .swiper-slide-thumb-active {
        opacity: 1 !important;
        border-color: var(--primary, #6366f1) !important;
    }
    #product-lightbox:not(.hidden) {
        display: flex;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Thumbs swiper (small strip)
        const thumbsEl = document.querySelector('.product-thumbs-swiper');
        let thumbsSwiper = null;
        if (thumbsEl) {
            thumbsSwiper = new Swiper('.product-thumbs-swiper', {
                spaceBetween: 8,
                slidesPerView: 'auto',
                freeMode: true,
                watchSlidesProgress: true,
            });
        }

        // Main gallery swiper
        const mainSwiper = new Swiper('.product-gallery-swiper', {
            grabCursor: true,
            spaceBetween: 0,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            thumbs: thumbsSwiper ? { swiper: thumbsSwiper } : undefined,
        });

        // Fullscreen lightbox
        const lightboxEl = document.getElementById('product-lightbox');
        const lightboxImages = @json($lightboxImages);
        if (lightboxEl && lightboxImages.length > 0) {
            const lightboxImg = document.getElementById('product-lightbox-img');
            const lightboxCounter = document.getElementById('product-lightbox-counter');
            let lightboxCurrentIndex = 0;

            const renderLightbox = () => {
                const image = lightboxImages[lightboxCurrentIndex];
                lightboxImg.src = image.url;
                lightboxImg.alt = image.alt;
                if (lightboxCounter) {
                    lightboxCounter.textContent = (lightboxCurrentIndex + 1) + ' / ' + lightboxImages.length;
                }
            };

            const openLightbox = (index) => {
                if (index < 0 || index >= lightboxImages.length) {
                    return;
                }
                lightboxCurrentIndex = index;
                renderLightbox();
                lightboxEl.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            const closeLightbox = () => {
                lightboxEl.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                lightboxImg.removeAttribute('src');
            };

            const showPrev = () => {
                lightboxCurrentIndex = (lightboxCurrentIndex - 1 + lightboxImages.length) % lightboxImages.length;
                renderLightbox();
            };

            const showNext = () => {
                lightboxCurrentIndex = (lightboxCurrentIndex + 1) % lightboxImages.length;
                renderLightbox();
            };

            document.querySelectorAll('.gallery-lightbox-trigger').forEach((trigger) => {
                const open = () => openLightbox(parseInt(trigger.dataset.lightboxIndex, 10));
                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    open();
                });
                trigger.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        open();
                    }
                });
            });

            lightboxEl.querySelectorAll('[data-lightbox-close]').forEach((btn) => {
                btn.addEventListener('click', closeLightbox);
            });
            lightboxEl.querySelector('[data-lightbox-prev]')?.addEventListener('click', (e) => {
                e.stopPropagation();
                showPrev();
            });
            lightboxEl.querySelector('[data-lightbox-next]')?.addEventListener('click', (e) => {
                e.stopPropagation();
                showNext();
            });

            document.addEventListener('keydown', (e) => {
                if (lightboxEl.classList.contains('hidden')) {
                    return;
                }
                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowLeft' && lightboxImages.length > 1) {
                    showPrev();
                } else if (e.key === 'ArrowRight' && lightboxImages.length > 1) {
                    showNext();
                }
            });
        }

        // Tabs Logic
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.getAttribute('data-target');

                // Reset buttons
                tabBtns.forEach(b => {
                    b.classList.remove('active', 'text-white', 'border-primary');
                    b.classList.add('text-gray-400', 'border-transparent');
                });

                // Set active button
                btn.classList.add('active', 'text-white', 'border-primary');
                btn.classList.remove('text-gray-400', 'border-transparent');

                // Hide all contents
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Show target content
                document.getElementById(target).classList.remove('hidden');
            });
        });

        // ===== Variant Selector Logic =====
        const variantOptions = document.querySelectorAll('.variant-option');
        if (variantOptions.length > 0) {
            const displayPrice = document.getElementById('display-price');
            const displayOriginal = document.getElementById('display-original-price');
            const displayDiscount = document.getElementById('display-discount');
            const hiddenInput = document.getElementById('selected-variant-id');
            const demoBtn = document.getElementById('product-demo-btn');
            const demoBtnText = document.getElementById('demo-btn-text');

            variantOptions.forEach(option => {
                option.addEventListener('click', () => {
                    const radio = option.querySelector('input[type="radio"]');
                    radio.checked = true;

                    // Update hidden input
                    hiddenInput.value = radio.value;

                    // Update visual state
                    variantOptions.forEach(opt => {
                        const container = opt.querySelector('div');
                        const dot = opt.querySelector('.w-5');
                        const innerDot = opt.querySelector('.w-2\\.5');

                        container.classList.remove('border-primary', 'bg-primary/10');
                        container.classList.add('border-white/10', 'bg-white/5');
                        dot.classList.remove('border-primary');
                        dot.classList.add('border-gray-500');
                        innerDot.classList.remove('bg-primary');
                        innerDot.classList.add('bg-transparent');
                    });

                    const activeContainer = option.querySelector('div');
                    const activeDot = option.querySelector('.w-5');
                    const activeInnerDot = option.querySelector('.w-2\\.5');

                    activeContainer.classList.add('border-primary', 'bg-primary/10');
                    activeContainer.classList.remove('border-white/10', 'bg-white/5');
                    activeDot.classList.add('border-primary');
                    activeDot.classList.remove('border-gray-500');
                    activeInnerDot.classList.add('bg-primary');
                    activeInnerDot.classList.remove('bg-transparent');

                    // Update price display
                    const isOnSale = radio.dataset.isOnSale === '1';
                    const price = radio.dataset.price;
                    const salePrice = radio.dataset.salePrice;
                    const discount = radio.dataset.discount;

                    if (isOnSale) {
                        displayPrice.textContent = '$' + parseFloat(salePrice).toFixed(2);
                        displayOriginal.textContent = '$' + parseFloat(price).toFixed(2);
                        displayOriginal.classList.remove('hidden');
                        displayDiscount.textContent = '-' + discount + '%';
                        displayDiscount.classList.remove('hidden');
                        displayDiscount.style.display = '';
                    } else {
                        displayPrice.textContent = '$' + parseFloat(price).toFixed(2);
                        displayOriginal.classList.add('hidden');
                        displayDiscount.classList.add('hidden');
                        displayDiscount.style.display = 'none';
                    }

                    // Update demo button URL
                    if (demoBtn) {
                        const demoUrl = radio.dataset.demoUrl;
                        if (demoUrl) {
                            demoBtn.href = demoUrl;
                            demoBtn.classList.remove('hidden');
                            if (demoBtnText) {
                                demoBtnText.textContent = 'Xem Trước (' + radio.dataset.name + ')';
                            }
                        } else {
                            demoBtn.classList.add('hidden');
                        }
                    }
                });
            });
        }
    });
</script>
@endsection
