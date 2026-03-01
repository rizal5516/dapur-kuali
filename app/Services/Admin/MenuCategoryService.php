<?php

namespace App\Services\Admin;

use App\Models\MenuCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class MenuCategoryService
{
    private const ALLOWED_SORT_COLUMNS = ['name', 'sort_order', 'created_at'];
    private const ALLOWED_SORT_DIRS    = ['asc', 'desc'];
    private const PER_PAGE_MAX         = 100;
    private const PER_PAGE_DEFAULT     = 10;

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

        return MenuCategory::query()
            ->when(
                filled(Arr::get($filters, 'search')),
                fn($q) => $q->where('name', 'like', '%' . Arr::get($filters, 'search') . '%')
            )
            ->when(
                Arr::has($filters, 'is_active'),
                fn($q) => $q->where('is_active', (bool) $filters['is_active'])
            )
            ->when(
                filled(Arr::get($filters, 'cuisine_type')),
                fn($q) => $q->where('cuisine_type', $filters['cuisine_type'])
            )
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage);
    }

    public function create(array $validated, int $createdBy): MenuCategory
    {
        return MenuCategory::query()->create(
            array_merge($validated, ['created_by' => $createdBy])
        );
    }

    public function update(MenuCategory $menuCategory, array $validated): MenuCategory
    {
        $menuCategory->update($validated);

        return $menuCategory->fresh();
    }

    public function delete(MenuCategory $menuCategory): void
    {
        $menuCategory->delete();
    }
}
