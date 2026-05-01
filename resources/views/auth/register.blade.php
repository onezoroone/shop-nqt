@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<section class="py-12 flex items-center justify-center min-h-[80vh] relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-accent/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-md w-full px-4 sm:px-6 relative z-10">
        <div class="text-center mb-10 reveal">
            <h1 class="section-heading text-white">Đăng <span class="gradient-text">Ký</span></h1>
            <p class="text-gray-400">Tạo tài khoản để mua hàng và theo dõi đơn hàng.</p>
        </div>

        <div class="glass-card p-8 sm:p-10 reveal border border-white/10 shadow-2xl relative overflow-hidden" style="transition-delay: 0.1s">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent to-primary"></div>
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Họ và tên</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Nguyễn Văn A">
                    @error('name')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

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

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Xác nhận mật khẩu</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input" placeholder="••••••••">
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
