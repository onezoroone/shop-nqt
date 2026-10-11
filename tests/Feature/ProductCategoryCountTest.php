<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductCategoryCountTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_catalog_counts_published_products_in_every_pivot_category_without_n_plus_one_queries(): void
    {
        $categories = Category::factory()->product()->count(5)->create();
        $published = Product::factory()->create(['category_id' => $categories[0]->id]);
        $published->categories()->sync($categories->take(2)->modelKeys());
        $draft = Product::factory()->draft()->create(['category_id' => $categories[0]->id]);
        $draft->categories()->sync($categories->take(2)->modelKeys());
        Cache::forget('product_categories');
        DB::enableQueryLog();
        DB::flushQueryLog();

        $response = $this->get(route('products.index'))->assertOk();
        $counts = $response->viewData('categories')->pluck('products_count', 'id');

        $this->assertEquals(1, $counts[$categories[0]->id]);
        $this->assertEquals(1, $counts[$categories[1]->id]);
        $this->assertEquals(0, $counts[$categories[2]->id]);
        $countQueries = collect(DB::getQueryLog())->filter(fn (array $query): bool => str_contains($query['query'], 'category_product') && str_contains($query['query'], 'count(*)'));
        $this->assertCount(1, $countQueries);
        DB::disableQueryLog();

        $this->get(route('products.index', ['category' => $categories[1]->slug]))
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee($draft->title);
    }
}
