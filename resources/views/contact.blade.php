@extends('layouts.app')

@section('title', 'Liên hệ')
@section('meta_description', 'Liên hệ — gửi tin nhắn cho tôi về dự án, hợp tác hoặc các câu hỏi.')

@section('content')
<section class="py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 reveal">
            <span class="text-primary font-semibold text-sm uppercase tracking-wider">Liên hệ</span>
            <h1 class="section-heading text-white mt-2">Liên Hệ <span class="gradient-text">Ngay</span></h1>
            <p class="text-gray-400 text-lg">Bạn có dự án nào không? Hãy thảo luận xem tôi có thể giúp gì.</p>
        </div>

        @if (session('success'))
            <div class="glass-card p-6 mb-8 border-success/30 text-center reveal">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-success mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <h3 class="text-lg font-semibold text-white mb-2">Tin nhắn đã được gửi!</h3>
                <p class="text-success">{{ session('success') }}</p>
            </div>
        @endif

        <div class="glass-card p-8 reveal" style="transition-delay: 0.1s">
            <form action="{{ route('contact.store') }}" method="POST" id="contact-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="contact-name" class="block text-sm font-medium text-gray-300 mb-2">Tên</label>
                        <input type="text" id="contact-name" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Tên của bạn">
                        @error('name')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact-email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input type="email" id="contact-email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="email@cuaban.com">
                        @error('email')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="contact-subject" class="block text-sm font-medium text-gray-300 mb-2">Tiêu đề</label>
                    <input type="text" id="contact-subject" name="subject" value="{{ old('subject') }}" required class="form-input" placeholder="Hỏi đáp dự án, hợp tác, v.v.">
                    @error('subject')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="contact-message" class="block text-sm font-medium text-gray-300 mb-2">Tin nhắn</label>
                    <textarea id="contact-message" name="message" rows="6" required class="form-input resize-none" placeholder="Hãy cho tôi biết về dự án của bạn...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full btn-primary text-lg justify-center py-4" id="contact-submit-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                    Gửi Tin Nhắn
                </button>
            </form>
        </div>

        {{-- Contact Info Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-8">
            <a href="mailto:{{ \App\Models\Setting::getValue('contact_email', '') }}" class="glass-card-hover p-5 text-center reveal block" style="transition-delay: 0.2s">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                </div>
                <h4 class="text-sm font-semibold text-white">Email</h4>
                <p class="text-xs text-gray-400 mt-1">{{ \App\Models\Setting::getValue('contact_email', '') }}</p>
            </a>
            <a href="{{ \App\Models\Setting::getValue('telegram_url', '') }}" target="_blank" rel="noopener" class="glass-card-hover p-5 text-center reveal block" style="transition-delay: 0.25s">
                <div class="w-10 h-10 rounded-xl bg-[#0088cc]/10 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-[#0088cc]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.223-.548.223l.188-2.85 5.18-4.686c.223-.195-.054-.282-.346-.088l-6.406 4.03-2.76-.864c-.6-.18-.61-.593.125-.88l10.814-4.17c.502-.18.948.113.805.823z"/></svg>
                </div>
                <h4 class="text-sm font-semibold text-white">Telegram</h4>
                <p class="text-xs text-gray-400 mt-1">
                    {{ \App\Models\Setting::getValue('telegram_url', '') }}
                </p>
            </a>
            <a href="{{ \App\Models\Setting::getValue('github_url', '') }}" target="_blank" rel="noopener" class="glass-card-hover p-5 text-center reveal block" style="transition-delay: 0.3s">
                <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                </div>
                <h4 class="text-sm font-semibold text-white">GitHub</h4>
                <p class="text-xs text-gray-400 mt-1">{{ \App\Models\Setting::getValue('github_url', '') }}</p>
            </a>
            <div class="glass-card p-5 text-center reveal" style="transition-delay: 0.35s">
                <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                </div>
                <h4 class="text-sm font-semibold text-white">Địa điểm</h4>
                <p class="text-xs text-gray-400 mt-1">Việt Nam 🇻🇳</p>
            </div>
        </div>
    </div>
</section>
@endsection
