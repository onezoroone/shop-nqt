@props([
    'settings',
])

@php
    $telegramUrl = $settings('telegram_url', 'https://t.me/nqtdev');
@endphp

<section {{ $attributes->merge(['class' => 'store-section store-terminal-cta']) }} aria-labelledby="home-cta-title">
    <div class="home-container">
        <div class="store-terminal-panel spotlight-card reveal" data-reveal-delay="80">
            <div class="store-terminal-panel__chrome" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
                <strong>checkout_support.sh</strong>
            </div>

            <div class="store-terminal-panel__body">
                <div>
                    <span class="store-section-kicker">Launch Ready</span>
                    <h2 id="home-cta-title" class="store-cta-title text-split">Cần một bộ code bán được hoặc build riêng cho sản phẩm của bạn?</h2>
                    <p>
                        Chọn sản phẩm trong store hoặc gửi brief. Tôi sẽ giúp bạn tinh chỉnh giao diện, luồng mua hàng, tích hợp thanh toán và tối ưu vận hành.
                    </p>
                </div>

                <div class="store-terminal-lines" aria-hidden="true">
                    <span><i>$</i> verify product-fit</span>
                    <span><i>$</i> customize checkout-flow</span>
                    <span><i>$</i> deploy production-ready</span>
                </div>
            </div>

            <div class="home-cta-actions">
                <a href="{{ route('contact.create') }}" class="home-button home-button--primary home-button--large" id="cta-contact-btn">
                    <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    Gửi Brief
                </a>

                <a href="{{ route('products.index') }}" class="home-button home-button--secondary home-button--large" id="cta-shop-btn">
                    Khám Phá Sản Phẩm
                </a>

                <a href="{{ $telegramUrl }}" target="_blank" rel="noopener" class="home-button home-button--ghost home-button--large">
                    Telegram
                </a>
            </div>
        </div>
    </div>
</section>
