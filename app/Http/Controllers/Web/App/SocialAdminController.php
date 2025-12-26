<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SocialAdminController extends Controller
{

    public function showBonusesPage(): View
    {

        $user = Auth::user();
        $title = __("pages.bonuses.name");
        $modalTitle = "modals.bonus.actions.add";
        $modalContent = [
            "name" => 'app.social-admin.bonus-modal',
            "parameters" => [],
        ];

                        $breadcrumbLinks = [

            ['route' => 'dashboard', 'label' => __('pages.dashboard.name')],
            ['route' => 'bonuses', 'label' => __('pages.bonuses.name')],

        ];
        return view('pages.app.social-admin.bonuses', compact('title', 'modalTitle', 'modalContent','breadcrumbLinks'));
    }


    public function showManageSocialWorksPage()
    {
        $user = Auth::user();

        $title = __("pages.manage_social_works.name");

        // Modal 1: Global Transfer
        $modalTitle = "modals.global_transfer.actions.add";
        $modalContent = [
            'name' => 'app.social_admin.global-transfer-modal',
            'parameters' => [
                'userId' => $user->id,
            ],
        ];

        // Get and cache General Settings (transformed to array)
        $app = Cache::rememberForever('general_settings', function () {
            $setting = GeneralSetting::where('maintenance', false)->first();
            return $setting ? $setting->toArray() : [];
        });

        // Modal 2: Banking Information
        $modalTitle2 = "modals.banking_info.actions.add";
        $modalContent2 = [
            'name' => 'core.admin.banking-information-modal',
            'parameters' => [
                'bankable' => $app,
                'bankableType' => 'app',
            ],
        ];


      $modalTitleOptions3 = ['name' => $app['acronym']];
        // Modal 3: User Management
        $modalTitle3 = "modals.user.actions.add.personnel";
        $modalContent3 = [
            'name' => 'default.user-modal',
            'parameters' => [],
        ];



                                $breadcrumbLinks = [

            ['route' => 'dashboard', 'label' => __('pages.dashboard.name')],
            ['route' => 'social_works_route', 'label' => __('pages.manage_social_works.name')],

        ];
        return view('pages.app.social-admin.social-works', compact(
            'title',
            'modalTitle',
            'modalContent',
            'modalTitle2',
            'modalContent2',
            'modalTitle3',
            'modalContent3',
            'modalTitleOptions3',
            'breadcrumbLinks'
        ));
    }


    public function showGlobalTransferDetailsPage(Request $request)
    {

        // Get all query parameters from the request
        $parameters = $request->query();
        $user = Auth::user();
        $establishmentId = $user->establishment_id;
        // Check if both 'id' and 'name' keys exist in the parameters
        if (array_key_exists('id', $parameters) && array_key_exists('motive', $parameters)) {
            // Proceed if both keys are present
            $title = __("pages.global_transfer_details.name", [
                "motive" => $parameters['motive'],
            ]);



            $modalTitle = "modals.transfer.actions.add";

            $modalContent = [
                "name" => 'app.social_admin.transfer-modal',
                "parameters" => [
                    "globalTransferId" => $parameters['id'],
                ],
            ];
            return view('pages.app.social-admin.global-transfers-details', compact('title', 'modalTitle', 'modalContent', 'parameters'));
        }

        // Handle the case when one or both keys are missing
        return redirect()->back()->withErrors(__('Invalid request parameters.'));
    }
}
