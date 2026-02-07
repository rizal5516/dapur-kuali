<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AdminForgotPasswordRequest;
use App\Http\Requests\AdminResetPasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

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
        $status = Password::broker('users')->sendResetLink(['email' => $email]);

        return response()->json([
        'message' => 'Jika email terdaftar, kami sudah mengirim link reset password.',
        'status' => $status,
        ]);
    }

    public function resetPassword(AdminResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();


        $status = Password::broker('users')->reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'token' => $data['token'],
            ],
            function ($user) use ($data) {
                $user->forceFill([
                    'password' => Hash::make($data['password']),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
        // Jangan bocorkan detail; tapi masih aman untuk kirim status generik
        return response()->json([
        'message' => 'Reset password gagal. Token tidak valid atau sudah kedaluwarsa.',
        'status' => $status,
        ], 422);
        }


        return response()->json([
        'message' => 'Password berhasil direset. Silakan login kembali.',
        'status' => $status,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout berhasil.']);
    }
}
