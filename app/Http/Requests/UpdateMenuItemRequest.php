<?php

namespace App\Http\Requests;

use App\Models\MenuItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $menuItem = $this->route('menu_item');
        $id = $menuItem instanceof MenuItem ? $menuItem->id : (int) $menuItem;

        return [
            'menu_category_id' => ['sometimes', 'integer', 'exists:menu_categories,id'],
            'name' => ['sometimes', 'string', 'min:2', 'max:120'],
            'slug' => [
                'sometimes',
                'string',
                'max:150',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('menu_items', 'slug')->ignore($id),
            ],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'price' => ['sometimes', 'integer', 'min:0', 'max:100000000'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_available' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0', 'max:1000000'],
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
