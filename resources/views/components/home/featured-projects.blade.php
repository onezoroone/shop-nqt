@props([
    'featuredProjects',
])

<section {{ $attributes->merge(['id' => 'projects', 'class' => 'home-production-section']) }} aria-labelledby="home-projects-title">
    <div class="home-container">
        <header class="home-section-head home-section-head--compact reveal" data-reveal-delay="60">
            <div>
                <span>Production log</span>
                <h2 id="home-projects-title">Những hệ thống đã chạy thật, không chỉ đẹp trên preview.</h2>
                <p>Các dự án nổi bật cho web app, landing bán hàng, thương mại điện tử và automation.</p>
            </div>
            <a href="{{ route('projects.index') }}" id="view-all-projects-btn">
                Xem tất cả dự án
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </header>

        @if ($featuredProjects->count())
            <div class="home-production-log">
                <div class="home-production-log__head" aria-hidden="true">
                    <span>Case</span>
                    <span>Project</span>
                    <span>Build stack</span>
                    <span>Open</span>
                </div>

                @foreach ($featuredProjects as $index => $project)
                    <a
                        href="{{ route('projects.show', $project) }}"
                        class="home-production-row reveal"
                        data-reveal-delay="{{ min($index, 3) * 50 }}"
                        id="featured-project-{{ $project->id }}"
                        aria-label="Xem dự án {{ $project->title }}"
                    >
                        <span class="home-production-row__index">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>

                        <div class="home-production-row__project">
                            <span>{{ $project->category?->name ?? 'Không phân loại' }}</span>
                            <h3>{{ $project->title }}</h3>
                            <p>{{ $project->excerpt }}</p>
                        </div>

                        <div class="home-production-row__stack" aria-label="Công nghệ sử dụng">
                            @foreach (array_slice($project->tech_stack ?? [], 0, 4) as $tech)
                                <span>{{ $tech }}</span>
                            @endforeach
                        </div>

                        <span class="home-production-row__arrow" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </a>
                @endforeach
            </div>
        @else
            <p class="home-empty-state">Dự án nổi bật sẽ được cập nhật sớm.</p>
        @endif
    </div>
</section>
