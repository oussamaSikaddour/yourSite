<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

        public function showServicesPage(): View
    {
        $user = Auth::user(); // Retrieve the authenticated user (not used here)
        $title = __("pages.services.name"); // Localized title


        // Modal configuration for adding a service
        $modalTitle = "modals.service.actions.add";
        $modalContent = [
            "name" => 'app.admin.service-modal',
            "parameters" => [],
        ];

                $breadcrumbLinks = [

            ['route' => 'dashboard', 'label' => __('pages.dashboard.name')],
            ['route' => 'services_route', 'label' => __('pages.services.name')],

        ];
        // Return the services admin view
        return view('pages.app.admin.services', compact('title', 'modalTitle', 'modalContent','breadcrumbLinks'));
    }
}
