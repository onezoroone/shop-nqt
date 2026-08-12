@extends('layouts.app')

@section('title', 'Liên hệ')
@section('meta_description', 'Liên hệ — gửi tin nhắn cho tôi về dự án, hợp tác hoặc các câu hỏi.')

@section('content')
<section class="store-view contact-dispatch" aria-labelledby="contact-title">
    <div class="contact-dispatch__grid">
        <header class="contact-dispatch__intro reveal">
            <div class="contact-dispatch__heading">
                <span class="store-section-kicker">Project Brief</span>
                <h1 id="contact-title">Liên hệ <span>triển khai.</span></h1>
                <p>Gửi brief cho sản phẩm code, cửa hàng điện tử hoặc hệ thống cần build riêng.</p>
            </div>

            <div class="contact-channels" aria-label="Kênh liên hệ trực tiếp">
                <div class="contact-channels__head">
                    <span>Kênh trực tiếp</span>
                    <span>NQT / VN</span>
                </div>

                <a href="mailto:{{ $contactEmail }}" class="contact-channel">
                    <span class="contact-channel__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                    </span>
                    <span class="contact-channel__copy">
                        <small>Email</small>
                        <strong title="{{ $contactEmail }}">{{ $contactEmail }}</strong>
                    </span>
                    <svg class="contact-channel__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>

                <a href="{{ $telegramUrl }}" target="_blank" rel="noopener" class="contact-channel">
                    <span class="contact-channel__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0Zm5.894 8.221-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.223-.548.223l.188-2.85 5.18-4.686c.223-.195-.054-.282-.346-.088l-6.406 4.03-2.76-.864c-.6-.18-.61-.593.125-.88l10.814-4.17c.502-.18.948.113.805.823Z" />
                        </svg>
                    </span>
                    <span class="contact-channel__copy">
                        <small>Telegram</small>
                        <strong title="{{ $telegramUrl }}">{{ $telegramUrl }}</strong>
                    </span>
                    <svg class="contact-channel__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>

                <a href="{{ $githubUrl }}" target="_blank" rel="noopener" class="contact-channel">
                    <span class="contact-channel__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12Z" />
                        </svg>
                    </span>
                    <span class="contact-channel__copy">
                        <small>GitHub</small>
                        <strong title="{{ $githubUrl }}">{{ $githubUrl }}</strong>
                    </span>
                    <svg class="contact-channel__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>

                <div class="contact-channel contact-channel--static">
                    <span class="contact-channel__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </span>
                    <span class="contact-channel__copy">
                        <small>Địa điểm</small>
                        <strong>Việt Nam</strong>
                    </span>
                    <span class="contact-channel__status" aria-hidden="true"></span>
                </div>
            </div>
        </header>

        <div class="contact-dispatch__form-column">
            @if (session('success'))
                <div class="contact-success reveal" role="status" aria-live="polite">
                    <span class="contact-success__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 2.25 2.25L15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                    <span>
                        <strong>Tin nhắn đã được gửi!</strong>
                        <small>{{ session('success') }}</small>
                    </span>
                </div>
            @endif

            <div class="contact-form-panel reveal" data-reveal-delay="100">
                <div class="contact-form-panel__head">
                    <span>Project intake</span>
                    <span>Brief / 01</span>
                </div>

                <form action="{{ route('contact.store') }}" method="POST" id="contact-form" class="contact-form">
                    @csrf

                    <div class="contact-form__grid">
                        <div class="contact-form__field">
                            <label for="contact-name">Tên</label>
                            <input
                                type="text"
                                id="contact-name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                class="form-input"
                                placeholder="Tên của bạn"
                                autocomplete="name"
                                @error('name') aria-invalid="true" aria-describedby="contact-name-error" @enderror
                            >
                            <span class="contact-form__error" id="contact-name-error">
                                @error('name') {{ $message }} @enderror
                            </span>
                        </div>

                        <div class="contact-form__field">
                            <label for="contact-email">Email</label>
                            <input
                                type="email"
                                id="contact-email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                class="form-input"
                                placeholder="email@cuaban.com"
                                autocomplete="email"
                                @error('email') aria-invalid="true" aria-describedby="contact-email-error" @enderror
                            >
                            <span class="contact-form__error" id="contact-email-error">
                                @error('email') {{ $message }} @enderror
                            </span>
                        </div>

                        <div class="contact-form__field contact-form__field--wide">
                            <label for="contact-subject">Tiêu đề</label>
                            <input
                                type="text"
                                id="contact-subject"
                                name="subject"
                                value="{{ old('subject') }}"
                                required
                                class="form-input"
                                placeholder="Hỏi đáp dự án, hợp tác, v.v."
                                autocomplete="off"
                                @error('subject') aria-invalid="true" aria-describedby="contact-subject-error" @enderror
                            >
                            <span class="contact-form__error" id="contact-subject-error">
                                @error('subject') {{ $message }} @enderror
                            </span>
                        </div>

                        <div class="contact-form__field contact-form__field--wide">
                            <label for="contact-message">Tin nhắn</label>
                            <textarea
                                id="contact-message"
                                name="message"
                                rows="7"
                                required
                                class="form-input resize-none"
                                placeholder="Hãy cho tôi biết về dự án của bạn..."
                                @error('message') aria-invalid="true" aria-describedby="contact-message-error" @enderror
                            >{{ old('message') }}</textarea>
                            <span class="contact-form__error" id="contact-message-error">
                                @error('message') {{ $message }} @enderror
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary contact-form__submit" id="contact-submit-btn">
                        <span>Gửi tin nhắn</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
