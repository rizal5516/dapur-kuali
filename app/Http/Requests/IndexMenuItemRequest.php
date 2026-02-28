<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'search'             => ['sometimes', 'nullable', 'string', 'max:100'],
            'menu_category_id'   => ['sometimes', 'nullable', 'integer', 'exists:menu_categories,id'],
            'is_available'       => ['sometimes', 'nullable', 'boolean'],
            'is_featured'        => ['sometimes', 'nullable', 'boolean'],
            'sort_by'            => ['sometimes', Rule::in(['name', 'price', 'sort_order', 'created_at'])],
            'sort_dir'           => ['sometimes', Rule::in(['asc', 'desc'])],
            'per_page'           => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
