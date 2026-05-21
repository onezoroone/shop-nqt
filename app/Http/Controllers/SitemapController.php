<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $products = Product::query()
            ->published()
            ->select(['slug', 'updated_at', 'published_at'])
            ->ordered()
            ->get();

        /** @var Collection<int, array{loc: string, lastmod: ?Carbon, changefreq: string, priority: string}> $staticPages */
        $staticPages = collect([
            [
                'loc' => route('home'),
                'lastmod' => null,
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
            [
                'loc' => route('products.index'),
                'lastmod' => $products->max('updated_at'),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
        ]);

        return response()
            ->view('sitemap', [
                'staticPages' => $staticPages,
                'products' => $products,
            ])
            ->header('Content-Type', 'text/xml; charset=UTF-8');
    }
}
