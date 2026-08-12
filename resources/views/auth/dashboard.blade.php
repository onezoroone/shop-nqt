@extends('layouts.app')

@section('title', 'Tài khoản của tôi')

@section('content')
<section class="py-12 store-view store-account-view">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 reveal">
            <span class="store-section-kicker">Customer Console</span>
            <h1 class="section-heading text-white">Xin chào, <span class="store-title-mark">{{ auth()->user()->name }}</span></h1>
            <p class="text-gray-400">Quản lý tài khoản, đơn hàng và quyền truy cập sản phẩm số.</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <div class="glass-card p-6 reveal">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Tổng đơn hàng</p>
                        <p class="text-2xl font-black text-white">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6 reveal" data-reveal-delay="50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-warning/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Đang chờ duyệt</p>
                        <p class="text-2xl font-black text-warning">{{ $pendingOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6 reveal" data-reveal-delay="100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-success/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Đã thanh toán</p>
                        <p class="text-2xl font-black text-success">${{ number_format($totalSpent, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left: Profile --}}
            <div class="glass-card p-6 reveal" data-reveal-delay="150">
                <h3 class="text-lg font-bold text-white mb-6 border-b border-white/10 pb-4">Thông tin tài khoản</h3>
                <div class="space-y-4">
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wider">Họ tên</span>
                        <p class="text-white font-semibold">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wider">Email</span>
                        <p class="text-white font-semibold">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wider">Ngày tham gia</span>
                        <p class="text-white font-semibold">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Right: Recent Orders --}}
            <div class="lg:col-span-2 reveal" data-reveal-delay="200">
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-6 border-b border-white/10 pb-4">
                        <h3 class="text-lg font-bold text-white">Đơn hàng gần đây</h3>
                        @if ($orders->count() > 0)
                            <a href="{{ route('orders.index') }}" class="store-muted-link inline-flex items-center gap-1 text-sm transition-colors">
                                Xem tất cả
                                <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        @endif
                    </div>

                    @if ($orders->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">Bạn chưa có đơn hàng nào</p>
                            <a href="{{ route('products.index') }}" class="btn-primary text-sm">Mua sắm ngay</a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($orders as $order)
                                <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between p-4 rounded-xl bg-surface-dark/50 hover:bg-white/5 transition-colors group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
                                            #{{ $order->id }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-white group-hover:text-primary transition-colors">{{ $order->items->count() }} sản phẩm</p>
                                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-bold text-success">${{ number_format($order->total_amount, 2) }}</span>
                                        @if ($order->status === 'pending')
                                            <span class="w-2 h-2 rounded-full bg-warning"></span>
                                        @elseif ($order->status === 'paid' || $order->status === 'completed')
                                            <span class="w-2 h-2 rounded-full bg-success"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-danger"></span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
