<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productCategories = Category::where('type', 'product')->pluck('id', 'slug');

        $products = [
            [
                'category_id' => $productCategories['wordpress-theme'],
                'title' => 'PhimVsub — Movie Streaming Theme',
                'slug' => 'phimvsub-movie-streaming-theme',
                'excerpt' => 'A premium WordPress theme for movie streaming websites with HLS support, responsive design, and SEO optimization.',
                'description' => '<h2>PhimVsub Movie Theme</h2><p>A complete movie streaming theme built from the ground up for performance and user experience.</p><h2>Features</h2><ul><li>HLS video player with adaptive bitrate</li><li>Advanced movie filtering (genre, year, country)</li><li>SEO-optimized with structured data</li><li>Responsive design for all devices</li><li>Admin dashboard for content management</li><li>Automated poster & backdrop fetching</li></ul><h2>Requirements</h2><p>WordPress 6.0+, PHP 8.1+</p>',
                'price' => 49.99,
                'sale_price' => 29.99,
                'tech_stack' => ['WordPress', 'PHP', 'JavaScript', 'CSS'],
                'demo_url' => 'https://demo.example.com/phimvsub',
                'features' => ['Responsive Design', 'SEO Optimized', 'HLS Player', 'Admin Panel', 'Auto Poster Fetch', 'Free Updates'],
                'is_featured' => true,
                'download_count' => 156,
                'status' => 'published',
                'published_at' => now()->subMonths(2),
            ],
            [
                'category_id' => $productCategories['wordpress-theme'],
                'title' => 'MangaTruyen — Comic Reader Theme',
                'slug' => 'mangatruyen-comic-reader-theme',
                'excerpt' => 'Feature-rich WordPress theme for manga and comic reading websites with chapter management and reading progress.',
                'description' => '<h2>MangaTruyen Reader Theme</h2><p>The ultimate comic and manga reading theme with smooth reading experience and powerful admin tools.</p><h2>Features</h2><ul><li>Chapter management with bulk upload</li><li>Reading progress tracking</li><li>Keyboard shortcuts for navigation</li><li>Image lazy loading for performance</li><li>Custom taxonomy for genres and authors</li><li>Bookmark system</li></ul>',
                'price' => 39.99,
                'tech_stack' => ['WordPress', 'PHP', 'JavaScript', 'jQuery'],
                'demo_url' => 'https://demo.example.com/mangatruyen',
                'features' => ['Chapter Management', 'Progress Tracking', 'Keyboard Navigation', 'Lazy Loading', 'Bookmarks', 'Well Documented'],
                'is_featured' => true,
                'download_count' => 89,
                'status' => 'published',
                'published_at' => now()->subMonth(),
            ],
            [
                'category_id' => $productCategories['laravel-package'],
                'title' => 'Content Crawler Pro',
                'slug' => 'content-crawler-pro',
                'excerpt' => 'Laravel package for automated content crawling with proxy support, rate limiting, and multi-source adapters.',
                'description' => '<h2>Content Crawler Pro</h2><p>A powerful Laravel package that makes content crawling easy with built-in proxy rotation and rate limiting.</p><h2>Features</h2><ul><li>Multi-source adapter system</li><li>Proxy rotation with auto health check</li><li>Configurable rate limiting</li><li>Queue integration for background processing</li><li>Content deduplication engine</li><li>Comprehensive logging</li></ul>',
                'price' => 79.99,
                'sale_price' => 59.99,
                'tech_stack' => ['Laravel', 'PHP', 'Redis'],
                'features' => ['Proxy Support', 'Rate Limiting', 'Queue Integration', 'Multi-source', 'Auto Health Check', 'Free Updates'],
                'is_featured' => true,
                'download_count' => 234,
                'status' => 'published',
                'published_at' => now()->subMonths(3),
            ],
            [
                'category_id' => $productCategories['full-website'],
                'title' => 'E-Commerce Starter Kit',
                'slug' => 'e-commerce-starter-kit',
                'excerpt' => 'Complete e-commerce solution built with Laravel and Vue.js. Ready to deploy with payment integration and admin dashboard.',
                'description' => '<h2>E-Commerce Starter Kit</h2><p>Skip months of development with this production-ready e-commerce platform.</p><h2>What You Get</h2><ul><li>Full Laravel backend with REST API</li><li>Vue.js SPA frontend</li><li>Stripe & PayPal integration</li><li>Admin dashboard with analytics</li><li>Inventory management system</li><li>Email notification templates</li></ul>',
                'price' => 199.99,
                'sale_price' => 149.99,
                'tech_stack' => ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'TailwindCSS'],
                'demo_url' => 'https://demo.example.com/ecommerce-kit',
                'features' => ['Payment Integration', 'Admin Dashboard', 'REST API', 'Email Templates', 'Inventory System', 'Well Documented', 'Free Updates'],
                'is_featured' => true,
                'download_count' => 67,
                'status' => 'published',
                'published_at' => now()->subWeeks(3),
            ],
            [
                'category_id' => $productCategories['ui-template'],
                'title' => 'Dark Admin Dashboard',
                'slug' => 'dark-admin-dashboard',
                'excerpt' => 'A sleek dark-themed admin dashboard template with charts, tables, and responsive components built with TailwindCSS.',
                'description' => '<h2>Dark Admin Dashboard</h2><p>A modern, dark-themed admin dashboard template perfect for SaaS applications and management panels.</p><h2>Components</h2><ul><li>Dashboard with charts and stats</li><li>Data tables with sorting and pagination</li><li>Form components with validation</li><li>User management pages</li><li>Settings panels</li><li>Login and registration pages</li></ul>',
                'price' => 24.99,
                'tech_stack' => ['HTML', 'TailwindCSS', 'JavaScript', 'Chart.js'],
                'demo_url' => 'https://demo.example.com/dark-dashboard',
                'features' => ['Dark Mode', 'Responsive Design', 'Chart Components', 'Form Validation', 'Data Tables', 'Well Documented'],
                'is_featured' => false,
                'download_count' => 312,
                'status' => 'published',
                'published_at' => now()->subMonths(4),
            ],
            [
                'category_id' => $productCategories['wordpress-plugin'],
                'title' => 'Smart Content Importer',
                'slug' => 'smart-content-importer',
                'excerpt' => 'WordPress plugin for intelligent content importing with ChatGPT-powered rewriting, image optimization, and scheduling.',
                'description' => '<h2>Smart Content Importer</h2><p>Automate your content workflow with AI-powered importing and rewriting.</p><h2>Features</h2><ul><li>Multi-source content importing</li><li>ChatGPT-powered content rewriting</li><li>Automatic image optimization</li><li>Scheduled imports with cron</li><li>Category auto-mapping</li><li>Duplicate detection</li></ul>',
                'price' => 34.99,
                'tech_stack' => ['WordPress', 'PHP', 'OpenAI API'],
                'features' => ['AI Rewriting', 'Image Optimization', 'Scheduling', 'Duplicate Detection', 'Auto Categorization', 'Free Updates'],
                'is_featured' => false,
                'download_count' => 178,
                'status' => 'published',
                'published_at' => now()->subMonths(2),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
