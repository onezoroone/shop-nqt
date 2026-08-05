@extends('layouts.app')

@section('title', 'Dự án')
@section('meta_description', 'Khám phá hồ sơ năng lực về ứng dụng web, công cụ và dự án mã nguồn mở của tôi.')

@section('content')
    <section class="py-12 store-view store-projects-view">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-10 reveal">
                <span class="store-section-kicker">Production Case Track</span>
                <h1 class="section-heading text-white mt-2">Dự án <span class="gradient-text">đã triển khai</span></h1>
                <p class="text-gray-400 text-lg">Bộ sưu tập web app, công cụ và hệ thống đã được build để chạy thật.</p>
            </div>

            {{-- Category Filter --}}
            <div class="glass-card p-4 flex flex-wrap gap-2 mb-10 reveal" data-reveal-delay="100">
                <a href="{{ route('projects.index') }}" class="px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 {{ !$currentCategory ? 'bg-primary text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white' }}" id="filter-all">
                    Tất cả
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('projects.index', ['category' => $category->slug]) }}" class="px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 {{ $currentCategory === $category->slug ? 'bg-primary text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white' }}" id="filter-{{ $category->slug }}">
                        {{ $category->name }} ({{ $category->projects_count }})
                    </a>
                @endforeach
            </div>

            {{-- Projects Grid --}}
            @if ($projects->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($projects as $index => $project)
                        <a href="{{ route('projects.show', $project) }}" class="glass-card-hover group overflow-hidden reveal" data-reveal-delay="{{ ($index % 6) * 50 }}" id="project-card-{{ $project->id }}">
                            <div class="aspect-video bg-gradient-to-br from-primary/20 to-accent/20 relative overflow-hidden">
                                @if ($project->thumbnail)
                                    <img src="{{$project->thumbnail_url}}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>
                                    </div>
                                @endif
                                @if ($project->is_featured)
                                    <div class="store-status-chip store-status-chip--featured absolute top-3 right-3 gap-1">
                                        <svg aria-hidden="true" class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 0 0 .95-.69l1.07-3.292Z" />
                                        </svg>
                                        Nổi bật
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-primary font-medium">{{ $project->category?->name ?? 'Không phân loại' }}</span>
                                    <span class="text-xs text-gray-500">{{ $project->published_at?->format('M Y') }}</span>
                                </div>
                                <h3 class="text-base font-bold text-white group-hover:text-primary transition-colors mb-2">{{ $project->title }}</h3>
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

                <div class="mt-10">
                    {{ $projects->withQueryString()->links() }}
                </div>
            @else
                <div class="glass-card p-16 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6Z" /></svg>
                    <h3 class="text-lg font-semibold text-white mb-2">Không tìm thấy dự án nào</h3>
                    <p class="text-gray-500">Hãy thử chọn một danh mục khác</p>
                </div>
            @endif
        </div>
    </section>
@endsection
