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
            'search'           => ['sometimes', 'string', 'max:100'],
            'cuisine_type'     => ['sometimes', 'string', 'in:makanan,minuman,dessert'], // ← TAMBAHKAN INI
            'menu_category_id' => ['sometimes', 'integer', 'exists:menu_categories,id'],
            'is_available'     => ['sometimes', 'boolean'],
            'is_featured'      => ['sometimes', 'boolean'],
            'sort_by'          => ['sometimes', 'string', 'in:name,price,sort_order,created_at'],
            'sort_dir'         => ['sometimes', 'string', 'in:asc,desc'],
            'per_page'         => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page'             => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
