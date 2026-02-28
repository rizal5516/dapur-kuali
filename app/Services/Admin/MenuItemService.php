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
                isset($filters['search']) && filled($filters['search']),
                fn($q) => $q->where('name', 'like', '%' . str($filters['search'])->limit(100) . '%')
            )
            ->when(
                isset($filters['menu_category_id']),
                fn($q) => $q->where('menu_category_id', (int) $filters['menu_category_id'])
            )
            ->when(
                isset($filters['is_available']),
                fn($q) => $q->where('is_available', filter_var($filters['is_available'], FILTER_VALIDATE_BOOLEAN))
            )
            ->when(
                isset($filters['is_featured']),
                fn($q) => $q->where('is_featured', filter_var($filters['is_featured'], FILTER_VALIDATE_BOOLEAN))
            )
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage);
    }
}
