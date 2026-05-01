<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Project categories
            ['name' => 'Web Application', 'slug' => 'web-application', 'type' => 'project', 'icon' => 'globe', 'sort_order' => 1],
            ['name' => 'Mobile App', 'slug' => 'mobile-app', 'type' => 'project', 'icon' => 'smartphone', 'sort_order' => 2],
            ['name' => 'API / Backend', 'slug' => 'api-backend', 'type' => 'project', 'icon' => 'server', 'sort_order' => 3],
            ['name' => 'WordPress', 'slug' => 'wordpress-project', 'type' => 'project', 'icon' => 'layout', 'sort_order' => 4],
            ['name' => 'Tool / Script', 'slug' => 'tool-script', 'type' => 'project', 'icon' => 'terminal', 'sort_order' => 5],

            // Product categories
            ['name' => 'Laravel Package', 'slug' => 'laravel-package', 'type' => 'product', 'icon' => 'package', 'sort_order' => 1],
            ['name' => 'WordPress Theme', 'slug' => 'wordpress-theme', 'type' => 'product', 'icon' => 'palette', 'sort_order' => 2],
            ['name' => 'WordPress Plugin', 'slug' => 'wordpress-plugin', 'type' => 'product', 'icon' => 'puzzle', 'sort_order' => 3],
            ['name' => 'Full Website', 'slug' => 'full-website', 'type' => 'product', 'icon' => 'monitor', 'sort_order' => 4],
            ['name' => 'UI Template', 'slug' => 'ui-template', 'type' => 'product', 'icon' => 'layers', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
