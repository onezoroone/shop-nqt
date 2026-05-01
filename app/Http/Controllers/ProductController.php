<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categorySlug = $request->query('category');
        $sort = $request->query('sort', 'newest');
        $search = $request->query('search');

        $categories = Cache::remember('product_categories', 3600, function () {
            return Category::ofType('product')
                ->ordered()
                ->withCount(['products' => fn ($q) => $q->published()])
                ->get();
        });

        $products = Product::select('id', 'category_id', 'title', 'slug', 'excerpt', 'thumbnail', 'price', 'sale_price', 'tech_stack', 'download_count', 'published_at')
            ->with('category:id,name,slug')
            ->published()
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->when($sort === 'price_low', fn ($q) => $q->orderBy('price'))
            ->when($sort === 'price_high', fn ($q) => $q->orderByDesc('price'))
            ->when($sort === 'popular', fn ($q) => $q->orderByDesc('download_count'))
            ->when($sort === 'newest' || ! in_array($sort, ['price_low', 'price_high', 'popular']), fn ($q) => $q->ordered())
            ->paginate(12);

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $categorySlug,
            'currentSort' => $sort,
            'search' => $search,
        ]);
    }

    public function show(Product $product): View
    {
        $product->load(['category:id,name,slug', 'images']);

        $relatedProducts = Product::select('id', 'category_id', 'title', 'slug', 'excerpt', 'thumbnail', 'price', 'sale_price', 'download_count')
            ->with('category:id,name,slug')
            ->published()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->ordered()
            ->limit(3)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
