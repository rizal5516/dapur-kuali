<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\Admin\ChangePasswordService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Exception;

class AdminController extends Controller
{
    public function __construct(
        private readonly ChangePasswordService $changePasswordService
    ) {}

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user      = $request->user();
        $validated = $request->validated();

        if (!$this->changePasswordService->verifyCurrentPassword($user, $validated['current_password'])) {
            return response()->json([
                'message' => 'Password saat ini tidak sesuai.',
            ], 422);
        }

        try {
            $this->changePasswordService->changePassword(
                $user,
                $validated['new_password'],
                $request->session()->getId()
            );
        } catch (Exception $e) {
            Log::error('Failed to change admin password.', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat mengubah password. Silakan coba lagi.',
            ], 500);
        }

        return response()->json([
            'message' => 'Password berhasil diubah.',
        ], 200);
    }
}