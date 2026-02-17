<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class ChangePasswordService
{
    public function verifyCurrentPassword(User $user, string $currentPassword): bool
    {
        return Hash::check($currentPassword, $user->password);
    }

    public function changePassword(User $user, string $newPassword, string $currentSessionId): void
    {
        DB::transaction(function () use ($user, $newPassword, $currentSessionId) {
            $user->forceFill([
                'password'       => Hash::make($newPassword),
                'remember_token' => Str::random(60),
            ])->save();

            $this->invalidateOtherSessions($user->id, $currentSessionId);
        });

        Log::info('Admin password changed.', [
            'user_id' => $user->id,
            'email'   => $user->email,
        ]);
    }

    private function invalidateOtherSessions(int $userId, string $currentSessionId): void
    {
        try {
            DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', '!=', $currentSessionId)
                ->delete();
        } catch (Exception $e) {
            Log::warning('Failed to invalidate other sessions after password change.', [
                'user_id' => $userId,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}