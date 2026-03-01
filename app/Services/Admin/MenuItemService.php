<?php

namespace App\Services\Admin;

use App\Models\MenuItem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class MenuItemService
{
    private const ALLOWED_SORT_COLUMNS = ['name', 'price', 'sort_order', 'created_at'];
    private const ALLOWED_SORT_DIRS    = ['asc', 'desc'];
    private const IMAGE_DISK           = 'public';
    private const IMAGE_FOLDER         = 'menu-items';

    public function paginate(array $filters = [])
    {
        $perPage = min((int) Arr::get($filters, 'per_page', 15), 100);

        $sortBy = in_array($filters['sort_by'] ?? '', self::ALLOWED_SORT_COLUMNS, true)
            ? $filters['sort_by']
            : 'sort_order';

        $sortDir = in_array($filters['sort_dir'] ?? '', self::ALLOWED_SORT_DIRS, true)
            ? $filters['sort_dir']
            : 'asc';

        return MenuItem::query()
            ->with(['category:id,name,slug,cuisine_type'])
            ->whereHas('category', fn($q) => $q->where('is_active', true))
            ->when(
                filled(Arr::get($filters, 'search')),
                fn($q) => $q->where('name', 'like', '%' . Arr::get($filters, 'search') . '%')
            )
            ->when(
                filled(Arr::get($filters, 'cuisine_type')),
                fn($q) => $q->whereHas(
                    'category',
                    fn($cat) => $cat
                        ->where('cuisine_type', $filters['cuisine_type'])
                        ->where('is_active', true)
                )
            )
            ->when(
                (int) Arr::get($filters, 'menu_category_id', 0) > 0,
                fn($q) => $q->where('menu_category_id', (int) $filters['menu_category_id'])
            )
            ->when(
                array_key_exists('is_available', $filters) && $filters['is_available'] !== null,
                fn($q) => $q->where('is_available', (bool) $filters['is_available'])
            )
            ->when(
                array_key_exists('is_featured', $filters) && $filters['is_featured'] !== null,
                fn($q) => $q->where('is_featured', (bool) $filters['is_featured'])
            )
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage);
    }

    public function create(array $validated, int $createdBy): MenuItem
    {
        $validated['image_url'] = $this->storeImage($validated);

        unset($validated['image']);

        return MenuItem::query()->create(
            array_merge($validated, ['created_by' => $createdBy])
        );
    }

    public function update(MenuItem $menuItem, array $validated): MenuItem
    {
        $newImageUrl = $this->storeImage($validated);

        if ($newImageUrl !== null) {

            $this->deleteOldImage($menuItem->image_url);
            $validated['image_url'] = $newImageUrl;
        } elseif (!empty($validated['remove_image'])) {
            $this->deleteOldImage($menuItem->image_url);
            $validated['image_url'] = null;
        }

        unset($validated['image'], $validated['remove_image']);

        $menuItem->update($validated);

        return $menuItem->fresh('category');
    }

    public function delete(MenuItem $menuItem): void
    {
        $this->deleteOldImage($menuItem->image_url);
        $menuItem->delete();
    }

    private function storeImage(array $data): ?string
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            return $data['image']->store(self::IMAGE_FOLDER, self::IMAGE_DISK);
        }
        return null;
    }

    private function deleteOldImage(?string $imagePath): void
    {
        if (filled($imagePath) && !str_starts_with($imagePath, 'http')) {
            Storage::disk(self::IMAGE_DISK)->delete($imagePath);
        }
    }
}
