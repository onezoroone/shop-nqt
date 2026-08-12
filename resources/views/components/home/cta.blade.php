@props([
    'settings',
])

@php
    $telegramUrl = $settings('telegram_url', 'https://t.me/nqtdev');
@endphp

<section {{ $attributes->merge(['class' => 'home-launch']) }} aria-labelledby="home-cta-title">
    <div class="home-container">
        <div class="home-launch__inner reveal" data-reveal-delay="60">
            <div class="home-launch__copy">
                <span>Ready to ship</span>
                <h2 id="home-cta-title">Cần một bộ code bán được hoặc build riêng cho sản phẩm của bạn?</h2>
                <p>Chọn sản phẩm trong store hoặc gửi brief. Tôi sẽ giúp bạn tinh chỉnh giao diện, luồng mua hàng, tích hợp thanh toán và tối ưu vận hành.</p>
            </div>

            <ol class="home-launch__steps" aria-label="Các bước triển khai">
                <li><span>01</span><strong>Chọn module phù hợp</strong></li>
                <li><span>02</span><strong>Tùy chỉnh checkout flow</strong></li>
                <li><span>03</span><strong>Deploy production-ready</strong></li>
            </ol>

            <div class="home-launch__actions">
                <a href="{{ route('contact.create') }}" class="home-launch__primary" id="cta-contact-btn">
                    Gửi brief
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a href="{{ route('products.index') }}" id="cta-shop-btn">Khám phá sản phẩm</a>
                <a href="{{ $telegramUrl }}" target="_blank" rel="noopener">Telegram</a>
            </div>
        </div>
    </div>
</section>
