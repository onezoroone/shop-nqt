@extends('layouts.app')

@section('title', 'Dự án')
@section('meta_description', 'Khám phá hồ sơ năng lực về ứng dụng web, công cụ và dự án mã nguồn mở của tôi.')

@section('content')
<section class="store-view projects-archive" aria-labelledby="projects-title">
    <div class="projects-archive__shell">
        <header class="projects-archive__masthead reveal">
            <div class="projects-archive__heading">
                <span class="store-section-kicker">Production Case Track</span>
                <h1 id="projects-title">Dự án <span>đã triển khai.</span></h1>
                <p>Bộ sưu tập web app, công cụ và hệ thống đã được build để chạy thật.</p>
            </div>

            <dl class="projects-archive__stats" aria-label="Thông tin hồ sơ dự án">
                <div>
                    <dt>Case files</dt>
                    <dd>{{ $projects->total() }}</dd>
                </div>
                <div>
                    <dt>Chuyên mục</dt>
                    <dd>{{ $categories->count() }}</dd>
                </div>
            </dl>
        </header>

        <nav class="projects-archive__filters reveal" data-reveal-delay="80" aria-label="Lọc danh mục dự án">
            <div class="projects-archive__filter-head">
                <span>Project index</span>
                <span>{{ str_pad((string) ($categories->count() + 1), 2, '0', STR_PAD_LEFT) }} mục</span>
            </div>

            <div class="projects-archive__filter-track">
                <a
                    href="{{ route('projects.index') }}"
                    class="project-filter {{ ! $currentCategory ? 'is-active' : '' }}"
                    id="filter-all"
                    @if (! $currentCategory) aria-current="page" @endif
                >
                    <span>00</span>
                    <strong>Tất cả</strong>
                    <small>All</small>
                </a>

                @foreach ($categories as $category)
                    <a
                        href="{{ route('projects.index', ['category' => $category->slug]) }}"
                        class="project-filter {{ $currentCategory === $category->slug ? 'is-active' : '' }}"
                        id="filter-{{ $category->slug }}"
                        @if ($currentCategory === $category->slug) aria-current="page" @endif
                    >
                        <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <strong>{{ $category->name }}</strong>
                        <small>{{ $category->projects_count }}</small>
                    </a>
                @endforeach
            </div>
        </nav>

        @if ($projects->count())
            <div class="projects-archive__list">
                @foreach ($projects as $index => $project)
                    <a
                        href="{{ route('projects.show', $project) }}"
                        class="project-file {{ $loop->first ? 'project-file--lead' : 'project-file--row' }} {{ ! $loop->first && $loop->even ? 'project-file--reverse' : '' }} reveal"
                        data-reveal-delay="{{ min($index, 4) * 45 }}"
                        id="project-card-{{ $project->id }}"
                    >
                        <span class="project-file__index" aria-hidden="true">
                            {{ str_pad((string) ($projects->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <div class="project-file__media">
                            @if ($project->thumbnail)
                                <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}" loading="lazy">
                            @else
                                <div class="project-file__placeholder" aria-hidden="true">
                                    <span>Case {{ str_pad((string) ($projects->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                                    </svg>
                                    <small>NQT / Production build</small>
                                </div>
                            @endif

                            @if ($project->is_featured)
                                <span class="project-file__featured">
                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 0 0 .95-.69l1.07-3.292Z" />
                                    </svg>
                                    Nổi bật
                                </span>
                            @endif
                        </div>

                        <div class="project-file__content">
                            <div class="project-file__meta">
                                <span>{{ $project->category?->name ?? 'Không phân loại' }}</span>
                                <time datetime="{{ $project->published_at?->toDateString() }}">{{ $project->published_at?->format('M Y') }}</time>
                            </div>

                            <h2>{{ $project->title }}</h2>
                            <p>{{ $project->excerpt }}</p>

                            @if ($project->tech_stack)
                                <div class="project-file__stack" aria-label="Công nghệ sử dụng">
                                    @foreach (array_slice($project->tech_stack, 0, 4) as $tech)
                                        <span>{{ $tech }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <span class="project-file__cta">
                                Xem hồ sơ dự án
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </span>
                        </div>

                        <span class="project-file__arrow" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="projects-archive__pagination">
                {{ $projects->withQueryString()->links() }}
            </div>
        @else
            <div class="projects-archive__empty reveal" role="status">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25A2.25 2.25 0 0 1 10.5 15.75V18A2.25 2.25 0 0 1 8.25 20.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6Z" />
                </svg>
                <span>Không có case file</span>
                <h2>Không tìm thấy dự án nào</h2>
                <p>Hãy thử chọn một danh mục khác.</p>
                <a href="{{ route('projects.index') }}">Xem tất cả dự án</a>
            </div>
        @endif
    </div>
</section>
@endsection
