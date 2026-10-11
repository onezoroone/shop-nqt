<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_robots_has_an_absolute_sitemap_url(): void
    {
        $this->get(route('robots'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Sitemap: '.route('sitemap'), false);
    }

    public function test_sitemap_returns_xml_with_published_products(): void
    {
        $published = Product::factory()->create(['slug' => 'published-product']);
        Product::factory()->draft()->create(['slug' => 'draft-product']);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee(route('products.show', $published), false);
        $response->assertSee(route('home'), false);
        $response->assertSee(route('products.index'), false);
        $response->assertDontSee(route('products.show', Product::where('slug', 'draft-product')->first()), false);
    }
}
