<?php

use App\Http\Controllers\Api\Core\BankingInformationController;
use Illuminate\Support\Facades\Route;

// ==========================================
// Locale-aware, protected API resource routes
// ==========================================
// Prefix: /api/{locale}/banking-information
// Middleware:
//   - auth:sanctum → ensures the user is authenticated
//   - apiMaintenance → blocks access if app is in maintenance mode

Route::group([
    'prefix' => 'api/{locale}',
    'middleware' => ['auth:sanctum', 'apiMaintenance','api.account.active'],
], function () {

    // Provides all RESTful routes for BankingInformation:
    // - index   → GET    /banking-information
    // - store   → POST   /banking-information
    // - show    → GET    /banking-information/{banking-information}
    // - update  → PUT    /banking-information/{banking-information}
    // - destroy → DELETE /banking-information/{banking-information}
    Route::apiResource('banking-information', BankingInformationController::class);
});
