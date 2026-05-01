<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
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
