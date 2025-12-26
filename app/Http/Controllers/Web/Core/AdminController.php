<?php

namespace App\Http\Controllers\Web\Core;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display the Admin (Establishment) Page.
     *
     * @return View
     */
    public function showManagePersonsPage(): View
    {
        $title = __("pages.manage_persons.name"); // Localized dashboard title

        // Modal configuration to add a user
        $modalTitle = "modals.person.actions.add";
        $modalContent = [
            "name" => 'core.person-modal',
            "parameters" => [],
        ];

        $breadcrumbLinks = [

            ['route' => 'dashboard', 'label' => __('pages.dashboard.name')],
            ['route' => 'persons_route', 'label' => __('pages.manage_persons.name')],

        ];
        return view('pages.core.admin.persons', compact('title', 'modalTitle', 'modalContent', 'breadcrumbLinks'));
    }
    public function showManageUsersPage(): View
    {
        $title = __("pages.users.name"); // Localized dashboard title

        // Modal configuration to add a user
        $modalTitle = "modals.user.actions.add";
        $modalContent = [
            "name" => 'core.user-modal',
            "parameters" => [],
        ];

        $breadcrumbLinks = [

            ['route' => 'dashboard', 'label' => __('pages.dashboard.name')],
            ['route' => 'users_route', 'label' => __('pages.manage_users.name')],

        ];
        return view('pages.core.admin.users', compact('title', 'modalTitle', 'modalContent', 'breadcrumbLinks'));
    }


    /**
     * Display the Menus Page.
     *
     * @return View
     */
    public function showMenusPage(): View
    {
        $user = Auth::user(); // Retrieve the authenticated user (not used here)
        $title = __("pages.menus.name"); // Localized title

        // Modal configuration for adding a menu
        $modalTitle = "modals.menu.actions.add";
        $modalContent = [
            "name" => 'core.admin.menu-modal',
            "parameters" => [],
        ];

        // Return the menus admin view
        return view('pages.core.admin.menus', compact('title', 'modalTitle', 'modalContent'));
    }

    /**
     * Display a specific Menu Page with query parameters.
     *
     * @param Request $request
     * @return View|null
     */
    public function showMenuPage(Request $request)
    {
        $parameters = $request->query(); // Get all query parameters from the URL

        // Check if required parameters exist
        if (array_key_exists('id', $parameters) && array_key_exists('title', $parameters)) {
            // Generate localized title using dynamic title
            $title = __("pages.menu.name", [
                "title" => $parameters['title'],
            ]);

            // Modal configuration for adding external links to a menu
            $modalTitle = "modals.external_link.actions.add";
            $modalContent = [
                "name" => 'core.admin.external-link-modal',
                "parameters" => [
                    "menuId" => $parameters['id'],
                ],
            ];

            // Return the specific menu view with dynamic data
            return view('pages.core.admin.menu', compact('title', 'modalTitle', 'modalContent', 'parameters'));
        }
    }
}
