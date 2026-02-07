<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuItemAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = MenuItem::query()
            ->with(['category:id,name,slug,cuisine_type'])
            ->orderByDesc('id')
            ->paginate(20);

        return response()->json($items);
    }

    public function store(StoreMenuItemRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $item = MenuItem::query()->create($data);

        return response()->json([
            'message' => 'Menu item berhasil dibuat.',
            'data' => $item->fresh(['category:id,name,slug,cuisine_type']),
        ], 201);
    }

    public function update(UpdateMenuItemRequest $request, MenuItem $menu_item): JsonResponse
    {
        $menu_item->update($request->validated());

        return response()->json([
            'message' => 'Menu item berhasil diperbarui.',
            'data' => $menu_item->fresh(['category:id,name,slug,cuisine_type']),
        ]);
    }

    public function destroy(Request $request, MenuItem $menu_item): JsonResponse
    {
        $menu_item->delete();

        return response()->json(['message' => 'Menu item berhasil dihapus.']);
    }
}
