<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Giỏ hàng của bạn đang trống!']);
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $total = 0;
        $orderItems = [];

        foreach ($cart as $productId => $quantity) {
            if ($products->has($productId)) {
                $product = $products[$productId];
                $itemPrice = $product->isOnSale() ? (float) $product->sale_price : (float) $product->price;
                $subtotal = $itemPrice * $quantity;
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $productId,
                    'price' => $itemPrice,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }
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

    public function show(Order $order)
    {
        // Must belong to auth user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('orders.index', compact('orders'));
    }
}
