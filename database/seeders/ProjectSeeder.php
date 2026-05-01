<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projectCategories = Category::where('type', 'project')->pluck('id', 'slug');

        $projects = [
            [
                'category_id' => $projectCategories['web-application'],
                'title' => 'E-Commerce Platform',
                'slug' => 'e-commerce-platform',
                'excerpt' => 'A full-featured e-commerce platform built with Laravel and Vue.js, supporting multiple payment gateways, inventory management, and real-time order tracking.',
                'description' => '<h2>Overview</h2><p>This e-commerce platform handles everything from product management to payment processing. Built with a focus on performance and scalability.</p><h2>Key Features</h2><ul><li>Multi-vendor support with commission system</li><li>Real-time inventory tracking</li><li>Integration with Stripe and PayPal</li><li>Advanced product search with Elasticsearch</li><li>Mobile-responsive dashboard</li></ul><h2>Technical Highlights</h2><p>Uses Laravel queues for order processing, Redis caching for product catalog, and WebSocket for real-time notifications.</p>',
                'tech_stack' => ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'TailwindCSS', 'Stripe API'],
                'demo_url' => 'https://demo.example.com/ecommerce',
                'source_url' => 'https://github.com/nqt/ecommerce',
                'is_featured' => true,
                'sort_order' => 1,
                'status' => 'published',
                'published_at' => now()->subMonths(2),
            ],
            [
                'category_id' => $projectCategories['web-application'],
                'title' => 'Movie Streaming Portal',
                'slug' => 'movie-streaming-portal',
                'excerpt' => 'A high-performance movie streaming website with HLS video support, automated content crawling, and beautiful responsive UI.',
                'description' => '<h2>Overview</h2><p>Built as a comprehensive movie streaming platform with support for multiple video sources and automated content management.</p><h2>Features</h2><ul><li>HLS streaming with adaptive bitrate</li><li>Automated crawler for content aggregation</li><li>User watchlist and history</li><li>Advanced filtering by genre, year, country</li><li>SEO-optimized with structured data</li></ul>',
                'tech_stack' => ['Laravel', 'PHP', 'MySQL', 'FFmpeg', 'TailwindCSS', 'JavaScript'],
                'demo_url' => 'https://demo.example.com/movies',
                'is_featured' => true,
                'sort_order' => 2,
                'status' => 'published',
                'published_at' => now()->subMonths(3),
            ],
            [
                'category_id' => $projectCategories['wordpress-project'],
                'title' => 'MangaTruyen Reader',
                'slug' => 'mangatruyen-reader',
                'excerpt' => 'Custom WordPress theme for manga/comic reading with chapter management, reading progress tracking, and optimized image loading.',
                'description' => '<h2>Overview</h2><p>A custom WordPress theme designed specifically for manga reading websites with focus on reading experience and performance.</p><h2>Features</h2><ul><li>Chapter management system</li><li>Keyboard navigation support</li><li>Lazy loading for images</li><li>Reading progress tracking</li><li>Responsive design for mobile reading</li></ul>',
                'tech_stack' => ['WordPress', 'PHP', 'JavaScript', 'CSS', 'MySQL'],
                'demo_url' => 'https://demo.example.com/manga',
                'source_url' => 'https://github.com/nqt/mangatruyen',
                'is_featured' => true,
                'sort_order' => 3,
                'status' => 'published',
                'published_at' => now()->subMonth(),
            ],
            [
                'category_id' => $projectCategories['api-backend'],
                'title' => 'Content Crawler Engine',
                'slug' => 'content-crawler-engine',
                'excerpt' => 'Robust content crawling system with proxy rotation, rate limiting, and multi-source aggregation for automated content management.',
                'description' => '<h2>Overview</h2><p>An advanced content crawling engine built to handle multiple sources with intelligent rate limiting and proxy support.</p><h2>Features</h2><ul><li>Multi-source crawling with configurable adapters</li><li>Proxy rotation with health checking</li><li>Rate limiting and queue management</li><li>Content deduplication</li><li>Webhook notifications</li></ul>',
                'tech_stack' => ['Laravel', 'PHP', 'Redis', 'MySQL', 'Docker'],
                'is_featured' => false,
                'sort_order' => 4,
                'status' => 'published',
                'published_at' => now()->subMonths(4),
            ],
            [
                'category_id' => $projectCategories['tool-script'],
                'title' => 'Video Transcoder Bot',
                'slug' => 'video-transcoder-bot',
                'excerpt' => 'Automated video transcoding tool that converts videos to HLS format with multiple quality options and subtitle extraction.',
                'description' => '<h2>Overview</h2><p>A command-line tool for batch video transcoding with support for HLS output, subtitle extraction, and quality presets.</p><h2>Features</h2><ul><li>Batch video processing</li><li>HLS output with multiple quality levels</li><li>Automatic subtitle extraction</li><li>Progress tracking and notifications</li></ul>',
                'tech_stack' => ['PHP', 'FFmpeg', 'Laravel', 'Redis'],
                'is_featured' => false,
                'sort_order' => 5,
                'status' => 'published',
                'published_at' => now()->subMonths(5),
            ],
            [
                'category_id' => $projectCategories['web-application'],
                'title' => 'Portfolio & Shop Platform',
                'slug' => 'portfolio-shop-platform',
                'excerpt' => 'This very website! A combined portfolio and digital product shop built with Laravel 12 and TailwindCSS 4.',
                'description' => '<h2>Overview</h2><p>A modern portfolio website combined with a digital product shop. Features premium dark theme design with glassmorphism effects.</p><h2>Features</h2><ul><li>Portfolio showcase with project details</li><li>Digital product shop with cart</li><li>Session-based cart system</li><li>Contact form with validation</li><li>Responsive design</li><li>SEO optimized</li></ul>',
                'tech_stack' => ['Laravel 12', 'TailwindCSS 4', 'PHP 8.2', 'MySQL', 'Vite'],
                'demo_url' => '/',
                'source_url' => 'https://github.com/nqt/shop-nqt',
                'is_featured' => true,
                'sort_order' => 0,
                'status' => 'published',
                'published_at' => now(),
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
