<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);
        $price = fake()->randomFloat(2, 9.99, 299.99);

        return [
            'category_id' => Category::factory()->product(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(2),
            'description' => '<p>' . implode('</p><p>', fake()->paragraphs(5)) . '</p>',
            'thumbnail' => null,
            'price' => $price,
            'sale_price' => fake()->optional(0.3)->randomFloat(2, 4.99, $price * 0.8),
            'tech_stack' => fake()->randomElements(
                ['Laravel', 'Vue.js', 'React', 'TailwindCSS', 'PHP', 'WordPress', 'Next.js', 'Node.js'],
                fake()->numberBetween(2, 4)
            ),
            'demo_url' => fake()->optional(0.8)->url(),
            'features' => fake()->randomElements(
                ['Responsive Design', 'SEO Optimized', 'Dark Mode', 'Multi-language', 'Admin Panel', 'API Ready', 'Well Documented', 'Free Updates'],
                fake()->numberBetween(3, 6)
            ),
            'is_featured' => fake()->boolean(25),
            'download_count' => fake()->numberBetween(0, 500),
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }

    public function onSale(): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_price' => round($attributes['price'] * 0.7, 2),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }
}
