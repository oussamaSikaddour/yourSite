<?php

namespace App\Http\Controllers\Web\App;

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    use GeneralTrait;

public function showServicePage(Request $request)
{
    $serviceId      = $request->query('id');

    $showBreadcrumb = (bool) $request->query('showBreadcrumb', false);

    abort_if(! $serviceId, 404, __('errors.service.missing_parameters'));

    $service = Service::findOrFail($serviceId);

    // Polymorphic bindings
    $articleableId   = $sliderableId   = $service->id;
    $articleableType = $sliderableType = Service::class;

    $title           = __('pages.articles.name');
    $containsTinyMce = true;

    // Breadcrumbs
    $breadcrumbLinks = $showBreadcrumb
        ? [
            ['route' => 'dashboard',      'label' => __('pages.dashboard.name')],
            ['route' => 'services_route', 'label' => __('pages.services.name')],
            [
                'route' => 'service_route',
                'label' => __('pages.service.name', ['name' => $service->name]),
            ],
        ]
        : [];

    // Article modal
    $articleModalTitle = __('modals.article.actions.add');
    $articleModalContent = [
        'name'       => 'core.author.article-modal',
        'parameters' => compact('articleableId', 'articleableType'),
    ];

    // Slider modal
    $sliderModalTitle = __('modals.slider.actions.add');
    $sliderModalContent = [
        'name'       => 'core.slider-modal',
        'parameters' => compact('sliderableId', 'sliderableType'),
    ];

    $modalTitleOptions = ['name' => $service->name];

    return view(
        'pages.app.author.service',
        compact(
            'title',
            'containsTinyMce',

            'articleModalTitle',
            'articleModalContent',

            'sliderModalTitle',
            'sliderModalContent',

            'modalTitleOptions',

            'articleableId',
            'articleableType',
            'sliderableId',
            'sliderableType',

            'breadcrumbLinks'
        )
    );
}

}
