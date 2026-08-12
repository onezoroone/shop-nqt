@props([
    'settings',
    'featuredProjects',
    'featuredProducts',
    'homeMetrics',
])

@php
    $heroProduct = $featuredProducts->first(fn ($product) => filled($product->thumbnail)) ?? $featuredProducts->first();
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

    $heroProductPrice = null;

    if ($heroProduct) {
        if ($heroProduct->hasVariants()) {
            $heroProductPrice = 'Từ $'.$heroProduct->starting_price;
        } elseif ($heroProduct->isOnSale()) {
            $heroProductPrice = '$'.$heroProduct->sale_price;
        } else {
            $heroProductPrice = '$'.$heroProduct->price;
        }
    }
@endphp

<section {{ $attributes->merge(['class' => 'hm-hero home-command']) }} aria-labelledby="home-hero-title">
    <div class="home-command__field" aria-hidden="true"></div>
    <div class="home-command__ambient" data-home-signal-field aria-hidden="true">
        <canvas class="home-command__signal-canvas" data-home-signal-canvas></canvas>
        <span class="home-command__signal-sweep"></span>
        <span class="home-command__signal-bracket home-command__signal-bracket--top"></span>
        <span class="home-command__signal-bracket home-command__signal-bracket--bottom"></span>
    </div>

    <div class="home-container home-command__inner">
        <div class="home-command__topline reveal" data-reveal-delay="30">
            <span>Release desk / NQT Dev</span>
            <span>{{ $homeMetrics['products'] }} products · {{ $homeMetrics['projects'] }} cases</span>
        </div>

        <div class="home-command__copy">
            <span class="home-command__kicker reveal" data-reveal-delay="60">
                <span aria-hidden="true"></span>
                NQT Digital Code Store
            </span>

            <h1 id="home-hero-title" class="home-command__title reveal" data-reveal-delay="100">
                {{ $settings('hero_title', 'Kiến tạo trải nghiệm số') }}
            </h1>

            <p class="home-command__lede reveal" data-reveal-delay="140">
                {{ $settings('hero_subtitle', 'Full-Stack Developer chuyên về Laravel, Mobile App, WordPress và các ứng dụng web tối ưu.') }}
            </p>

            <form action="{{ route('products.index') }}" method="GET" class="home-command__search reveal" data-reveal-delay="180" role="search">
                <label for="home-product-search" class="sr-only">Tìm sản phẩm số</label>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input id="home-product-search" type="search" name="search" placeholder="Tìm Laravel kit, UI template, automation script..." autocomplete="off">
                <button type="submit">
                    <span>Tìm</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>

            <div class="home-command__actions reveal" data-reveal-delay="220">
                <a href="{{ route('products.index') }}" class="home-command__button home-command__button--primary" id="hero-shop-btn">
                    Cửa hàng
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a href="{{ route('projects.index') }}" class="home-command__button" id="hero-projects-btn">Dự án</a>
                <a href="{{ $telegramUrl }}" target="_blank" rel="noopener" class="home-command__link" id="hero-telegram-btn">Telegram</a>
            </div>
        </div>

        <aside class="home-command__release reveal spotlight-card" data-reveal-delay="180" aria-label="Sản phẩm và dự án nổi bật">
            <div class="home-command__release-head">
                <span>Current release</span>
                <span>01 / Live</span>
            </div>

            @if ($heroProduct)
                <a href="{{ route('products.show', $heroProduct) }}" class="home-command__release-product">
                    <span>{{ $heroProduct->categories->first()?->name ?? 'Digital product' }}</span>
                    <strong>{{ $heroProduct->title }}</strong>
                    <small>{{ $heroProductPrice }}</small>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            @endif

            @if ($heroProject)
                <a href="{{ route('projects.show', $heroProject) }}" class="home-command__release-case">
                    <span>Featured case</span>
                    <strong>{{ $heroProject->title }}</strong>
                    <small>{{ $heroProject->category?->name ?? 'Production case' }}</small>
                </a>
            @endif

            <dl class="home-command__metrics">
                <div>
                    <dt>Products</dt>
                    <dd>{{ $homeMetrics['products'] }}</dd>
                </div>
                <div>
                    <dt>Cases</dt>
                    <dd>{{ $homeMetrics['projects'] }}</dd>
                </div>
                <div>
                    <dt>Stack</dt>
                    <dd>{{ $homeMetrics['technologies'] ?: '—' }}</dd>
                </div>
            </dl>
        </aside>

        <div class="home-command__stack reveal" data-reveal-delay="260" aria-label="Công nghệ nổi bật">
            <span>Core stack</span>
            <div>
                @forelse ($techSignals as $tech)
                    <strong>{{ $tech }}</strong>
                @empty
                    <strong>Laravel</strong>
                    <strong>WordPress</strong>
                    <strong>TailwindCSS</strong>
                @endforelse
            </div>
        </div>
    </div>
</section>
