<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $category = $request->query('category'); // food|interior|wedding

        $query = Gallery::query()
            ->select(['id', 'title', 'category', 'image_url', 'alt_text', 'sort_order'])
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('sort_order');

        if (is_string($category) && in_array($category, ['food', 'interior', 'wedding'], true)) {
            $query->where('category', $category);
        }

        $items = $query->get();

        return response()->json([
            'data' => $items,
        ]);
    }
}
