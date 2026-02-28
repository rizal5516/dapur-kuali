<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexMenuItemRequest;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use App\Services\Admin\MenuItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MenuItemAdminController extends Controller
{
    public function __construct(private readonly MenuItemService $service) {}

    public function index(IndexMenuItemRequest $request): AnonymousResourceCollection
    {
        $items = $this->service->paginate($request->validated());

        return MenuItemResource::collection($items);
    }

    public function store(StoreMenuItemRequest $request): JsonResponse
    {
        $item = $this->service->create($request->validated(), $request->user()->id);

        return (new MenuItemResource($item->load('category')))
            ->additional(['message' => 'Menu item berhasil dibuat.'])
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateMenuItemRequest $request, MenuItem $menu_item): JsonResponse
    {
        $item = $this->service->update($menu_item, $request->validated());

        return (new MenuItemResource($item))
            ->additional(['message' => 'Menu item berhasil diperbarui.'])
            ->response();
    }

    public function destroy(MenuItem $menu_item): JsonResponse
    {
        $this->service->delete($menu_item);

        return response()->json(['message' => 'Menu item berhasil dihapus.']);
    }
}
