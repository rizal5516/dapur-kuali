<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRestaurantProfileRequest;
use App\Models\RestaurantProfile;
use Illuminate\Http\JsonResponse;

class RestaurantProfileAdminController extends Controller
{
    public function show(): JsonResponse
    {
        $profile = RestaurantProfile::query()->orderBy('id')->first();

        return response()->json(['data' => $profile]);
    }

    public function update(UpdateRestaurantProfileRequest $request): JsonResponse
    {
        // MVP: gunakan record pertama; jika belum ada, create
        $profile = RestaurantProfile::query()->orderBy('id')->first();

        if (!$profile) {
            $profile = new RestaurantProfile(['brand_name' => 'Restaurant']);
        }

        $profile->fill($request->validated());
        $profile->updated_by = $request->user()->id;
        $profile->save();

        return response()->json([
            'message' => 'Profile berhasil diperbarui.',
            'data' => $profile->fresh(),
        ]);
    }
}
