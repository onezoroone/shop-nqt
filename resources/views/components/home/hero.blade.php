@props([
    'settings',
    'featuredProjects',
    'featuredProducts',
])

@php
    $heroProduct = $featuredProducts->first();
    $heroProject = $featuredProjects->first();
    $telegramUrl = $settings('telegram_url', 'https://t.me/nqtdev');
    $techSignals = $featuredProducts
        ->pluck('tech_stack')
        ->merge($featuredProjects->pluck('tech_stack'))
        ->flatten()
        ->filter()
        ->unique()
        ->take(8)
        ->values();
@endphp

<section {{ $attributes->merge(['class' => 'store-hero home-hero']) }} aria-labelledby="home-hero-title">
    <div class="store-hero-bg" aria-hidden="true">
        <div class="store-hero-bg__grid"></div>
        <div class="store-hero-bg__beam"></div>
        <div class="store-hero-bg__noise"></div>
    </div>

    <div class="store-hero__inner home-container">
        <div class="store-hero__copy">
            <div class="reveal" data-reveal-delay="30">
                <span class="store-kicker">
                    <span class="store-kicker__dot" aria-hidden="true"></span>
                    NQT Digital Code Store
                </span>
            </div>

            <h1 id="home-hero-title" class="store-hero-title text-split reveal" data-reveal-delay="100">
                {{ $settings('hero_title', 'Kiến tạo trải nghiệm số') }}
            </h1>

            <div class="store-hero__intro reveal" data-reveal-delay="180">
                <p>
                    {{ $settings('hero_subtitle', 'Full-Stack Developer chuyên về Laravel, Mobie App, WordPress và các ứng dụng web tối ưu.') }}
                </p>

                <div class="store-command-line" aria-live="polite">
                    <span>npm run</span>
                    <strong id="typing-text" data-texts='["deploy-laravel-kit", "ship-wordpress-theme", "build-mobile-web", "optimize-checkout-flow"]'></strong>
                    <i aria-hidden="true"></i>
                </div>
            </div>

            <form action="{{ route('products.index') }}" method="GET" class="store-search reveal" data-reveal-delay="250">
                <label for="home-product-search" class="sr-only">Tìm sản phẩm số</label>
                <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input id="home-product-search" type="search" name="search" placeholder="Tìm Laravel kit, UI template, automation script...">
                <button type="submit">Search Store</button>
            </form>

            <div class="store-hero-actions reveal" data-reveal-delay="320">
                <a href="{{ route('products.index') }}" class="home-button home-button--primary" id="hero-shop-btn">
                    <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                    </svg>
                    Khám Phá Cửa Hàng
                </a>

                <a href="{{ route('projects.index') }}" class="home-button home-button--secondary" id="hero-projects-btn">
                    <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
                    </svg>
                    Xem Dự Án
                </a>

                <a href="{{ $telegramUrl }}" target="_blank" rel="noopener" class="home-button home-button--telegram" id="hero-telegram-btn">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0Zm5.894 8.221-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.223-.548.223l.188-2.85 5.18-4.686c.223-.195-.054-.282-.346-.088l-6.406 4.03-2.76-.864c-.6-.18-.61-.593.125-.88l10.814-4.17c.502-.18.948.113.805.823Z" />
                    </svg>
                    Nhắn Telegram
                </a>
            </div>

            <dl class="store-stats reveal" data-reveal-delay="390">
                <div>
                    <dt>Digital products</dt>
                    <dd>{{ $featuredProducts->count() + 2 }}+</dd>
                </div>
                <div>
                    <dt>Case studies</dt>
                    <dd>{{ $featuredProjects->count() + 2 }}+</dd>
                </div>
                <div>
                    <dt>Experience</dt>
                    <dd>3+</dd>
                </div>
            </dl>
        </div>

        <div class="store-lab reveal" data-lab-scene data-reveal-delay="160" aria-label="Sản phẩm và dự án nổi bật">
            <div class="store-lab__word" aria-hidden="true">CODE STORE</div>
            <div class="store-lab__orbit" aria-hidden="true"></div>
            <div class="store-lab__trace store-lab__trace--one" aria-hidden="true"></div>
            <div class="store-lab__trace store-lab__trace--two" aria-hidden="true"></div>

            <div class="store-device spotlight-card home-parallax" data-parallax-speed="0.18">
                <div class="store-device__topbar">
                    <span></span>
                    <span></span>
                    <span></span>
                    <strong>PRODUCT_BOARD.v12</strong>
                </div>

                <div class="store-device__screen">
                    @if ($heroProduct?->thumbnail)
                        <img src="{{ $heroProduct->thumbnail_url }}" alt="{{ $heroProduct->title }}" loading="eager" fetchpriority="high">
                    @else
                        <div class="store-device__placeholder">
                            <svg aria-hidden="true" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.9">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                            </svg>
                        </div>
                    @endif
                    <div class="store-device__scan" aria-hidden="true"></div>
                    <div class="store-device__hud" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                <div class="store-device__meta">
                    <span>{{ $heroProduct?->categories->first()?->name ?? 'Digital product' }}</span>
                    <strong>{{ $heroProduct?->title ?? 'Production-ready code kit' }}</strong>
                    @if ($heroProduct)
                        <em>
                            @if ($heroProduct->hasVariants())
                                Từ ${{ $heroProduct->starting_price }}
                            @elseif ($heroProduct->isOnSale())
                                ${{ $heroProduct->sale_price }}
                            @else
                                ${{ $heroProduct->price }}
                            @endif
                        </em>
                    @endif
                </div>
            </div>

            @if ($heroProduct)
                <a href="{{ route('products.show', $heroProduct) }}" class="store-orbit-card store-orbit-card--product spotlight-card">
                    <span>Featured product</span>
                    <strong>{{ $heroProduct->title }}</strong>
                    <small>{{ $heroProduct->download_count }} lượt bán</small>
                </a>
            @endif

            @if ($heroProject)
                <a href="{{ route('projects.show', $heroProject) }}" class="store-orbit-card store-orbit-card--project spotlight-card">
                    <span>Case study</span>
                    <strong>{{ $heroProject->title }}</strong>
                    <small>{{ $heroProject->category?->name ?? 'Project' }}</small>
                </a>
            @endif

            <div class="store-code-ticker" aria-hidden="true">
                <div>
                    @for ($tickerLoop = 0; $tickerLoop < 2; $tickerLoop++)
                        @forelse ($techSignals as $tech)
                            <span>{{ $tech }}</span>
                        @empty
                            <span>Laravel</span>
                            <span>WordPress</span>
                            <span>TailwindCSS</span>
                            <span>Mobile Web</span>
                        @endforelse
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <div class="home-scroll-cue" aria-hidden="true">
        <span></span>
        Scroll to inspect
    </div>
</section>
