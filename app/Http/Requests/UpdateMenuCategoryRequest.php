<?php

namespace App\Http\Requests;

use App\Models\MenuCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMenuCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $menuCategory = $this->route('menu_category');
        $id = $menuCategory instanceof MenuCategory ? $menuCategory->id : (int) $menuCategory;

        return [
            'name'         => ['sometimes', 'string', 'min:2', 'max:80'],
            'slug'         => [
                'sometimes',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('menu_categories', 'slug')->ignore($id),
            ],
            'cuisine_type' => ['sometimes', Rule::in(['makanan', 'minuman', 'dessert'])],
            'sort_order'   => ['sometimes', 'integer', 'min:0', 'max:1000000'],
            'is_active'    => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->name) ? trim($this->name) : $this->name,
            'slug' => is_string($this->slug) ? trim($this->slug) : $this->slug,
        ]);
    }
}
