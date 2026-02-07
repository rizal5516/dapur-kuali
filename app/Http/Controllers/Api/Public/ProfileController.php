<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RestaurantProfile;


class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $profile = RestaurantProfile::query()
            ->select([
                'id',
                'brand_name',
                'tagline',
                'about',
                'address',
                'phone',
                'whatsapp_number',
                'email',
                'instagram_url',
                'google_maps_embed',
                'opening_hours_text',
            ])
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->first();

        return response()->json([
            'data' => $profile,
        ]);
    }
}
