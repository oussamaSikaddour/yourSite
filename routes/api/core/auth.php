<?php

use App\Http\Controllers\Api\Core\AuthController;
use Illuminate\Support\Facades\Route;

// ==========================
// Public Auth API Routes
// ==========================
// All routes here are accessible to guests and protected by the `apiMaintenance` middleware
Route::group([
    "prefix" => "api/{locale}", // Allows for locale-aware API URLs (e.g. /api/en/login)
    "middleware" => ["guest", "apiMaintenance"]
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('registerFirstStep', [AuthController::class, 'registerFirstStep']);
    Route::post('registerLastStep', [AuthController::class, 'registerLastStep']);
    Route::post('verificationCode', [AuthController::class, 'sendVerificationCode']);
    Route::post('forgotPassword', [AuthController::class, 'forgotPassword']);
});

// ==========================
// Protected Auth API Routes
// ==========================
// Requires auth:sanctum token and passes maintenance mode check
Route::group([
    'prefix' => 'api/{locale}',
    'middleware' => ['auth:sanctum', 'apiMaintenance']
], function () {
    // This endpoint should not be blocked by `api.account.active`
    Route::get('toggleAccountStatus', [AuthController::class, 'toggleAccountStatus']);

    // These endpoints require the account to be active
    Route::group(['middleware' => ['api.account.active']], function () {
        Route::get('refreshToken', [AuthController::class, 'refreshToken']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('change-email', [AuthController::class, 'changeEmail']);
    });
});

