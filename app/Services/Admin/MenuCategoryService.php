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
    private const PER_PAGE_DEFAULT     = 20;

    public function paginate(array $filters): LengthAwarePaginator
    {
        $perPage  = min((int) Arr::get($filters, 'per_page', self::PER_PAGE_DEFAULT), self::PER_PAGE_MAX);
        $sortBy   = in_array($filters['sort_by'] ?? '', self::ALLOWED_SORT_COLUMNS, true)
            ? $filters['sort_by']
            : 'sort_order';
        $sortDir  = in_array($filters['sort_dir'] ?? '', self::ALLOWED_SORT_DIRS, true)
            ? $filters['sort_dir']
            : 'asc';

        return MenuCategory::query()
            ->when(
                isset($filters['search']) && filled($filters['search']),
                fn($q) => $q->where('name', 'like', '%' . str($filters['search'])->limit(100) . '%')
            )
            ->when(
                isset($filters['is_active']),
                fn($q) => $q->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN))
            )
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage);
    }
}
