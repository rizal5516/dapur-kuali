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
            'previewImages' => $this->whenLoaded(
                'items',
                fn() =>
                $this->items
                    ->whereNotNull('image_url')
                    ->sortBy('sort_order')
                    ->take(3)
                    ->values()
                    ->map(fn($item) => $this->resolveImageUrl($item->image_url))
                    ->all()
            ),
        ];
    }

    private function resolveImageUrl(?string $imagePath): ?string
    {
        if (blank($imagePath)) return null;
        if (str_starts_with($imagePath, 'http')) return $imagePath;

        return rtrim(config('app.url'), '/') . '/storage/' . ltrim($imagePath, '/');
    }
}
