<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AdminForgotPasswordRequest;
use App\Http\Requests\AdminResetPasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\RateLimiter;
use Exception;

class AuthController extends Controller
{
    public function login(AdminLoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login berhasil.',
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ] : null,
        ]);
    }

    public function forgotPassword(AdminForgotPasswordRequest $request): JsonResponse
    {
        $email = $request->validated()['email'];

        $key = 'forgot-password:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik.",
            ], 429);
        }

        RateLimiter::hit($key, 300);

        try {
            $status = Password::broker('users')->sendResetLink(['email' => $email]);
        } catch (Exception $e) {
            // Log error untuk debugging, tapi jangan expose ke user
            Log::error('Forgot password error', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        // Selalu return success response (prevent user enumeration)
        return response()->json([
            'message' => 'Jika email terdaftar, kami sudah mengirim link reset password.',
        ], 200);
    }

    public function resetPassword(AdminResetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $key = 'reset-password:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik.",
            ], 429);
        }

        RateLimiter::hit($key, 600);

        try {
            $status = Password::broker('users')->reset(
                [
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'password_confirmation' => $validated['password_confirmation'],
                    'token' => $validated['token'],
                ],
                function ($user, $password) {
                    try {
                        $user->forceFill([
                            'password' => Hash::make($password),
                            'remember_token' => Str::random(60),
                        ])->save();

                        // Invalidate all sessions - wrapped in try-catch
                        try {
                            DB::table('sessions')
                                ->where('user_id', $user->id)
                                ->delete();
                        } catch (Exception $e) {
                            // Log tapi jangan stop process
                            Log::warning('Failed to delete sessions on password reset', [
                                'user_id' => $user->id,
                                'error' => $e->getMessage(),
                            ]);
                        }

                        event(new PasswordReset($user));
                    } catch (Exception $e) {
                        // Log error internal
                        Log::error('Reset password callback error', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);

                        throw $e; // Re-throw agar Laravel Password broker handle
                    }
                }
            );

            if ($status !== Password::PASSWORD_RESET) {
                return response()->json([
                    'message' => $this->getResetErrorMessage($status),
                ], 422);
            }

            RateLimiter::clear($key);

            return response()->json([
                'message' => 'Password berhasil direset. Silakan login kembali.',
            ], 200);

        } catch (Exception $e) {
            // Log technical error
            Log::error('Reset password error', [
                'email' => $validated['email'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return user-friendly error (tidak expose detail teknis)
            return response()->json([
                'message' => 'Terjadi kesalahan saat mereset password. Silakan coba lagi atau hubungi administrator.',
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout berhasil.']);
    }

    private function getResetErrorMessage(string $status): string
    {
        return match ($status) {
            Password::INVALID_TOKEN => 'Link reset password tidak valid atau sudah kedaluwarsa.',
            Password::INVALID_USER => 'Link reset password tidak valid atau sudah kedaluwarsa.',
            default => 'Reset password gagal. Silakan minta link reset baru.',
        };
    }
}
