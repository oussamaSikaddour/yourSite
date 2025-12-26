<?php

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Web\Core\AuthorController;
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
    Route::get('/articles', [AuthorController::class, 'showArticlesPage'])
        ->name(RoutesNames::ARTICLES_ROUTE->value);
    Route::get('/trends', [AuthorController::class, 'showTrendsPage'])
        ->name(RoutesNames::TRENDS_ROUTE->value);

    Route::get('/sliders', [AuthorController::class, 'showSlidersPage'])
        ->name(RoutesNames::SLIDERS_ROUTE->value);

    Route::get('/slider', [AuthorController::class, 'showSliderPage'])
        ->name(RoutesNames::SLIDER_ROUTE->value);
});
