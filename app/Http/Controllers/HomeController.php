<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        SEOTools::setTitle(Setting::getValue('seo_home_title', 'NQT Dev'));
        SEOTools::setDescription(Setting::getValue('seo_home_description', ''));
        SEOTools::metatags()->addKeyword(explode(',', Setting::getValue('seo_home_keywords', '')));
        SEOTools::setCanonical(route('home'));
        SEOTools::opengraph()->setUrl(route('home'));
        SEOTools::opengraph()->addProperty('type', 'website');
        SEOTools::addImages([Setting::getValue('seo_default_image', asset('assets/images/placeholder.jpg'))]);

        $featuredProjects = Cache::remember('featured_projects', 1800, function () {
            return Project::select('id', 'category_id', 'title', 'slug', 'excerpt', 'thumbnail', 'tech_stack', 'is_featured')
                ->with('category:id,name,slug')
                ->published()
                ->featured()
                ->ordered()
                ->limit(4)
                ->get();
        });

        $featuredProducts = Cache::remember('featured_products', 1800, function () {
            return Product::select('id', 'category_id', 'title', 'slug', 'excerpt', 'thumbnail', 'price', 'sale_price', 'tech_stack', 'is_featured', 'download_count')
                ->with(['categories:id,name,slug', 'variants'])
                ->published()
                ->featured()
                ->ordered()
                ->limit(4)
                ->get();
        });

        $homeMetrics = Cache::remember('home_metrics', 300, function (): array {
            $technologies = Product::published()
                ->pluck('tech_stack')
                ->merge(Project::published()->pluck('tech_stack'))
                ->flatten()
                ->filter(fn (mixed $technology): bool => is_string($technology) && filled(trim($technology)))
                ->map(fn (string $technology): string => trim($technology))
                ->unique(fn (string $technology): string => mb_strtolower($technology));

            return [
                'products' => Product::published()->count(),
                'projects' => Project::published()->count(),
                'technologies' => $technologies->count(),
            ];
        });

        $skills = Cache::remember('all_skills', 3600, function () {
            return Skill::ordered()->get()->groupBy('category');
        });

        return view('home', [
            'featuredProjects' => $featuredProjects,
            'featuredProducts' => $featuredProducts,
            'homeMetrics' => $homeMetrics,
            'skills' => $skills,
            'settings' => fn (string $key, mixed $default = null) => Setting::getValue($key, $default),
        ]);
    }
}
