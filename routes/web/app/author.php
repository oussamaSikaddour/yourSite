<?php

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Web\App\AuthorController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'auth',
        'maintenance',
        'web.account.active',
        'can:admin-or-author-access',
    ]
], function () {

    Route::get('/service', [AuthorController::class, 'showServicePage'])
        ->name(RoutesNames::SERVICE_ROUTE->value);

});
