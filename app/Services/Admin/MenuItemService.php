<?php

namespace App\Services\Admin;

use App\Models\MenuItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class MenuItemService
{
    private const ALLOWED_SORT_COLUMNS = ['name', 'price', 'sort_order', 'created_at'];
    private const ALLOWED_SORT_DIRS    = ['asc', 'desc'];
    private const PER_PAGE_MAX         = 100;
    private const PER_PAGE_DEFAULT     = 20;

    public function paginate(array $filters): LengthAwarePaginator
    {
        $perPage = min(
            (int) Arr::get($filters, 'per_page', self::PER_PAGE_DEFAULT),
            self::PER_PAGE_MAX,
        );

        $sortBy = in_array($filters['sort_by'] ?? '', self::ALLOWED_SORT_COLUMNS, true)
            ? $filters['sort_by']
            : 'sort_order';

        $sortDir = in_array($filters['sort_dir'] ?? '', self::ALLOWED_SORT_DIRS, true)
            ? $filters['sort_dir']
            : 'asc';

        return MenuItem::query()
            ->with(['category:id,name,slug,cuisine_type'])
            ->when(
                filled(Arr::get($filters, 'search')),
                fn($q) => $q->where('name', 'like', '%' . Arr::get($filters, 'search') . '%')
            )
            ->when(
                filled(Arr::get($filters, 'cuisine_type')),
                fn($q) => $q->whereHas(
                    'category',
                    fn($cat) => $cat->where('cuisine_type', $filters['cuisine_type'])
                )
            )
            ->when(
                (int) Arr::get($filters, 'menu_category_id', 0) > 0,
                fn($q) => $q->where('menu_category_id', (int) $filters['menu_category_id'])
            )
            ->when(
                Arr::has($filters, 'is_available'),
                fn($q) => $q->where('is_available', (bool) $filters['is_available'])
            )
            ->when(
                Arr::has($filters, 'is_featured'),
                fn($q) => $q->where('is_featured', (bool) $filters['is_featured'])
            )
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage);
    }

    public function create(array $validated, int $createdBy): MenuItem
    {
        return MenuItem::query()->create(
            array_merge($validated, ['created_by' => $createdBy])
        );
    }

    public function update(MenuItem $menuItem, array $validated): MenuItem
    {
        $menuItem->update($validated);

        return $menuItem->fresh('category');
    }

    public function delete(MenuItem $menuItem): void
    {
        $menuItem->delete();
    }
}
