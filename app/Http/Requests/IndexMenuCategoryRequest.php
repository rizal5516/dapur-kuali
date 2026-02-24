<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexMenuCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'search'       => ['sometimes', 'nullable', 'string', 'max:100'],
            'is_active'    => ['sometimes', 'nullable', 'boolean'],
            'cuisine_type' => ['sometimes', 'nullable', Rule::in(['makanan', 'minuman', 'dessert'])], // ✅
            'sort_by'      => ['sometimes', Rule::in(['name', 'sort_order', 'created_at'])],
            'sort_dir'     => ['sometimes', Rule::in(['asc', 'desc'])],
            'per_page'     => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
