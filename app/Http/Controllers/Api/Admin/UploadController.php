<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function storeImage(UploadImageRequest $request): JsonResponse
    {
        $file = $request->file('image');

        // whitelist folder (hindari path traversal)
        $folder = (string) $request->input('folder', 'general');
        $allowedFolders = ['general', 'menu', 'gallery', 'promo', 'profile'];

        if (!in_array($folder, $allowedFolders, true)) {
            $folder = 'general';
        }

        // nama file aman + random (hindari user-controlled file name)
        $ext = $file->getClientOriginalExtension();
        $safeName = Str::uuid()->toString() . '.' . strtolower($ext);

        // Simpan ke storage/app/public/uploads/<folder>/
        $path = $file->storeAs("uploads/{$folder}", $safeName, 'public');

        // URL publik via symlink /storage/...
        $publicUrl = Storage::disk('public')->url($path);

        return response()->json([
            'message' => 'Upload berhasil.',
            'data' => [
                'path' => $path,
                'url' => $publicUrl,
            ],
        ], 201);
    }
}
