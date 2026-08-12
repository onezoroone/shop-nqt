<?php

namespace Tests\Feature\Admin;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTechnologyUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_project_technologies_from_csv_field(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $project = Project::factory()->create([
            'tech_stack' => ['Laravel', 'MySQL'],
        ]);

        $response = $this->actingAs($admin, 'backpack')->put(route('project.update', $project->id), [
            'id' => $project->id,
            'title' => $project->title,
            'slug' => $project->slug,
            'category_id' => $project->category_id,
            'excerpt' => $project->excerpt,
            'description' => $project->description,
            'thumbnail' => $project->thumbnail,
            'tech_stack_csv' => 'Laravel, Vue.js, Redis, Laravel,  ',
            'demo_url' => $project->demo_url,
            'source_url' => $project->source_url,
            'is_featured' => $project->is_featured,
            'sort_order' => $project->sort_order,
            'status' => $project->status,
            'published_at' => $project->published_at?->format('Y-m-d H:i:s'),
            'meta_title' => $project->meta_title,
            'meta_description' => $project->meta_description,
            'meta_keywords' => $project->meta_keywords,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertSame(
            ['Laravel', 'Vue.js', 'Redis'],
            $project->fresh()->tech_stack
        );
    }
}
