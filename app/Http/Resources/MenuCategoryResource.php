<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'cuisineType' => $this->cuisine_type,
            'isActive'    => (bool) $this->is_active,
            'imageUrl'    => $this->image_url,
            'sortOrder'   => $this->sort_order,
            'createdAt'   => $this->created_at?->toISOString(),
            'updatedAt'   => $this->updated_at?->toISOString(),
        ];
    }
}
