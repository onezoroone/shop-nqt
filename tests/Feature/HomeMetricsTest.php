<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class HomeMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_metrics_count_all_published_content_and_unique_technologies(): void
    {
        Cache::flush();

        Product::factory()->featured()->create([
            'tech_stack' => ['Laravel', ' PHP '],
        ]);
        Product::factory()->count(5)->create([
            'is_featured' => false,
            'tech_stack' => ['Vue.js', 'Laravel'],
        ]);
        Product::factory()->draft()->create([
            'tech_stack' => ['Draft only'],
        ]);

        Project::factory()->featured()->create([
            'tech_stack' => ['Redis', 'laravel'],
        ]);
        Project::factory()->count(4)->create([
            'is_featured' => false,
            'tech_stack' => ['PHP', 'Redis'],
        ]);
        Project::factory()->draft()->create([
            'tech_stack' => ['Draft only'],
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('6 products · 5 cases');
        $response->assertSeeInOrder([
            '<dt>Products</dt>',
            '<dd>6</dd>',
            '<dt>Cases</dt>',
            '<dd>5</dd>',
            '<dt>Stack</dt>',
            '<dd>4</dd>',
        ], false);
    }
}
