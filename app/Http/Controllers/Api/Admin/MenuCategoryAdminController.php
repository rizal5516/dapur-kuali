<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuCategoryRequest;
use App\Http\Requests\UpdateMenuCategoryRequest;
use App\Models\MenuCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuCategoryAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = MenuCategory::query()
            ->orderBy('sort_order')
            ->paginate(20);

        return response()->json($categories);
    }

    public function store(StoreMenuCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $category = MenuCategory::query()->create($data);

        return response()->json([
            'message' => 'Kategori berhasil dibuat.',
            'data' => $category,
        ], 201);
    }

    public function update(UpdateMenuCategoryRequest $request, MenuCategory $menu_category): JsonResponse
    {
        $menu_category->update($request->validated());

        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'data' => $menu_category->fresh(),
        ]);
    }

    public function destroy(Request $request, MenuCategory $menu_category): JsonResponse
    {
        // Aman: soft delete (jangan hard delete)
        $menu_category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}
