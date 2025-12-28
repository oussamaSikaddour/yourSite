<?php

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Web\Core\CoreController;
use Illuminate\Support\Facades\Route;

Route::get('lang/{lang}', [CoreController::class, 'setLang'])
    ->where('lang', 'en|fr|ar') // Validate language parameter
    ->name(RoutesNames::SET_LANG->value);

Route::get('/maintenanceMode', [CoreController::class, 'showIsOnMaintenanceModePage'])
    ->name(RoutesNames::IS_ON_MAINTENANCE_MODE->value);
Route::get('/toggle-status', [CoreController::class, 'showToggleAccountStatusPage'])
    ->name(RoutesNames::TOGGLE_ACCOUNT_STATUS->value);
Route::group(['middleware' => 'maintenance'], function () {
    Route::get('/', [CoreController::class, 'showIndexPage'])
        ->name(RoutesNames::INDEX->value);
});
