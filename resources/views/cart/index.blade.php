@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
@php
    $cartItemCount = array_sum(array_column($cartItems, 'quantity'));
@endphp

<section class="store-view store-cart-view cart-workbench">
    <div class="cart-shell">
        <header class="cart-masthead reveal">
            <div class="cart-masthead__copy">
                <p class="cart-kicker">Checkout workspace</p>
                <h1 class="section-heading">Giỏ hàng<span aria-hidden="true">.</span></h1>
                <p class="cart-intro">
                    Kiểm tra sản phẩm, phiên bản và số lượng trước khi tạo đơn hàng.
                </p>
            </div>

            <ol class="cart-progress" aria-label="Tiến trình đặt hàng">
                <li class="is-current" aria-current="step">
                    <span>01</span>
                    <strong>Giỏ hàng</strong>
                </li>
                <li>
                    <span>02</span>
                    <strong>Xác nhận</strong>
                </li>
                <li>
                    <span>03</span>
                    <strong>Hoàn tất</strong>
                </li>
            </ol>
        </header>

        @if ($errors->any())
            <div class="cart-notice cart-notice--error reveal" role="alert">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L10.052 3.38c.866-1.5 3.03-1.5 3.896 0l7.355 12.746ZM12 16.5h.008v.008H12V16.5Z" />
                </svg>
                <div>
                    <strong>Chưa thể tiếp tục</strong>
                    <p>{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="cart-notice cart-notice--success reveal" role="status">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                <div>
                    <strong>Giỏ hàng đã cập nhật</strong>
                    <p>Thay đổi đã được lưu và tổng đơn hàng đã được tính lại.</p>
                </div>
            </div>
        @endif

        @if ($cartItemCount > 0)
            <div class="cart-layout" id="cart-content">
                <div class="cart-lines reveal">
                    <div class="cart-lines__head">
                        <div>
                            <p class="cart-label">Danh sách sản phẩm</p>
                            <h2>{{ $cartItemCount }} sản phẩm trong phiên này</h2>
                        </div>
                        <a href="{{ route('products.index') }}" class="cart-text-link">
                            Thêm sản phẩm
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>
                        </a>
                    </div>

                    <div class="cart-line-list">
                        @foreach ($cartItems as $index => $item)
                            <article class="cart-line reveal" data-reveal-delay="{{ $index * 50 }}" id="cart-item-{{ $item['cart_key'] }}">
                                <a href="{{ route('products.show', $item['product']) }}" class="cart-line__media" tabindex="-1" aria-hidden="true">
                                    @if ($item['product']->thumbnail)
                                        <img src="{{ $item['product']->thumbnail_url }}" alt="" loading="lazy">
                                    @else
                                        <svg viewBox="0 0 80 64" fill="none" aria-hidden="true">
                                            <path d="M13 17.5h54M13 32h54M13 46.5h34" stroke="currentColor" stroke-width="1.5" />
                                            <path d="M55 42.5h12v8H55z" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    @endif
                                </a>

                                <div class="cart-line__body">
                                    <p class="cart-line__number">Line {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</p>
                                    <a href="{{ route('products.show', $item['product']) }}" class="cart-line__title">
                                        {{ $item['product']->title }}
                                    </a>
                                    @if ($item['variant'])
                                        <p class="cart-line__variant">Phiên bản / {{ $item['variant']->name }}</p>
                                    @else
                                        <p class="cart-line__variant">Bản tiêu chuẩn</p>
                                    @endif
                                </div>

                                <div class="cart-line__quantity">
                                    <span class="cart-label">Số lượng</span>
                                    <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="cart-quantity-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="cart_key" value="{{ $item['cart_key'] }}">
                                        <label for="cart-quantity-{{ $loop->index }}" class="sr-only">Số lượng {{ $item['product']->title }}</label>
                                        <input id="cart-quantity-{{ $loop->index }}" type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99" inputmode="numeric">
                                        <button type="submit">Cập nhật</button>
                                    </form>
                                </div>

                                <div class="cart-line__price">
                                    <span class="cart-label">Tạm tính</span>
                                    <strong>${{ number_format($item['subtotal'], 2) }}</strong>
                                </div>

                                <form action="{{ route('cart.remove', $item['product']) }}" method="POST" class="cart-line__remove">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="cart_key" value="{{ $item['cart_key'] }}">
                                    <button type="submit" title="Xóa khỏi giỏ hàng" aria-label="Xóa {{ $item['product']->title }} khỏi giỏ hàng">
                                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </article>
                        @endforeach
                    </div>
                </div>

                <aside class="cart-summary reveal" aria-labelledby="cart-summary-title">
                    <div class="cart-summary__status">
                        <span class="cart-summary__status-dot" aria-hidden="true"></span>
                        Sẵn sàng tạo đơn
                    </div>

                    <h2 id="cart-summary-title">Tóm tắt đơn hàng</h2>

                    <dl class="cart-summary__ledger">
                        <div>
                            <dt>Sản phẩm</dt>
                            <dd>{{ $cartItemCount }}</dd>
                        </div>
                        <div>
                            <dt>Phí xử lý</dt>
                            <dd>$0.00</dd>
                        </div>
                        <div class="cart-summary__total">
                            <dt>Tổng cộng</dt>
                            <dd>${{ number_format($total, 2) }}</dd>
                        </div>
                    </dl>

                    <p class="cart-summary__note">
                        Đơn hàng sẽ được ghi nhận trong tài khoản của bạn sau khi xác nhận.
                    </p>

                    <form action="{{ route('checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="cart-primary-action" id="checkout-btn">
                            Tiến hành đặt hàng
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>
                        </button>
                    </form>

                    <a href="{{ route('products.index') }}" class="cart-secondary-action">
                        Tiếp tục mua sắm
                    </a>
                </aside>
            </div>
        @else
            <div class="cart-empty reveal">
                <div class="cart-empty__copy">
                    <p class="cart-label">Cart status / 00</p>
                    <h2>Chưa có sản phẩm trong giỏ.</h2>
                    <p>
                        Chọn source code, theme hoặc module phù hợp. Sản phẩm bạn thêm sẽ xuất hiện ở đây để kiểm tra trước khi đặt hàng.
                    </p>
                    <a href="{{ route('products.index') }}" class="cart-primary-action" id="browse-products-btn">
                        Khám phá sản phẩm
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
                        </svg>
                    </a>
                </div>

                <div class="cart-empty__diagram" aria-hidden="true">
                    <div class="cart-empty__signal">
                        <span>00</span>
                        <strong>EMPTY QUEUE</strong>
                    </div>
                    <svg class="cart-empty__icon" viewBox="0 0 160 160" fill="none">
                        <path d="M34 38h11l9 55h65l13-41H49" stroke="currentColor" stroke-width="2" />
                        <path d="M59 109h65" stroke="currentColor" stroke-width="2" />
                        <circle cx="67" cy="126" r="6" stroke="currentColor" stroke-width="2" />
                        <circle cx="116" cy="126" r="6" stroke="currentColor" stroke-width="2" />
                        <path d="M80 66h30M95 51v30" stroke="currentColor" stroke-width="2" />
                    </svg>
                    <div class="cart-empty__route">
                        <span class="is-active">Chọn sản phẩm</span>
                        <span>Kiểm tra giỏ</span>
                        <span>Tạo đơn hàng</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
