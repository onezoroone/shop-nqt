<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $productIds = array_keys($cart);

        $products = Product::select('id', 'title', 'slug', 'thumbnail', 'price', 'sale_price')
            ->whereIn('id', $productIds)
            ->published()
            ->get()
            ->keyBy('id');

        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            if ($products->has($productId)) {
                $product = $products[$productId];
                $itemPrice = $product->isOnSale() ? (float) $product->sale_price : (float) $product->price;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $itemPrice * $quantity,
                ];
                $total += $itemPrice * $quantity;
            }
        }

        return view('cart.index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $cart = session()->get('cart', []);
        $productId = $product->id;

        if (isset($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }

        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Added to cart',
                'cartCount' => array_sum($cart),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart = session()->get('cart', []);
        $cart[$product->id] = $quantity;
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove(Product $product): RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

    public function count(): JsonResponse
    {
        $cart = session()->get('cart', []);

        return response()->json(['count' => array_sum($cart)]);
    }
}
