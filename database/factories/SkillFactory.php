<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'icon' => null,
            'proficiency' => fake()->numberBetween(40, 100),
            'category' => fake()->randomElement(['Frontend', 'Backend', 'DevOps', 'Tools']),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
