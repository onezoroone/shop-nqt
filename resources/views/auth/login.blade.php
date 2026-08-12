@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<section class="store-view store-auth-view auth-access" aria-labelledby="login-title">
    <div class="auth-access__grid">
        <header class="auth-access__intro reveal">
            <a href="{{ route('products.index') }}" class="auth-access__back-link">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Tiếp tục khám phá cửa hàng
            </a>

            <div class="auth-access__heading">
                <span class="store-section-kicker">Account Access</span>
                <h1 id="login-title">Đăng nhập.<br><span>Tiếp tục build.</span></h1>
                <p>Truy cập dashboard, đơn hàng và source code đã mua trong một luồng duy nhất.</p>
            </div>

            <ol class="auth-access__index" aria-label="Quyền truy cập tài khoản">
                <li>
                    <span>01</span>
                    <div>
                        <strong>Dashboard</strong>
                        <small>Thông tin tài khoản</small>
                    </div>
                </li>
                <li>
                    <span>02</span>
                    <div>
                        <strong>Đơn hàng</strong>
                        <small>Lịch sử giao dịch</small>
                    </div>
                </li>
                <li>
                    <span>03</span>
                    <div>
                        <strong>Source code</strong>
                        <small>Sản phẩm đã mua</small>
                    </div>
                </li>
            </ol>
        </header>

        <div class="auth-access__form-panel reveal" data-reveal-delay="100">
            <div class="auth-access__form-head">
                <span>Secure access</span>
                <span aria-hidden="true">NQT / AUTH</span>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="auth-form">
                @csrf

                <div class="auth-form__field">
                    <label for="email">Email</label>
                    <div class="auth-form__control">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.57 5.274a2.25 2.25 0 0 1-2.36 0L2.25 6.75" />
                        </svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="form-input"
                            placeholder="email@cuaban.com"
                            autocomplete="email"
                            inputmode="email"
                            @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                        >
                    </div>
                    <div class="auth-form__message" aria-live="polite">
                        @error('email')
                            <p id="email-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="auth-form__field">
                    <label for="password">Mật khẩu</label>
                    <div class="auth-form__control auth-form__control--password">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 9h10.5A2.25 2.25 0 0 0 19.5 17.25v-4.5a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v4.5a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="form-input"
                            placeholder="Nhập mật khẩu"
                            autocomplete="current-password"
                            @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                        >
                        <button type="button" class="auth-form__password-toggle" data-password-toggle="password" aria-label="Hiện mật khẩu" aria-pressed="false">
                            <svg class="auth-form__eye auth-form__eye--show" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg class="auth-form__eye auth-form__eye--hide" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 4.9A10 10 0 0 1 12 4.7c6 0 9.75 7.3 9.75 7.3a17 17 0 0 1-2.2 3.2M6.1 6.1C3.7 7.8 2.25 12 2.25 12S6 19.3 12 19.3a9.8 9.8 0 0 0 3.1-.5" />
                            </svg>
                        </button>
                    </div>
                    <div class="auth-form__message" aria-live="polite">
                        @error('password')
                            <p id="password-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="auth-form__options">
                    <label class="auth-form__remember">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        <span aria-hidden="true"></span>
                        Ghi nhớ đăng nhập
                    </label>
                </div>

                <button type="submit" class="auth-form__submit btn-primary">
                    <span>Đăng nhập</span>
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>

            <div class="auth-access__register">
                <span>Chưa có tài khoản?</span>
                <a href="{{ route('register') }}">Tạo tài khoản</a>
            </div>
        </div>
    </div>
</section>
@endsection
