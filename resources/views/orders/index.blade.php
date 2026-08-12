@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<section class="py-12 store-view store-orders-view">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 reveal">
            <span class="store-section-kicker">Order Archive</span>
            <h1 class="section-heading text-white">Đơn hàng <span class="store-title-mark">của tôi</span></h1>
        </div>

        @if ($orders->isEmpty())
            <div class="glass-card p-12 text-center reveal">
                <p class="text-gray-400 mb-6">Bạn chưa có đơn hàng nào.</p>
                <a href="{{ route('products.index') }}" class="btn-primary">Mua sắm ngay</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="glass-card p-6 reveal flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-white">Đơn hàng #{{ $order->id }}</h3>
                            <p class="text-sm text-gray-400 mt-1">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-lg font-bold text-success">${{ number_format($order->total_amount, 2) }}</span>
                            @if ($order->status === 'pending')
                                <span class="store-status-chip store-status-chip--warning">Chờ thanh toán</span>
                            @elseif ($order->status === 'paid')
                                <span class="store-status-chip store-status-chip--featured">Đã thanh toán</span>
                            @elseif ($order->status === 'completed')
                                <span class="store-status-chip store-status-chip--success">Đã hoàn thành</span>
                            @else
                                <span class="store-status-chip store-status-chip--danger">Đã hủy</span>
                            @endif
                            <a href="{{ route('orders.show', $order) }}" class="btn-outline text-sm py-1.5 px-3">Chi tiết</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
