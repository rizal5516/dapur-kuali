<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = Gallery::query()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->paginate(20);

        return response()->json($items);
    }

    public function store(StoreGalleryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $gallery = Gallery::query()->create($data);

        return response()->json([
            'message' => 'Gallery berhasil dibuat.',
            'data' => $gallery,
        ], 201);
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery): JsonResponse
    {
        $gallery->update($request->validated());

        return response()->json([
            'message' => 'Gallery berhasil diperbarui.',
            'data' => $gallery->fresh(),
        ]);
    }

    public function destroy(Request $request, Gallery $gallery): JsonResponse
    {
        $gallery->delete();

        return response()->json(['message' => 'Gallery berhasil dihapus.']);
    }
}
