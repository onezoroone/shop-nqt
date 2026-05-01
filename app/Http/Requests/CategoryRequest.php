<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
        $categoryId = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', Rule::unique('categories')->ignore($categoryId)],
            'type' => ['required', Rule::in(['project', 'product'])],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }
}
