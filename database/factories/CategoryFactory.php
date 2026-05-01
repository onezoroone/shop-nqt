<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'type' => fake()->randomElement(['project', 'product']),
            'icon' => null,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function project(): static
    {
        return $this->state(fn () => ['type' => 'project']);
    }

    public function product(): static
    {
        return $this->state(fn () => ['type' => 'product']);
    }
}
