@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<section class="py-12 flex items-center justify-center min-h-[80vh] relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-accent/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-md w-full px-4 sm:px-6 relative z-10">
        <div class="text-center mb-10 reveal">
            <h1 class="section-heading text-white">Đăng <span class="gradient-text">Nhập</span></h1>
            <p class="text-gray-400">Chào mừng bạn quay lại hệ thống.</p>
        </div>

        <div class="glass-card p-8 sm:p-10 reveal border border-white/10 shadow-2xl relative overflow-hidden" style="transition-delay: 0.1s">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-accent"></div>
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="email@cuaban.com">
                    @error('email')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Mật khẩu</label>
                    <input type="password" id="password" name="password" required class="form-input" placeholder="••••••••">
                    @error('password')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-600 bg-surface-dark text-primary focus:ring-primary/50">
                        <span class="text-sm text-gray-400">Ghi nhớ tôi</span>
                    </label>
                </div>

                <button type="submit" class="w-full btn-primary text-lg justify-center py-3">
                    Đăng Nhập
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-400">
                Chưa có tài khoản? <a href="{{ route('register') }}" class="text-primary hover:text-white transition-colors">Đăng ký ngay</a>
            </div>
        </div>
    </div>
</section>
@endsection
