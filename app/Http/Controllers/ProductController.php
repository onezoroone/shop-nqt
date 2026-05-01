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
        \Artesaos\SEOTools\Facades\SEOTools::setTitle(\App\Models\Setting::getValue('seo_products_title', 'Sản phẩm'));
        \Artesaos\SEOTools\Facades\SEOTools::setDescription(\App\Models\Setting::getValue('seo_products_description', ''));
        \Artesaos\SEOTools\Facades\SEOTools::metatags()->addKeyword(explode(',', \App\Models\Setting::getValue('seo_products_keywords', '')));
        \Artesaos\SEOTools\Facades\SEOTools::setCanonical(route('products.index'));
        \Artesaos\SEOTools\Facades\SEOTools::opengraph()->setUrl(route('products.index'));
        \Artesaos\SEOTools\Facades\SEOTools::opengraph()->addProperty('type', 'website');
        \Artesaos\SEOTools\Facades\SEOTools::addImages([\App\Models\Setting::getValue('seo_default_image', asset('assets/images/placeholder.jpg'))]);

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
            ->with('categories:id,name,slug')
            ->published()
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->whereHas('categories', fn ($q) => $q->where('slug', $categorySlug));
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
        \Artesaos\SEOTools\Facades\SEOTools::setTitle($product->meta_title ?: $product->title);
        \Artesaos\SEOTools\Facades\SEOTools::setDescription($product->meta_description ?: strip_tags($product->excerpt));
        \Artesaos\SEOTools\Facades\SEOTools::metatags()->addKeyword(explode(',', $product->meta_keywords ?? ''));
        \Artesaos\SEOTools\Facades\SEOTools::setCanonical(route('products.show', $product));
        \Artesaos\SEOTools\Facades\SEOTools::opengraph()->setUrl(route('products.show', $product));
        \Artesaos\SEOTools\Facades\SEOTools::opengraph()->addProperty('type', 'product');
        if ($product->thumbnail_url) {
            \Artesaos\SEOTools\Facades\SEOTools::addImages([$product->thumbnail_url]);
        }

        $product->load(['categories:id,name,slug']);

        $categoryIds = $product->categories->pluck('id');

        $relatedProducts = Product::select('id', 'category_id', 'title', 'slug', 'excerpt', 'thumbnail', 'price', 'sale_price', 'download_count')
            ->with('categories:id,name,slug')
            ->published()
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds))
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
