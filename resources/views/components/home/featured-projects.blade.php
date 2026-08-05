@props([
    'featuredProjects',
])

<section {{ $attributes->merge(['id' => 'projects', 'class' => 'store-section store-projects']) }} aria-labelledby="home-projects-title">
    <div class="home-container">
        <div class="store-section-heading reveal" data-reveal-delay="70">
            <span class="store-section-kicker">Production Case Track</span>
            <h2 id="home-projects-title" class="store-section-title text-split">Những hệ thống đã chạy thật, không chỉ đẹp trên preview.</h2>
            <p>Các dự án nổi bật cho web app, landing bán hàng, thương mại điện tử và automation.</p>
        </div>

        <div class="store-project-track">
            @foreach ($featuredProjects as $index => $project)
                <a
                    href="{{ route('projects.show', $project) }}"
                    class="store-project-case spotlight-card reveal {{ $index === 0 ? 'store-project-case--featured' : '' }}"
                    data-reveal-delay="{{ 120 + ($index * 75) }}"
                    id="featured-project-{{ $project->id }}"
                    aria-label="Xem dự án {{ $project->title }}"
                >
                    <div class="store-project-case__media">
                        @if ($project->thumbnail)
                            <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}" loading="lazy" class="home-image-reveal">
                        @else
                            <div class="store-product-placeholder">
                                <svg aria-hidden="true" class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                                </svg>
                            </div>
                        @endif

                        <div class="store-project-case__overlay" aria-hidden="true">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>

                        @if ($project->is_featured)
                            <span class="store-chip-label store-chip-label--hot">Featured build</span>
                        @endif
                    </div>

                    <div class="store-project-case__content">
                        <div class="store-card-meta">
                            <span>{{ $project->category?->name ?? 'Không phân loại' }}</span>
                            <span>Case {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <h3>{{ $project->title }}</h3>
                        <p>{{ $project->excerpt }}</p>

                        <div class="store-tech-row" aria-label="Công nghệ sử dụng">
                            @foreach (array_slice($project->tech_stack ?? [], 0, 4) as $tech)
                                <span>{{ $tech }}</span>
                            @endforeach
                        </div>
                    </div>

                    <span class="store-card-arrow" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </span>
                </a>
            @endforeach
        </div>

        <div class="reveal store-section-cta" data-reveal-delay="180">
            <a href="{{ route('projects.index') }}" class="home-button home-button--secondary" id="view-all-projects-btn">
                Xem Tất Cả Dự Án
                <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
</section>
