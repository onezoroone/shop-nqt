<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);

        // Collect all product IDs (cart keys are "product_id" or "product_id-variant_id")
        $productIds = [];
        $variantIds = [];
        foreach (array_keys($cart) as $key) {
            [$productId, $variantId] = $this->parseCartKey($key);
            $productIds[] = $productId;
            if ($variantId) {
                $variantIds[] = $variantId;
            }
        }

        $products = Product::select('id', 'title', 'slug', 'thumbnail', 'price', 'sale_price')
            ->whereIn('id', array_unique($productIds))
            ->published()
            ->get()
            ->keyBy('id');

        $variants = ProductVariant::whereIn('id', array_unique($variantIds))
            ->get()
            ->keyBy('id');

        $cartItems = [];
        $total = 0;

        foreach ($cart as $cartKey => $quantity) {
            [$productId, $variantId] = $this->parseCartKey($cartKey);

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

            $cartItems[] = [
                'cart_key' => $cartKey,
                'product' => $product,
                'variant' => $variant,
                'quantity' => $quantity,
                'subtotal' => $itemPrice * $quantity,
            ];
            $total += $itemPrice * $quantity;
        }

        return view('cart.index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $cart = session()->get('cart', []);
        $variantId = $request->input('variant_id');

        // Validate variant belongs to product if specified
        if ($variantId) {
            $variant = $product->variants()->find($variantId);
            if (! $variant) {
                $variantId = null;
            }
        }

        $cartKey = $this->buildCartKey($product->id, $variantId);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]++;
        } else {
            $cart[$cartKey] = 1;
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
        $cartKey = $request->input('cart_key', (string) $product->id);
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey] = $quantity;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        $cartKey = $request->input('cart_key', (string) $product->id);
        $cart = session()->get('cart', []);
        unset($cart[$cartKey]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

    public function count(): JsonResponse
    {
        $cart = session()->get('cart', []);

        return response()->json(['count' => array_sum($cart)]);
    }

    /**
     * Parse a cart key like "42" or "42-7" into [product_id, variant_id|null].
     *
     * @return array{0: int, 1: int|null}
     */
    private function parseCartKey(string $key): array
    {
        $parts = explode('-', $key, 2);

        return [(int) $parts[0], isset($parts[1]) ? (int) $parts[1] : null];
    }

    /**
     * Build a cart key from product ID and optional variant ID.
     */
    private function buildCartKey(int $productId, ?int $variantId): string
    {
        return $variantId ? "{$productId}-{$variantId}" : (string) $productId;
    }
}
