<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\HealthController;

use App\Http\Controllers\Api\Public\ProfileController;
use App\Http\Controllers\Api\Public\MenuController;
use App\Http\Controllers\Api\Public\GalleryController;
use App\Http\Controllers\Api\Public\WeddingReservationController;

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\MenuCategoryAdminController;
use App\Http\Controllers\Api\Admin\MenuItemAdminController;
use App\Http\Controllers\Api\Admin\GalleryAdminController;
use App\Http\Controllers\Api\Admin\RestaurantProfileAdminController;
use App\Http\Controllers\Api\Admin\WeddingReservationAdminController;
use App\Http\Controllers\Api\Admin\UploadController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('public')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::get('/menu', [MenuController::class, 'index']);
    Route::get('/gallery', [GalleryController::class, 'index']);

    Route::post('/wedding-reservations', [WeddingReservationController::class, 'store'])
        ->middleware('throttle:wedding-reservations');
});

Route::prefix('admin')->group(function () {

    /**
     * AUTH (Sanctum SPA / cookie-based)
     * Wajib lewat middleware "web" supaya session tersedia.
     */
    Route::middleware(['web'])->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
        Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:password-reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:password-reset');

        Route::post('/change-password', [AdminController::class, 'changePassword'])
        ->middleware(['auth:sanctum', 'throttle:change-password'])
        ->name('admin.change-password');
    });

    /**
     * CMS protected routes
     */
    Route::middleware(['auth:sanctum', 'role:admin,editor'])->group(function () {
        Route::get('/profile', [RestaurantProfileAdminController::class, 'show']);
        Route::put('/profile', [RestaurantProfileAdminController::class, 'update']);

        Route::apiResource('/menu-categories', MenuCategoryAdminController::class)->except(['show']);
        Route::apiResource('/menu-items', MenuItemAdminController::class)->except(['show']);

        Route::apiResource('/galleries', GalleryAdminController::class)->except(['show']);

        Route::get('/wedding-reservations', [WeddingReservationAdminController::class, 'index']);
        Route::patch('/wedding-reservations/{id}/status', [WeddingReservationAdminController::class, 'updateStatus']);

        Route::post('/uploads/images', [UploadController::class, 'storeImage']);
    });
});

Route::get('/health', [HealthController::class, 'index']);
