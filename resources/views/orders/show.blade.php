@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<section class="py-12 store-view store-order-detail-view">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 reveal flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="section-heading text-white mb-0">Đơn hàng #{{ $order->id }}</h1>
            <a href="{{ route('orders.index') }}" class="store-muted-link inline-flex items-center gap-2 transition-colors">
                <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Trở lại danh sách
            </a>
        </div>

        @if (session('success'))
            <div class="glass-card p-4 mb-6 border-success/30 text-success text-sm reveal">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card p-6 reveal">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-4">Sản phẩm đã đặt</h3>
                    <div class="space-y-4">
                        @foreach ($order->items as $item)
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-12 rounded bg-surface-dark flex-shrink-0">
                                    @if ($item->product->thumbnail)
                                        <img src="{{$item->product->thumbnail_url}}" alt="{{ $item->product->title }}" class="w-full h-full object-cover rounded">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <a href="{{ route('products.show', $item->product) }}" class="text-sm font-semibold text-white hover:text-primary transition-colors">
                                        {{ $item->product->title }}
                                    </a>
                                    @if ($item->variant_name)
                                        <span class="text-xs text-accent ml-1">— {{ $item->variant_name }}</span>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">${{ number_format($item->price, 2) }} x {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-white">${{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            </div>
                            @php
                                $downloadUrl = $item->variant?->source_url ?? $item->product->source_url;
                            @endphp
                            @if (($order->status === 'paid' || $order->status === 'completed') && !empty($downloadUrl))
                                <div class="mt-2 ml-20">
                                    <a href="{{ $downloadUrl }}" target="_blank" rel="noopener" class="inline-flex items-center text-sm font-medium text-success hover:text-success/80 transition-colors bg-success/10 px-3 py-1.5 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Tải Source Code{{ $item->variant_name ? ' (' . $item->variant_name . ')' : '' }}
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/10 flex justify-between items-center">
                        <span class="text-gray-300 font-semibold">Tổng cộng:</span>
                        <span class="text-2xl font-black text-success">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="glass-card p-6 reveal" data-reveal-delay="100">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-4">Trạng thái</h3>
                    <div class="mb-2">
                        <span class="text-gray-400 text-sm">Trạng thái thanh toán:</span>
                        @if ($order->status === 'pending')
                            <span class="store-status-chip store-status-chip--warning ml-2">Chờ thanh toán</span>
                        @elseif ($order->status === 'paid')
                            <span class="store-status-chip store-status-chip--featured ml-2">Đã thanh toán</span>
                        @elseif ($order->status === 'completed')
                            <span class="store-status-chip store-status-chip--success ml-2">Đã hoàn thành</span>
                        @else
                            <span class="store-status-chip store-status-chip--danger ml-2">Đã hủy</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-gray-400 text-sm">Phương thức:</span>
                        <span class="ml-2 text-white uppercase">{{ $order->payment_method }}</span>
                    </div>
                </div>

                @if ($order->status === 'pending')
                    <div class="glass-card p-6 reveal border-warning/30" data-reveal-delay="200">
                        <h3 class="text-lg font-bold text-warning mb-3">Hướng dẫn thanh toán</h3>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-300 mb-2">1. Chuyển khoản USDT (TRC20)</p>
                            <p class="text-xs text-gray-400 mb-2">Chuyển chính xác <span class="text-success font-bold">${{ number_format($order->total_amount, 2) }}</span> vào ví:</p>
                            <label for="usdt-wallet" class="sr-only">Địa chỉ ví USDT</label>
                            <input type="text" value="{{ $usdtWalletAddress }}" class="form-input font-mono text-xs w-full bg-surface-dark text-gray-300 mb-2" readonly id="usdt-wallet">
                            <button type="button" data-copy-target="usdt-wallet" data-copy-label="Đã copy!" class="store-muted-link text-xs transition-colors">Sao chép địa chỉ ví</button>
                        </div>

                        <div class="border-t border-white/10 pt-4">
                            <p class="text-sm text-gray-300 mb-2">2. Xác nhận thanh toán</p>
                            <p class="text-xs text-gray-400 mb-3">Chụp lại hóa đơn và nhắn tin qua Telegram kèm mã đơn hàng <strong>#{{ $order->id }}</strong> để được duyệt.</p>
                            <a href="{{ $telegramUrl }}" target="_blank" rel="noopener" class="w-full btn-primary bg-[#0088cc] hover:bg-[#0088cc]/80 text-white justify-center py-2 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.223-.548.223l.188-2.85 5.18-4.686c.223-.195-.054-.282-.346-.088l-6.406 4.03-2.76-.864c-.6-.18-.61-.593.125-.88l10.814-4.17c.502-.18.948.113.805.823z"/></svg>
                                Nhắn tin Telegram
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
