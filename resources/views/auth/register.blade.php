@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<section class="py-12 store-view store-auth-view flex items-center justify-center min-h-[80vh] relative overflow-hidden">
    <div class="max-w-md w-full px-4 sm:px-6 relative z-10">
        <div class="text-center mb-10 reveal">
            <span class="store-section-kicker">Create Account</span>
            <h1 class="section-heading text-white">Đăng <span class="gradient-text">ký</span></h1>
            <p class="text-gray-400">Tạo tài khoản để mua source code và theo dõi đơn hàng.</p>
        </div>

        <div class="glass-card p-8 sm:p-10 reveal border border-white/10 shadow-2xl relative overflow-hidden" data-reveal-delay="100">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent to-primary"></div>
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Họ và tên</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Nguyễn Văn A" autocomplete="name">
                    @error('name')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="email@cuaban.com" autocomplete="email">
                    @error('email')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Mật khẩu</label>
                    <input type="password" id="password" name="password" required class="form-input" placeholder="••••••••" autocomplete="new-password">
                    @error('password')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Xác nhận mật khẩu</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input" placeholder="••••••••" autocomplete="new-password">
                </div>

                <button type="submit" class="w-full btn-primary text-lg justify-center py-3">
                    Đăng Ký
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-400">
                Đã có tài khoản? <a href="{{ route('login') }}" class="text-primary hover:text-white transition-colors">Đăng nhập</a>
            </div>
        </div>
    </div>
</section>
@endsection
