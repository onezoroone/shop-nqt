<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'image_path' => 'products/screenshot-' . fake()->numberBetween(1, 10) . '.webp',
            'alt_text' => fake()->sentence(3),
            'sort_order' => fake()->numberBetween(0, 5),
        ];
    }
}
