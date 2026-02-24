<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexMenuCategoryRequest;
use App\Http\Requests\StoreMenuCategoryRequest;
use App\Http\Requests\UpdateMenuCategoryRequest;
use App\Http\Resources\MenuCategoryResource;
use App\Models\MenuCategory;
use App\Services\Admin\MenuCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MenuCategoryAdminController extends Controller
{
    public function __construct(private readonly MenuCategoryService $service) {}

    public function index(IndexMenuCategoryRequest $request): AnonymousResourceCollection
    {
        $categories = $this->service->paginate($request->validated());

        return MenuCategoryResource::collection($categories);
    }

    public function store(StoreMenuCategoryRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), ['created_by' => $request->user()->id]);

        $category = MenuCategory::query()->create($data);

        return (new MenuCategoryResource($category))
            ->additional(['message' => 'Kategori berhasil dibuat.'])
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateMenuCategoryRequest $request, MenuCategory $menu_category): JsonResponse
    {
        $menu_category->update($request->validated());

        return (new MenuCategoryResource($menu_category->fresh()))
            ->additional(['message' => 'Kategori berhasil diperbarui.'])
            ->response();
    }

    public function destroy(MenuCategory $menu_category): JsonResponse
    {
        $menu_category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}
