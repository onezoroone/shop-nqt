<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:100'],
            'proficiency' => ['required', 'integer', 'min:0', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }
}
