<?php

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Web\App\AdminController;
use Illuminate\Support\Facades\Route;

// ===========================================================
// Admin-only Web Routes
// Prefix: / (root)
// Middleware:
//   - auth           → user must be authenticated
//   - maintenance    → app must not be in maintenance mode
//   - can:admin-access → user must have admin permission
// ===========================================================

Route::group(['middleware' => ['auth', 'maintenance', 'web.account.active',
//  'can:admin-access'
 ]], function () {

    // Name: admin.sliders


    Route::get('/Services', [AdminController::class, 'showServicesPage'])
        ->name(RoutesNames::SERVICES_ROUTE->value);
});
