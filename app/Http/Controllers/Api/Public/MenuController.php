<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Filter yang aman (whitelist)
        $cuisine = $request->query('cuisine'); // sunda|betawi|minuman|lainnya
        $featured = $request->boolean('featured', false);

        $categoriesQuery = MenuCategory::query()
            ->select(['id', 'name', 'slug', 'cuisine_type', 'sort_order'])
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->orderBy('sort_order');

        if (is_string($cuisine) && in_array($cuisine, ['sunda', 'betawi', 'minuman', 'lainnya'], true)) {
            $categoriesQuery->where('cuisine_type', $cuisine);
        }

        $categories = $categoriesQuery
            ->with(['items' => function ($q) use ($featured) {
                $q->select([
                        'id',
                        'menu_category_id',
                        'name',
                        'slug',
                        'description',
                        'price',
                        'image_url',
                        'is_featured',
                        'is_available',
                        'sort_order',
                    ])
                    ->where('is_available', true)
                    ->whereNull('deleted_at')
                    ->orderBy('sort_order');

                if ($featured) {
                    $q->where('is_featured', true);
                }
            }])
            ->get();

        return response()->json([
            'data' => $categories,
        ]);
    }
}
