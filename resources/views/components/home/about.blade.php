@props([
    'settings',
    'skills',
])

<section {{ $attributes->merge(['id' => 'about', 'class' => 'store-section store-stack']) }} aria-labelledby="home-about-title">
    <div class="home-container">
        <div class="store-stack-grid">
            <div class="store-stack-copy reveal" data-reveal-delay="70">
                <span class="store-section-kicker">Build System</span>
                <h2 id="home-about-title" class="store-section-title text-split">Một stack bán hàng cần đẹp, nhanh và dễ nâng cấp.</h2>
                <p>
                    {{ $settings('about_text', 'Tôi là một lập trình viên Full-Stack đầy đam mê với kinh nghiệm xây dựng các ứng dụng web, nền tảng nội dung và công cụ tự động hóa.') }}
                </p>

                <div class="store-principle-grid" aria-label="Nguyên tắc phát triển">
                    <article class="store-principle spotlight-card reveal" data-reveal-delay="120">
                        <span class="store-principle__icon">
                            <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                            </svg>
                        </span>
                        <strong>Ship nhanh</strong>
                        <span>Ưu tiên luồng mua, tốc độ tải và mốc launch thật.</span>
                    </article>

                    <article class="store-principle spotlight-card reveal" data-reveal-delay="170">
                        <span class="store-principle__icon">
                            <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                        <strong>Code sạch</strong>
                        <span>Cấu trúc dễ đọc, dễ bảo trì, không khóa cứng ý tưởng.</span>
                    </article>

                    <article class="store-principle spotlight-card reveal" data-reveal-delay="220">
                        <span class="store-principle__icon">
                            <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0 3.75 3.75m0 0 3.75-3.75M12 20.25V9.75" />
                            </svg>
                        </span>
                        <strong>Hiệu năng</strong>
                        <span>Thiết kế animation có nhịp, không đánh đổi Core Web Vitals.</span>
                    </article>
                </div>
            </div>

            <div class="store-stack-panel spotlight-card reveal" data-reveal-delay="150">
                <div class="store-stack-panel__top">
                    <span>Capability Matrix</span>
                    <strong>{{ $skills->flatten()->count() }} skills</strong>
                </div>

                <div class="store-stack-panel__body">
                    @forelse ($skills as $category => $categorySkills)
                        <section class="store-skill-group" aria-labelledby="skill-group-{{ \Illuminate\Support\Str::slug($category) }}">
                            <div class="store-skill-group__heading">
                                <h3 id="skill-group-{{ \Illuminate\Support\Str::slug($category) }}">{{ $category }}</h3>
                                <span>{{ $categorySkills->count() }} modules</span>
                            </div>

                            <div class="store-skill-grid">
                                @foreach ($categorySkills as $skill)
                                    <div class="store-skill-row">
                                        <div class="store-skill-row__meta">
                                            <span>{{ $skill->name }}</span>
                                            <strong>{{ $skill->proficiency }}%</strong>
                                        </div>
                                        <div class="skill-bar store-skill-track" role="progressbar" aria-label="{{ $skill->name }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $skill->proficiency }}">
                                            <div class="skill-bar-fill store-skill-fill" data-width="{{ $skill->proficiency }}"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @empty
                        <p class="home-empty-state">Kỹ năng sẽ được cập nhật sớm.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
