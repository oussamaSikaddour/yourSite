<?php

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Web\App\GuestController;
use Illuminate\Support\Facades\Route;

// ============================================================
// Guest Routes (Only accessible to unauthenticated users)
// Prefix: /
// Middleware:
//   - guest        → only accessible if NOT authenticated
//   - maintenance  → disabled if app is in maintenance mode
// ============================================================

Route::group(['middleware' => ['maintenance']], function () {

    Route::get('/serviceDetails', [GuestController::class, 'showServiceDetailsPage'])
        ->name(RoutesNames::SERVICE_DETAILS_PUBLIC->value);
    Route::get('/publicServices', [GuestController::class, 'showServicesPublicPage'])
        ->name(RoutesNames::SERVICES_PUBLIC->value);
});
