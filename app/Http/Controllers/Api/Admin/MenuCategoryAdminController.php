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
        $category = $this->service->create($request->validated(), $request->user()->id);

        return (new MenuCategoryResource($category))
            ->additional(['message' => 'Kategori berhasil dibuat.'])
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateMenuCategoryRequest $request, MenuCategory $menu_category): JsonResponse
    {
        $category = $this->service->update($menu_category, $request->validated());

        return (new MenuCategoryResource($category))
            ->additional(['message' => 'Kategori berhasil diperbarui.'])
            ->response();
    }

    public function destroy(MenuCategory $menu_category): JsonResponse
    {
        $this->service->delete($menu_category);

        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}
