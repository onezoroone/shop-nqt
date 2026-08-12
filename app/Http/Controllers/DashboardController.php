<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $this->configureNoIndexSeo(
            title: 'Tài khoản của tôi - NQT Dev',
            description: 'Quản lý thông tin tài khoản, đơn hàng và quyền truy cập sản phẩm số tại NQT Dev.',
            canonicalUrl: route('dashboard')
        );

        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->with('items')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->whereIn('status', ['paid', 'completed'])
            ->sum('total_amount');

        return view('auth.dashboard', compact('orders', 'totalOrders', 'pendingOrders', 'totalSpent'));
    }
}
