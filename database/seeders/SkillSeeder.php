<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // Backend
            ['name' => 'PHP', 'category' => 'Backend', 'proficiency' => 95, 'sort_order' => 1],
            ['name' => 'Laravel', 'category' => 'Backend', 'proficiency' => 93, 'sort_order' => 2],
            ['name' => 'MySQL', 'category' => 'Backend', 'proficiency' => 88, 'sort_order' => 3],
            ['name' => 'Redis', 'category' => 'Backend', 'proficiency' => 80, 'sort_order' => 4],
            ['name' => 'REST API', 'category' => 'Backend', 'proficiency' => 90, 'sort_order' => 5],
            ['name' => 'Node.js', 'category' => 'Backend', 'proficiency' => 70, 'sort_order' => 6],

            // Frontend
            ['name' => 'HTML/CSS', 'category' => 'Frontend', 'proficiency' => 92, 'sort_order' => 1],
            ['name' => 'JavaScript', 'category' => 'Frontend', 'proficiency' => 85, 'sort_order' => 2],
            ['name' => 'TailwindCSS', 'category' => 'Frontend', 'proficiency' => 90, 'sort_order' => 3],
            ['name' => 'Vue.js', 'category' => 'Frontend', 'proficiency' => 78, 'sort_order' => 4],
            ['name' => 'React', 'category' => 'Frontend', 'proficiency' => 65, 'sort_order' => 5],

            // DevOps
            ['name' => 'Docker', 'category' => 'DevOps', 'proficiency' => 75, 'sort_order' => 1],
            ['name' => 'Linux/Server', 'category' => 'DevOps', 'proficiency' => 82, 'sort_order' => 2],
            ['name' => 'Git', 'category' => 'DevOps', 'proficiency' => 88, 'sort_order' => 3],
            ['name' => 'CI/CD', 'category' => 'DevOps', 'proficiency' => 70, 'sort_order' => 4],
            ['name' => 'Nginx', 'category' => 'DevOps', 'proficiency' => 78, 'sort_order' => 5],

            // Tools
            ['name' => 'WordPress', 'category' => 'Tools', 'proficiency' => 90, 'sort_order' => 1],
            ['name' => 'FFmpeg', 'category' => 'Tools', 'proficiency' => 75, 'sort_order' => 2],
            ['name' => 'Vite', 'category' => 'Tools', 'proficiency' => 82, 'sort_order' => 3],
            ['name' => 'Elasticsearch', 'category' => 'Tools', 'proficiency' => 65, 'sort_order' => 4],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
