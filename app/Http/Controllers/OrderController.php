<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Giỏ hàng của bạn đang trống!']);
        }

        // Collect product and variant IDs
        $productIds = [];
        $variantIds = [];
        foreach (array_keys($cart) as $key) {
            $parts = explode('-', $key, 2);
            $productIds[] = (int) $parts[0];
            if (isset($parts[1])) {
                $variantIds[] = (int) $parts[1];
            }
        }

        $products = Product::whereIn('id', array_unique($productIds))->get()->keyBy('id');
        $variants = ProductVariant::whereIn('id', array_unique($variantIds))->get()->keyBy('id');

        $total = 0;
        $orderItems = [];

        foreach ($cart as $cartKey => $quantity) {
            $parts = explode('-', $cartKey, 2);
            $productId = (int) $parts[0];
            $variantId = isset($parts[1]) ? (int) $parts[1] : null;

            if (! $products->has($productId)) {
                continue;
            }

            $product = $products[$productId];
            $variant = $variantId ? ($variants[$variantId] ?? null) : null;

            if ($variant) {
                $itemPrice = $variant->isOnSale() ? (float) $variant->sale_price : (float) $variant->price;
            } else {
                $itemPrice = $product->isOnSale() ? (float) $product->sale_price : (float) $product->price;
            }

            $subtotal = $itemPrice * $quantity;
            $total += $subtotal;

            $orderItems[] = [
                'product_id' => $productId,
                'product_variant_id' => $variant?->id,
                'variant_name' => $variant?->name,
                'price' => $itemPrice,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        if ($total == 0) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Không có sản phẩm hợp lệ trong giỏ hàng.']);
        }

        $order = DB::transaction(function () use ($total, $orderItems) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => 'usdt',
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            return $order;
        });

        // Clear cart
        session()->forget('cart');

        return redirect()->route('orders.show', $order)->with('success', 'Đơn hàng đã được tạo thành công! Vui lòng thanh toán.');
    }

    public function show(Order $order): View
    {
        // Must belong to auth user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $this->configureNoIndexSeo(
            title: "Chi tiết đơn hàng #{$order->id} - NQT Dev",
            description: 'Kiểm tra sản phẩm, trạng thái và thông tin thanh toán của đơn hàng tại NQT Dev.',
            canonicalUrl: route('orders.show', $order)
        );

        return view('orders.show', [
            'order' => $order,
            'telegramUrl' => Setting::getValue('telegram_url', 'https://t.me/nqtdev'),
            'usdtWalletAddress' => Setting::getValue('usdt_wallet_address', 'TRC20: ...'),
        ]);
    }

    public function index(): View
    {
        $this->configureNoIndexSeo(
            title: 'Đơn hàng của tôi - NQT Dev',
            description: 'Theo dõi danh sách, trạng thái và lịch sử đơn hàng trong tài khoản NQT Dev.',
            canonicalUrl: route('orders.index')
        );

        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('orders.index', compact('orders'));
    }
}
