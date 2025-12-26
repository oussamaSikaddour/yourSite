<?php

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Web\Core\AuthController;
use App\Http\Controllers\Web\Core\UserController;
use Illuminate\Support\Facades\Route;

// ============================================================
// 🔐 Authenticated User Routes
// ============================================================

// 🔓 Routes accessible to authenticated users (no maintenance check)
Route::middleware('auth')->group(function () {
    // 🔘 Logout route
    Route::get('/logout', [AuthController::class, 'logout'])
        ->name(RoutesNames::LOG_OUT->value);
});

// 🔐 Routes that require both authentication and maintenance check
Route::middleware(['auth', 'maintenance', 'web.account.active'])->group(function () {

    // 🔑 Password Change Page
    Route::get('/change-password', [UserController::class, 'showChangePasswordPage'])
        ->name(RoutesNames::CHANGE_PASSWORD->value);

    // 📧 Email Change Page
    Route::get('/change-email', [UserController::class, 'showChangeEmailPage'])
        ->name(RoutesNames::CHANGE_EMAIL->value);

    Route::get('/MedicalFile', [UserController::class, 'showMedicalFilePage'])
        ->name(RoutesNames::MEDICAL_FILE_ROUTE->value);
    // 👤 Profile Page
    Route::get('/profile', [UserController::class, 'showProfilePage'])
        ->name(RoutesNames::PROFILE->value);
    Route::get('/dashboard', [UserController::class, 'showDashboard'])
        ->name(RoutesNames::DASHBOARD->value);
});
