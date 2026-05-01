@extends('layouts.app')

@section('title', $project->title)
@section('meta_description', $project->excerpt)

@section('content')
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8 reveal">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Trang chủ</a>
                <span>/</span>
                <a href="{{ route('projects.index') }}" class="hover:text-primary transition-colors">Dự án</a>
                <span>/</span>
                <span class="text-gray-300">{{ $project->title }}</span>
            </nav>

            {{-- Project Header --}}
            <div class="reveal">
                <div class="flex items-center gap-3 mb-4">
                    <span class="tech-tag">{{ $project->category->name }}</span>
                    @if ($project->is_featured)
                        <span class="px-2 py-1 text-xs font-bold bg-warning/20 text-warning rounded-full">⭐ Nổi bật</span>
                    @endif
                    <span class="text-sm text-gray-500">{{ $project->published_at?->format('F j, Y') }}</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-white mb-4">{{ $project->title }}</h1>
                <p class="text-xl text-gray-400 leading-relaxed">{{ $project->excerpt }}</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3 mt-6 mb-10 reveal" style="transition-delay: 0.1s">
                @if ($project->demo_url)
                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="btn-primary" id="project-demo-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        Xem Demo
                    </a>
                @endif
                @if ($project->source_url)
                    <a href="{{ $project->source_url }}" target="_blank" rel="noopener" class="btn-outline" id="project-source-btn">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        Mã Nguồn
                    </a>
                @endif
            </div>

            {{-- Thumbnail --}}
            <div class="reveal glass-card overflow-hidden mb-10" style="transition-delay: 0.15s">
                <div class="aspect-video bg-gradient-to-br from-primary/20 to-accent/20 relative">
                    @if ($project->thumbnail)
                        <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-white/5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tech Stack --}}
            @if ($project->tech_stack)
                <div class="reveal mb-10" style="transition-delay: 0.2s">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Công Nghệ</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($project->tech_stack as $tech)
                            <span class="tech-tag">{{ $tech }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Description --}}
            <div class="reveal glass-card p-8 mb-10" style="transition-delay: 0.25s">
                <div class="prose-custom max-w-none">
                    {!! $project->description !!}
                </div>
            </div>

            {{-- Related Projects --}}
            @if ($relatedProjects->count())
                <div class="reveal" style="transition-delay: 0.3s">
                    <h3 class="text-xl font-bold text-white mb-6">Dự Án Liên Quan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($relatedProjects as $related)
                            <a href="{{ route('projects.show', $related) }}" class="glass-card-hover group overflow-hidden" id="related-project-{{ $related->id }}">
                                <div class="aspect-video bg-gradient-to-br from-primary/20 to-accent/20 relative overflow-hidden">
                                    @if ($related->thumbnail)
                                        <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h4 class="text-sm font-bold text-white group-hover:text-primary transition-colors">{{ $related->title }}</h4>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $related->excerpt }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
