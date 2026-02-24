<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\GalleryAdminController;
use App\Http\Controllers\Api\Admin\MenuCategoryAdminController;
use App\Http\Controllers\Api\Admin\MenuItemAdminController;
use App\Http\Controllers\Api\Admin\RestaurantProfileAdminController;
use App\Http\Controllers\Api\Admin\UploadController;
use App\Http\Controllers\Api\Admin\WeddingReservationAdminController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\Public\GalleryController;
use App\Http\Controllers\Api\Public\MenuController;
use App\Http\Controllers\Api\Public\ProfileController;
use App\Http\Controllers\Api\Public\WeddingReservationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — No authentication required
|--------------------------------------------------------------------------
*/

Route::prefix('public')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::get('/menu', [MenuController::class, 'index']);
    Route::get('/gallery', [GalleryController::class, 'index']);

    Route::post('/wedding-reservations', [WeddingReservationController::class, 'store'])
        ->middleware('throttle:wedding-reservations');
});

/*
|--------------------------------------------------------------------------
| Admin Routes — All session-based via web middleware (SPA Vue.js only)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    /*
    | Auth — Guest only (login, forgot, reset tidak perlu sudah login)
    */
    Route::middleware('guest')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:login');

        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
            ->middleware('throttle:password-reset');

        Route::post('/reset-password', [AuthController::class, 'resetPassword'])
            ->middleware('throttle:password-reset');
    });

    /*
    | Auth — Authenticated
    */
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/change-password', [AdminController::class, 'changePassword'])
            ->middleware('throttle:change-password')
            ->name('admin.change-password');
    });

    /*
    | CMS — Authenticated + Role
    */
    Route::middleware(['auth:sanctum', 'role:admin,editor'])->group(function () {
        Route::get('/profile', [RestaurantProfileAdminController::class, 'show']);
        Route::put('/profile', [RestaurantProfileAdminController::class, 'update']);

        Route::apiResource('/menu-categories', MenuCategoryAdminController::class)
            ->except(['show']);

        Route::apiResource('/menu-items', MenuItemAdminController::class)
            ->except(['show']);

        Route::apiResource('/galleries', GalleryAdminController::class)
            ->except(['show']);

        Route::prefix('wedding-reservations')->group(function () {
            Route::get('/', [WeddingReservationAdminController::class, 'index']);
            Route::patch('/{reservation}/status', [WeddingReservationAdminController::class, 'updateStatus']);
        });

        Route::post('/uploads/images', [UploadController::class, 'storeImage']);
    });
});

/*
|--------------------------------------------------------------------------
| System
|--------------------------------------------------------------------------
*/
Route::get('/health', [HealthController::class, 'index']);
