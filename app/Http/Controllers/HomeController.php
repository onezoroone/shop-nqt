<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
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
                ->with('category:id,name,slug')
                ->published()
                ->featured()
                ->ordered()
                ->limit(4)
                ->get();
        });

        $skills = Cache::remember('all_skills', 3600, function () {
            return Skill::ordered()->get()->groupBy('category');
        });

        return view('home', [
            'featuredProjects' => $featuredProjects,
            'featuredProducts' => $featuredProducts,
            'skills' => $skills,
            'settings' => fn (string $key, mixed $default = null) => Setting::getValue($key, $default),
        ]);
    }
}
