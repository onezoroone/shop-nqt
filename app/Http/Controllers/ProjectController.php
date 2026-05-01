<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $categorySlug = $request->query('category');

        $categories = Cache::remember('project_categories', 3600, function () {
            return Category::ofType('project')
                ->ordered()
                ->withCount(['projects' => fn ($q) => $q->published()])
                ->get();
        });

        $projects = Project::select('id', 'category_id', 'title', 'slug', 'excerpt', 'thumbnail', 'tech_stack', 'is_featured', 'published_at')
            ->with('category:id,name,slug')
            ->published()
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            })
            ->ordered()
            ->paginate(12);

        return view('projects.index', [
            'projects' => $projects,
            'categories' => $categories,
            'currentCategory' => $categorySlug,
        ]);
    }

    public function show(Project $project): View
    {
        $project->load('category:id,name,slug');

        $relatedProjects = Project::select('id', 'category_id', 'title', 'slug', 'excerpt', 'thumbnail', 'tech_stack')
            ->with('category:id,name,slug')
            ->published()
            ->where('category_id', $project->category_id)
            ->where('id', '!=', $project->id)
            ->ordered()
            ->limit(3)
            ->get();

        return view('projects.show', [
            'project' => $project,
            'relatedProjects' => $relatedProjects,
        ]);
    }
}
