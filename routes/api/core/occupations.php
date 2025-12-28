<?php

use App\Http\Controllers\Api\Core\OccupationController;
use Illuminate\Support\Facades\Route;

// ==========================================================
// Locale-aware, protected API routes for Occupations
// Nested under: /api/{locale}/occupations
// Middleware:
//   - auth:sanctum → ensures the user is authenticated
//   - apiMaintenance → blocks access if the app is in maintenance mode
//
// These routes are scoped to a specific user ID.
// ==========================================================

Route::group([
    'prefix' => 'api/{locale}/occupations',
    'middleware' => ['auth:sanctum', 'apiMaintenance','api.account.active'],
], function () {

    // Get all occupations for a given user
    // GET /api/{locale}/occupations/{person}
    Route::get('{person}', [OccupationController::class, 'index']);

    // Store a new occupation for a given user
    // POST /api/{locale}/occupations/{person}
    Route::post('{person}', [OccupationController::class, 'store']);

    // Show a specific occupation for a given user
    // GET /api/{locale}/occupations/{person}/{occupation}
    Route::get('{person}/{occupation}', [OccupationController::class, 'show']);

    // Update a specific occupation for a given user
    // PATCH /api/{locale}/occupations/{person}/{occupation}
    Route::patch('{person}/{occupation}', [OccupationController::class, 'update']);

    // Delete a specific occupation (no user ID needed for deletion)
    // DELETE /api/{locale}/occupations/{occupation}
    Route::delete('{occupation}', [OccupationController::class, 'destroy']);
});
