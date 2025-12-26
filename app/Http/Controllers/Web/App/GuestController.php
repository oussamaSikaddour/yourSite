<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Service; // Make sure you have a Service model

class GuestController extends Controller
{
    public function showServiceDetailsPage(Request $request): View
    {
        $serviceId = $request->query('id');
        $fromServicesPublic = (bool) $request->query('formServicesPublic', false);

        abort_if(! $serviceId, 404, __('errors.service.missing_parameters'));

        $service = Service::findOrFail($serviceId);

        $title = __('pages.service_details_public.name');

        // Polymorphic bindings
        $articleableId   = $service->id;
        $articleableType = Service::class;

        $breadcrumbLinks = [
            ['route' => 'index', 'label' => __('pages.landing_page.name')],
            ...(
                $fromServicesPublic
                ? [['route' => 'services_public', 'label' => __('pages.services_public.name')]]
                : []
            ),
            ['route' => 'service_details_public', 'label' => __('pages.service_details_public.name')],
        ];

        return view(
            'pages.app.service',
            compact(
                'title',
                'service',
                'articleableId',
                'articleableType',
                'breadcrumbLinks'
            )
        );
    }

    public function showServicesPublicPage(): View
    {

        // Page title (localized)

        $breadcrumbLinks = [
            ['route' => 'index',      'label' => __('pages.landing_page.name')],
            ['route' => 'services_public', 'label' => __('pages.services_public.name')]
        ];

        $title = __('pages.services_public.name');
        return view('pages.app.services', compact('title', 'breadcrumbLinks'));
    }
}
