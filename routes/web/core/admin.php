<?php

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Web\Core\AdminController;
use Illuminate\Support\Facades\Route;

// ===========================================================
// Admin-only Web Routes
// Prefix: / (root)
// Middleware:
//   - auth           → user must be authenticated
//   - maintenance    → app must not be in maintenance mode
//   - can:admin-access → user must have admin permission
// ===========================================================

Route::group(['middleware' => ['auth', 'maintenance', 'web.account.active', 'can:admin-access']], function () {

    // Menus list view
    // URL: /menus
    // Name: admin.menus
    Route::get('/managePersons', [AdminController::class, 'showManagePersonsPage'])
        ->name(RoutesNames::PERSONS_ROUTE->value);
    Route::get('/ManageUsers', [AdminController::class, 'showManageUsersPage'])
        ->name(RoutesNames::USERS_ROUTE->value);
    Route::get('/menus', [AdminController::class, 'showMenusPage'])
        ->name(RoutesNames::MENUS_ROUTE->value);

    // Single menu form/edit
    // URL: /menu
    // Name: admin.menu
    Route::get('/menu', [AdminController::class, 'showMenuPage'])
        ->name(RoutesNames::MENU_ROUTE->value);

    // Sliders list view
    // URL: /sliders
    // Name: admin.sliders


});
