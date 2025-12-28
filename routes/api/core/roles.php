<?php

use App\Http\Controllers\Api\Core\RoleController;
use Illuminate\Support\Facades\Route;

// =======================================================
// Locale-aware, authenticated & permission-protected routes
// Prefix: /api/{locale}
// Middleware:
//   - apiMaintenance → blocks access during maintenance
//   - auth:sanctum → requires user to be authenticated
//   - can:super-admin-access → requires super admin privilege
// =======================================================

Route::group([
  "prefix" => "api/{locale}",
  'middleware' => ['apiMaintenance', 'auth:sanctum','api.account.active', 'can:super-admin-access']
], function () {

    // Get all roles (for dropdowns, assignments, etc.)
    // GET /api/{locale}/roles
    Route::apiResource('roles', RoleController::class)->only('index');

    // Manage roles for users or permissions
    // POST /api/{locale}/manage-roles
    // Expected to handle assigning/removing roles to/from users
    Route::post('manage-roles', [RoleController::class, 'manageRoles']);
});
