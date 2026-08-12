<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEO::generate() !!}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <script>
        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.classList.add('motion-enabled');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-body min-h-screen flex flex-col">
    {{-- Navigation --}}
    <nav id="main-nav" class="site-nav fixed top-0 left-0 right-0 z-50 bg-transparent">
        <div class="site-shell max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="site-nav__bar flex items-center justify-between gap-4 h-16 md:h-20">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="site-brand flex items-center gap-2 logo-container" id="site-logo" aria-label="NQT Dev">
                    <div class="site-brand-mark w-10 h-10 flex items-center justify-center logo-icon">
                        <span class="site-brand-letter text-white font-black text-lg logo-letter">N</span>
                    </div>
                    <span class="site-brand-text text-xl font-bold text-white logo-text">NQT<span class="text-primary">Dev</span></span>
                </a>

                {{-- Desktop Nav --}}
                <div class="site-nav-links hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="site-nav-link px-4 py-2 text-sm font-medium {{ request()->routeIs('home') ? 'is-active' : '' }}" id="nav-home" @if (request()->routeIs('home')) aria-current="page" @endif>Trang chủ</a>
                    <a href="{{ route('projects.index') }}" class="site-nav-link px-4 py-2 text-sm font-medium {{ request()->routeIs('projects.*') ? 'is-active' : '' }}" id="nav-projects" @if (request()->routeIs('projects.*')) aria-current="page" @endif>Dự án</a>
                    <a href="{{ route('products.index') }}" class="site-nav-link px-4 py-2 text-sm font-medium {{ request()->routeIs('products.*') ? 'is-active' : '' }}" id="nav-shop" @if (request()->routeIs('products.*')) aria-current="page" @endif>Cửa hàng</a>
                    <a href="{{ route('contact.create') }}" class="site-nav-link px-4 py-2 text-sm font-medium {{ request()->routeIs('contact.*') ? 'is-active' : '' }}" id="nav-contact" @if (request()->routeIs('contact.*')) aria-current="page" @endif>Liên hệ</a>
                </div>

                <form action="{{ route('products.index') }}" method="GET" class="site-nav-search hidden lg:flex" role="search" aria-label="Tìm kiếm sản phẩm">
                    <label for="site-nav-search" class="sr-only">Tìm kiếm sản phẩm</label>
                    <svg aria-hidden="true" class="site-nav-search__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input id="site-nav-search" type="search" name="search" value="{{ request()->routeIs('products.*') ? request('search') : '' }}" placeholder="Tìm code, theme, module..." autocomplete="off">
                    <span class="site-nav-search__kbd" aria-hidden="true">Ctrl K</span>
                </form>

                {{-- Cart + Mobile Toggle --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('cart.index') }}" class="site-nav-action relative p-2 {{ request()->routeIs('cart.*') ? 'is-active' : '' }}" id="nav-cart" aria-label="Giỏ hàng" @if (request()->routeIs('cart.*')) aria-current="page" @endif>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        @php $cartCount = array_sum(session('cart', [])); @endphp
                        <span id="cart-badge" class="cart-badge" style="{{ $cartCount > 0 ? '' : 'display:none' }}">{{ $cartCount }}</span>
                    </a>

                    @auth
                        <div class="site-user-menu relative" data-user-menu>
                            <button type="button" class="site-user-button flex items-center gap-2 px-3 py-1.5 text-sm font-medium" aria-label="Mở menu tài khoản" aria-controls="site-user-dropdown" aria-expanded="false" data-user-menu-toggle>
                                <div class="site-user-avatar w-6 h-6 flex items-center justify-center text-xs font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </button>
                            <!-- Dropdown -->
                            <div id="site-user-dropdown" class="site-user-dropdown absolute right-0 mt-2 w-52 z-50 hidden" aria-hidden="true" data-user-menu-dropdown>
                                <div class="py-2">
                                    <div class="px-4 py-2 border-b border-white/5 mb-2">
                                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5">Tài khoản</a>
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5">Đơn hàng của tôi</a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-danger hover:bg-danger/10 transition-colors">Đăng xuất</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="site-login hidden md:inline-flex px-4 py-2 text-sm font-medium">Đăng nhập</a>
                    @endauth
                    
                    <button id="mobile-nav-toggle" type="button" class="site-nav-action md:hidden p-2" aria-label="Mở menu điều hướng" aria-controls="mobile-nav-menu" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Nav Menu --}}
            <div id="mobile-nav-menu" class="hidden md:hidden pb-4" aria-hidden="true">
                <div class="site-mobile-panel p-3 space-y-1 relative z-50">
                    <form action="{{ route('products.index') }}" method="GET" class="site-mobile-search" role="search" aria-label="Tìm kiếm sản phẩm trên mobile">
                        <label for="site-mobile-search" class="sr-only">Tìm kiếm sản phẩm</label>
                        <input id="site-mobile-search" type="search" name="search" placeholder="Tìm code, theme..." autocomplete="off">
                        <button type="submit" aria-label="Tìm kiếm">
                            <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </button>
                    </form>
                    <a href="{{ route('home') }}" class="site-mobile-link block px-4 py-3 text-sm font-medium {{ request()->routeIs('home') ? 'is-active' : '' }}" @if (request()->routeIs('home')) aria-current="page" @endif>Trang chủ</a>
                    <a href="{{ route('projects.index') }}" class="site-mobile-link block px-4 py-3 text-sm font-medium {{ request()->routeIs('projects.*') ? 'is-active' : '' }}" @if (request()->routeIs('projects.*')) aria-current="page" @endif>Dự án</a>
                    <a href="{{ route('products.index') }}" class="site-mobile-link block px-4 py-3 text-sm font-medium {{ request()->routeIs('products.*') ? 'is-active' : '' }}" @if (request()->routeIs('products.*')) aria-current="page" @endif>Cửa hàng</a>
                    <a href="{{ route('contact.create') }}" class="site-mobile-link block px-4 py-3 text-sm font-medium {{ request()->routeIs('contact.*') ? 'is-active' : '' }}" @if (request()->routeIs('contact.*')) aria-current="page" @endif>Liên hệ</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="flex-1 pt-16 md:pt-20">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="site-footer">
        @php
            $footerTelegramUrl = \App\Models\Setting::getValue('telegram_url', 'https://t.me/nqtdev');
            $footerGithubUrl = \App\Models\Setting::getValue('github_url', null);
            $footerEmail = \App\Models\Setting::getValue('contact_email', null);
        @endphp
        <div class="site-footer__inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <p class="site-footer-statement">Code đẹp chỉ có giá trị khi bán được, tải nhanh và vận hành gọn.</p>

            <div class="site-footer__meta">
                <a href="{{ route('home') }}" class="site-brand flex items-center gap-2 logo-container" aria-label="NQT Dev">
                    <div class="site-brand-mark w-10 h-10 flex items-center justify-center logo-icon">
                        <span class="site-brand-letter text-white font-black text-lg logo-letter">N</span>
                    </div>
                    <span class="site-brand-text text-lg font-bold text-white logo-text">NQT<span class="text-primary">Dev</span></span>
                </a>

                <nav class="site-footer-links" aria-label="Liên kết cuối trang">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <a href="{{ route('projects.index') }}">Dự án</a>
                    <a href="{{ route('products.index') }}">Cửa hàng</a>
                    <a href="{{ route('contact.create') }}">Liên hệ</a>
                </nav>

                <div class="site-footer-social" aria-label="Kết nối">
                    @if ($footerGithubUrl)
                        <a href="{{ $footerGithubUrl }}" target="_blank" rel="noopener" class="site-social-link" aria-label="GitHub">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                    @endif
                    <a href="{{ $footerEmail ? 'mailto:' . $footerEmail : route('contact.create') }}" class="site-social-link" aria-label="Email">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                    </a>
                    <a href="{{ $footerTelegramUrl }}" target="_blank" rel="noopener" class="site-social-link" aria-label="Telegram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.223-.548.223l.188-2.85 5.18-4.686c.223-.195-.054-.282-.346-.088l-6.406 4.03-2.76-.864c-.6-.18-.61-.593.125-.88l10.814-4.17c.502-.18.948.113.805.823z"/></svg>
                    </a>
                </div>
            </div>

            <div class="site-footer-bottom">
                <p>© {{ date('Y') }} NQT Dev. Laravel code store and production builds.</p>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
