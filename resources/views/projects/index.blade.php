@extends('layouts.app')

@section('title', 'Dự án')
@section('meta_description', 'Khám phá hồ sơ năng lực về ứng dụng web, công cụ và dự án mã nguồn mở của tôi.')

@section('content')
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-10 reveal">
                <span class="text-primary font-semibold text-sm uppercase tracking-wider">Hồ sơ năng lực</span>
                <h1 class="section-heading text-white mt-2">Dự Án <span class="gradient-text">Của Tôi</span></h1>
                <p class="text-gray-400 text-lg">Khám phá bộ sưu tập ứng dụng web, công cụ và dự án mã nguồn mở của tôi</p>
            </div>

            {{-- Category Filter --}}
            <div class="flex flex-wrap gap-2 mb-10 reveal" style="transition-delay: 0.1s">
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
                        <a href="{{ route('projects.show', $project) }}" class="glass-card-hover group overflow-hidden reveal" style="transition-delay: {{ ($index % 6) * 0.05 }}s" id="project-card-{{ $project->id }}">
                            <div class="aspect-video bg-gradient-to-br from-primary/20 to-accent/20 relative overflow-hidden">
                                @if ($project->thumbnail)
                                    <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>
                                    </div>
                                @endif
                                @if ($project->is_featured)
                                    <div class="absolute top-3 right-3 px-2 py-1 text-xs font-bold bg-warning/90 text-black rounded-full">⭐ Nổi bật</div>
                                @endif
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-primary font-medium">{{ $project->category->name }}</span>
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
