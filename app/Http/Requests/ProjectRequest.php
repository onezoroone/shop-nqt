<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $projectId = $this->route('id');

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200', Rule::unique('projects')->ignore($projectId)],
            'category_id' => ['required', 'exists:categories,id'],
            'excerpt' => ['required', 'string'],
            'description' => ['required', 'string'],
            'thumbnail' => ['nullable', 'string'],
            'tech_stack' => ['nullable', 'array'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
