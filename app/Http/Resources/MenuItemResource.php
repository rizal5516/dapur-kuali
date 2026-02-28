<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'menuCategoryId' => $this->menu_category_id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'description'    => $this->description,
            'price'          => (int) $this->price,
            'imageUrl'       => $this->image_url,
            'isFeatured'     => (bool) $this->is_featured,
            'isAvailable'    => (bool) $this->is_available,
            'sortOrder'      => (int) $this->sort_order,
            'createdAt'      => $this->created_at?->toISOString(),
            'updatedAt'      => $this->updated_at?->toISOString(),
            'category'       => $this->whenLoaded('category', fn() => [
                'id'          => $this->category->id,
                'name'        => $this->category->name,
                'slug'        => $this->category->slug,
                'cuisineType' => $this->category->cuisine_type,
            ]),
        ];
    }
}
