<?php

namespace App\Http\Controllers\Web\Core;

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Controller;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    use GeneralTrait;
    /**
     * Display the Site Parameters Page.
     *
     * @return View
     */
    public function showSiteParametersPage(): View
    {
        $title = __("pages.site_parameters.name"); // Localized title for site parameters
        return view('pages.core.site-parameters', compact('title'));
    }


    /**
     * Show the Super Admin Dashboard.
     *
     * @return View
     */
    public function showOccupationsFieldsPage(): View
    {
        $title = __("pages.occupation_fields.name"); // Localized dashboard title

        // Modal configuration to add a user
        $modalTitle = "modals.field.actions.new";
        $modalContent = [
            "name" => 'core.super-admin.field-modal',
            "parameters" => [],
        ];

        return view('pages.core.super-admin.occupations-fields', compact('title', 'modalTitle', 'modalContent'));
    }
    /**
     * Show the Super Admin Dashboard.
     *
     * @return View
     */
    public function showWilayatesPage(): View
    {
        $title = __("pages.wilayates.name"); // Localized dashboard title

        // Modal configuration to add a user
        $modalTitle = "modals.wilaya.actions.new";
        $modalContent = [
            "name" => 'core.super-admin.wilaya-modal',
            "parameters" => [],
        ];

        return view('pages.core.super-admin.wilayates', compact('title', 'modalTitle', 'modalContent'));
    }
    /**
     * Show the Super Admin Dashboard.
     *
     * @return View
     */
    public function showWilayaPage(Request $request)
    {
        $parameters = $request->query(); // Get all query parameters

        // Check for required slider parameters
        if (array_key_exists('id', $parameters) && array_key_exists('code', $parameters)) {
            // Generate localized title using slider name
            $title = __("pages.wilaya.name", [
                "code" => $parameters['code'],
            ]);
            // Modal configuration for adding a slide to the slider
            $modalTitle = "modals.daira.actions.add";
            $modalContent = [
                "name" => 'core.super-admin.daira-modal',
                "parameters" => [
                    'wilayaId' => $parameters['id']
                ],
            ];

            // Return the slides view with editor and modal config
            return view('pages.core.super-admin.wilaya', compact('title', 'modalTitle', 'modalContent', 'parameters'));
        }
    }


    /**
     * Show the Banks Management Page.
     *
     * @return View
     */
    public function showBanksPage(): View
    {
        $title = __("pages.banks.name"); // Localized banks page title

        // Modal configuration for adding a bank
        $modalTitle = "modals.bank.actions.add";
        $modalContent = [
            "name" => 'core.super-admin.bank-modal',
            "parameters" => [],
        ];

        return view('pages.core.super-admin.banks', compact('title', 'modalTitle', 'modalContent'));
    }

    /**
     * Show the Messages Page.
     *
     * @return View
     */
    public function showMessagesPage(): View
    {
        $title = __("pages.messages.name"); // Localized messages page title
        return view('pages.core.super-admin.messages', compact('title'));
    }

    /**
     * Show the Landing Scene General Info Page.
     *
     * @return View
     */
    public function showGeneralInfosPage(): View
    {

        $breadcrumbLinks = [

            ['route' => 'dashboard', 'label' => __('pages.dashboard.name')],
            ['route' => 'general_infos', 'label' => __('pages.general_infos.name')],

        ];
        $title = __("pages.general_infos.name"); // Localized general info title
        return view('pages.core.super-admin.general-infos', compact('title', 'breadcrumbLinks'));
    }

    /**
     * Show the Hero Scene Management Page.
     *
     * @return View
     */
    public function showManageHeroScene(): View
    {

                $breadcrumbLinks = [

            ['route' => 'dashboard', 'label' => __('pages.dashboard.name')],
            ['route' => 'manage_hero', 'label' => __('pages.manage_hero.name')],

        ];
        $title = __("pages.manage_hero.name"); // Localized hero section title
        return view('pages.core.super-admin.manage-section-hero', compact('title' ,'breadcrumbLinks'));
    }

    /**
     * Show the About Us Scene Management Page.
     *
     * @return View
     */
    public function showManageAboutUsScene(): View
    {

                $breadcrumbLinks = [

            ['route' => 'dashboard', 'label' => __('pages.dashboard.name')],
            ['route' => 'manage_about_us', 'label' => __('pages.manage_about_us.name')],

        ];
        $title = __("pages.manage_about_us.name"); // Localized about-us section title
        return view('pages.core.super-admin.manage-section-about-us', compact('title' ,'breadcrumbLinks'));
    }

    /**
     * Show the "Our Qualities" Section Management Page.
     *
     * @return View
     */
    public function showManageOurQualitiesPage(): View
    {
        $title = __("pages.manage_our_qualities.name"); // Localized qualities section title

        // Modal configuration for adding a new quality
        $modalTitle = "modals.our_quality.actions.new";
        $modalContent = [
            "name" => 'core.super-admin.our-quality-modal',
            "parameters" => [],
        ];

                $breadcrumbLinks = [

            ['route' => 'dashboard', 'label' => __('pages.dashboard.name')],
            ['route' => 'manage_our_qualities', 'label' => __('pages.manage_our_qualities.name')],

        ];
        return view('pages.core.super-admin.our-qualities', compact('title', 'modalTitle', 'modalContent','breadcrumbLinks'));
    }
}
