<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (empty($this->slug) && $this->title) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->title)
            ]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $productId = $this->route('id');

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200', Rule::unique('products')->ignore($productId)],
            'categories' => ['required', 'array'],
            'categories.*' => ['exists:categories,id'],
            'excerpt' => ['required', 'string'],
            'description' => ['required', 'string'],
            'thumbnail' => ['nullable'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'tech_stack' => ['nullable', 'array'],
            'features' => ['nullable', 'array'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['boolean'],
            'download_count' => ['integer', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }
}
