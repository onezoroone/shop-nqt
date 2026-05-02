@extends('layouts.app')

@section('title', 'NQT Dev')
@section('meta_description', 'Portfolio and digital product shop by NQT Dev — Full-Stack Developer specializing in Laravel, WordPress, and modern web technologies.')

@section('content')
    {{-- ===== HERO SECTION ===== --}}
    <section class="relative min-h-[90vh] flex items-center overflow-hidden bg-surface-dark">
        {{-- Vanta Background will attach here --}}
        <div id="vanta-bg" class="absolute inset-0 z-0"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 pointer-events-none">
            <div class="max-w-3xl pointer-events-auto">
                <div class="reveal">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-primary text-sm font-medium mb-6">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        Đang nhận dự án mới
                    </span>
                </div>

                <h1 class="reveal text-5xl md:text-7xl font-black text-aurora leading-tight mb-6 drop-shadow-lg" style="transition-delay: 0.1s">
                    {{ $settings('hero_title', 'Kiến tạo trải nghiệm số') }}
                </h1>

                <div class="reveal" style="transition-delay: 0.2s">
                    <p class="text-xl md:text-2xl text-gray-300 drop-shadow leading-relaxed mb-4">
                        {{ $settings('hero_subtitle', 'Full-Stack Developer chuyên về Laravel, Mobie App, WordPress và các ứng dụng web tối ưu.') }}
                    </p>
                    <div class="h-10 drop-shadow">
                        <span id="typing-text" class="text-xl md:text-2xl font-semibold gradient-text" data-texts='["Lập trình viên Laravel", "Chuyên gia WordPress", "Kỹ sư Full-Stack", "Mobile Developer"]'></span>
                        <span class="text-2xl text-primary animate-pulse">|</span>
                    </div>
                </div>

                <div class="reveal flex flex-wrap gap-4 mt-10" style="transition-delay: 0.3s">
                    <a href="{{ route('projects.index') }}" class="btn-primary" id="hero-projects-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" /></svg>
                        Xem Dự Án
                    </a>
                    <a href="{{ route('products.index') }}" class="btn-outline bg-surface-dark/50 backdrop-blur" id="hero-shop-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                        Khám Phá Cửa Hàng
                    </a>
                    <a href="{{ \App\Models\Setting::getValue('telegram_url', 'https://t.me/nqtdev') }}" target="_blank" rel="noopener" class="btn-outline border-[#0088cc] text-[#0088cc] hover:bg-[#0088cc]/10 hover:border-[#0088cc] bg-surface-dark/50 backdrop-blur" style="box-shadow: 0 0 15px rgba(0, 136, 204, 0.2);" id="hero-telegram-btn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.223-.548.223l.188-2.85 5.18-4.686c.223-.195-.054-.282-.346-.088l-6.406 4.03-2.76-.864c-.6-.18-.61-.593.125-.88l10.814-4.17c.502-.18.948.113.805.823z"/></svg>
                        Nhắn Telegram
                    </a>
                </div>

                {{-- Stats --}}
                <div class="reveal flex flex-wrap gap-8 mt-14" style="transition-delay: 0.4s">
                    <div>
                        <div class="text-3xl font-black text-white drop-shadow">{{ $featuredProjects->count() + 2 }}+</div>
                        <div class="text-sm text-gray-300 mt-1">Dự Án</div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-white drop-shadow">{{ $featuredProducts->count() + 2 }}+</div>
                        <div class="text-sm text-gray-300 mt-1">Sản Phẩm</div>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-white drop-shadow">3+</div>
                        <div class="text-sm text-gray-300 mt-1">Năm Kinh Nghiệm</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== ABOUT SECTION ===== --}}
    <section id="about" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="reveal">
                    <span class="text-primary font-semibold text-sm uppercase tracking-wider">Về Tôi</span>
                    <h2 class="section-heading text-white mt-2">Đam Mê <span class="gradient-text">Code Sạch</span></h2>
                    <p class="text-gray-400 leading-relaxed text-lg">
                        {{ $settings('about_text', 'Tôi là một lập trình viên Full-Stack đầy đam mê với kinh nghiệm xây dựng các ứng dụng web, nền tảng nội dung và công cụ tự động hóa.') }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div class="glass-card p-4">
                            <div class="text-2xl font-bold text-primary mb-1">🚀</div>
                            <div class="text-white font-semibold text-sm">Giao Hàng Nhanh</div>
                            <div class="text-gray-500 text-xs mt-1">Tiến độ nhanh chóng</div>
                        </div>
                        <div class="glass-card p-4">
                            <div class="text-2xl font-bold text-accent mb-1">💎</div>
                            <div class="text-white font-semibold text-sm">Code Chất Lượng</div>
                            <div class="text-gray-500 text-xs mt-1">Sạch, dễ bảo trì</div>
                        </div>
                        <div class="glass-card p-4">
                            <div class="text-2xl font-bold text-success mb-1">🔧</div>
                            <div class="text-white font-semibold text-sm">Hỗ Trợ Tận Tình</div>
                            <div class="text-gray-500 text-xs mt-1">Bảo trì liên tục</div>
                        </div>
                        <div class="glass-card p-4">
                            <div class="text-2xl font-bold text-warning mb-1">⚡</div>
                            <div class="text-white font-semibold text-sm">Hiệu Suất Cao</div>
                            <div class="text-gray-500 text-xs mt-1">Tối ưu tốc độ</div>
                        </div>
                    </div>
                </div>

                {{-- Skills --}}
                <div class="reveal" style="transition-delay: 0.2s">
                    <div class="glass-card p-8">
                        <h3 class="text-xl font-bold text-white mb-6">Kỹ Năng Công Nghệ</h3>
                        @foreach ($skills as $category => $categorySkills)
                            <div class="mb-6 last:mb-0">
                                <h4 class="text-sm font-semibold text-primary uppercase tracking-wider mb-3">{{ $category }}</h4>
                                <div class="space-y-3">
                                    @foreach ($categorySkills as $skill)
                                        <div>
                                            <div class="flex justify-between text-sm mb-1">
                                                <span class="text-gray-300">{{ $skill->name }}</span>
                                                <span class="text-gray-500">{{ $skill->proficiency }}%</span>
                                            </div>
                                            <div class="skill-bar">
                                                <div class="skill-bar-fill" data-width="{{ $skill->proficiency }}"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FEATURED PROJECTS ===== --}}
    <section id="projects" class="py-20 bg-surface/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <span class="text-primary font-semibold text-sm uppercase tracking-wider">Hồ Sơ Năng Lực</span>
                <h2 class="section-heading text-white mt-2">Dự Án <span class="gradient-text">Nổi Bật</span></h2>
                <p class="section-subtitle">Một số dự án gần đây mà tôi tự hào</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($featuredProjects as $index => $project)
                    <a href="{{ route('projects.show', $project) }}" class="glass-card-hover group overflow-hidden reveal" style="transition-delay: {{ $index * 0.1 }}s" id="featured-project-{{ $project->id }}">
                        <div class="aspect-video bg-gradient-to-br from-primary/20 to-accent/20 relative overflow-hidden">
                            @if ($project->thumbnail)
                                <img src="{{$project->thumbnail_url}}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>
                                </div>
                            @endif
                            @if ($project->is_featured)
                                <div class="absolute top-3 right-3 px-2 py-1 text-xs font-bold bg-warning/90 text-black rounded-full">⭐ Nổi bật</div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs text-accent font-medium">{{ $product->categories->first()?->name ?? 'Không phân loại' }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-white group-hover:text-primary transition-colors mb-2">{{ $project->title }}</h3>
                            <p class="text-gray-400 text-sm leading-relaxed line-clamp-2">{{ $project->excerpt }}</p>
                            <div class="flex flex-wrap gap-1.5 mt-4">
                                @foreach (array_slice($project->tech_stack ?? [], 0, 4) as $tech)
                                    <span class="tech-tag text-[10px]">{{ $tech }}</span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-10 reveal">
                <a href="{{ route('projects.index') }}" class="btn-outline" id="view-all-projects-btn">
                    Xem Tất Cả Dự Án
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ===== FEATURED PRODUCTS ===== --}}
    <section id="shop" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <span class="text-accent font-semibold text-sm uppercase tracking-wider">Cửa Hàng</span>
                <h2 class="section-heading text-white mt-2">Sản Phẩm <span class="gradient-text">Số</span></h2>
                <p class="section-subtitle">Các công cụ và mẫu cao cấp sẵn sàng cho dự án tiếp theo của bạn</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($featuredProducts as $index => $product)
                    <div class="glass-card-hover group overflow-hidden reveal" style="transition-delay: {{ $index * 0.1 }}s" id="featured-product-{{ $product->id }}">
                        <a href="{{ route('products.show', $product) }}">
                            <div class="aspect-[4/3] bg-gradient-to-br from-accent/20 to-primary/20 relative overflow-hidden">
                                @if ($product->thumbnail)
                                    <img src="{{$product->thumbnail_url}}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                                    </div>
                                @endif
                                @if ($product->isOnSale())
                                    <div class="absolute top-3 left-3 sale-badge">-{{ $product->discount_percent }}%</div>
                                @endif
                            </div>
                        </a>
                        <div class="p-5">
                            <span class="text-xs text-accent font-medium">{{ $product->categories->first()?->name ?? 'Không phân loại' }}</span>
                            <a href="{{ route('products.show', $product) }}">
                                <h3 class="text-sm font-bold text-white group-hover:text-primary transition-colors mt-1 mb-2 line-clamp-2">{{ $product->title }}</h3>
                            </a>
                            <div class="flex items-center gap-2 mb-3">
                                @if ($product->isOnSale())
                                    <span class="text-lg font-bold text-success">${{ $product->sale_price }}</span>
                                    <span class="text-sm text-gray-500 line-through">${{ $product->price }}</span>
                                @else
                                    <span class="text-lg font-bold text-success">${{ $product->price }}</span>
                                @endif
                            </div>
                            <button data-cart-add="{{ route('cart.add', $product) }}" class="w-full btn-primary text-sm justify-center py-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                                Thêm vào Giỏ
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10 reveal">
                <a href="{{ route('products.index') }}" class="btn-outline" id="view-all-products-btn">
                    Xem Tất Cả Sản Phẩm
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ===== CTA SECTION ===== --}}
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-accent/10"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
            <h2 class="text-3xl md:text-5xl font-black text-white mb-6">Sẵn sàng bắt đầu <span class="gradient-text">dự án tiếp theo</span>?</h2>
            <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">Cùng nhau biến ý tưởng của bạn thành hiện thực. Gửi tin nhắn cho tôi và chúng ta cùng thảo luận.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('contact.create') }}" class="btn-primary text-lg px-8 py-4" id="cta-contact-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                    Liên Hệ Ngay
                </a>
                <a href="{{ route('products.index') }}" class="btn-outline text-lg px-8 py-4" id="cta-shop-btn">Khám Phá Sản Phẩm</a>
            </div>
        </div>
    </section>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            VANTA.GLOBE({
                el: "#vanta-bg",
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: 0x3b82f6,
                color2: 0x8b5cf6,
                size: 1.2,
                backgroundColor: 0x0f172a
            });
        });
    </script>
    @endpush
@endsection
