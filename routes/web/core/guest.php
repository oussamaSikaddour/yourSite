<?php

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Web\Core\GuestController;
use Illuminate\Support\Facades\Route;

// ============================================================
// Guest Routes (Only accessible to unauthenticated users)
// Prefix: /
// Middleware:
//   - guest        → only accessible if NOT authenticated
//   - maintenance  → disabled if app is in maintenance mode
// ============================================================

Route::group(['middleware' => ['guest', 'maintenance']], function () {

    // Registration page
    // URL: /register
    // Route Name: guest.register
    Route::get('/register', [GuestController::class, 'showRegisterPage'])
        ->name(RoutesNames::REGISTER->value);

    // Login page
    // URL: /login
    // Route Name: guest.login
    Route::get('/login', [GuestController::class, 'showLoginPage'])
        ->name(RoutesNames::LOGIN->value);

    // Forgot password page
    // URL: /forgetPassword
    // Route Name: guest.forgetPassword
    Route::get('/forgetPassword', [GuestController::class, 'showForgetPasswordPage'])
        ->name(RoutesNames::FORGET_PASSWORD->value);
});
