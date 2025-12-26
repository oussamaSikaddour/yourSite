
<?php

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Web\App\SocialAdminController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'maintenance', 'web.account.active', 'can:social-admin-access']], function () {
    Route::get('/SocialAdmin', [SocialAdminController::class, 'showManageSocialWorksPage'])
        ->name(RoutesNames::SOCIAL_WORKS_ROUTE->value);
    Route::get('/bankingInfos', [SocialAdminController::class, 'showEstablishmentBankingInformationPage'])
        ->name(RoutesNames::ESTABLISHMENT_BANKING_INFO_ROUTE->value);
    Route::get('/bonuses', [SocialAdminController::class, 'showBonusesPage'])
        ->name(RoutesNames::BONUSES_ROUTE->value);
    Route::get('/globalTransfers', [SocialAdminController::class, 'showGlobalTransfersPage'])
        ->name(RoutesNames::GLOBAL_TRANSFERS_ROUTE->value);
    Route::get('/globalTransfersDetails', [SocialAdminController::class, 'showGlobalTransferDetailsPage'])
        ->name(RoutesNames::GLOBAL_TRANSFERS_DETAILS_ROUTE->value);

});
