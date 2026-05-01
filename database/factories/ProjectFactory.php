<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'category_id' => Category::factory()->project(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(2),
            'description' => '<p>' . implode('</p><p>', fake()->paragraphs(5)) . '</p>',
            'thumbnail' => null,
            'tech_stack' => fake()->randomElements(
                ['Laravel', 'Vue.js', 'React', 'TailwindCSS', 'PHP', 'MySQL', 'Redis', 'Docker', 'TypeScript', 'Next.js'],
                fake()->numberBetween(2, 5)
            ),
            'demo_url' => fake()->optional(0.7)->url(),
            'source_url' => fake()->optional(0.5)->url(),
            'is_featured' => fake()->boolean(30),
            'sort_order' => fake()->numberBetween(0, 20),
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }
}
