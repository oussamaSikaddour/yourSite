<?php

namespace App\Http\Controllers\Web\Core;

use App\Enum\Core\Web\RoutesNames;
use App\Http\Controllers\Controller;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    use GeneralTrait;


    public function showSlidersPage(Request $request): View
    {
        // Extract query parameters safely
        $sliderableId = $request->query('sliderableId');
        $sliderableType = $request->query('sliderableType');
        $sliderableName = $request->query('sliderableName');


        // Abort if either is missing
        abort_unless($sliderableId && $sliderableType, 404, 'Missing sliderableId or sliderableType.');

        // Localized page title
        $title = __('pages.sliders.name');

        // Modal configuration
        $modalTitle = __('modals.slider.actions.add');
        $modalTitleOptions =['name'=>$sliderableName];
        $modalContent = [
            'name' => 'core.slider-modal',
            'parameters' => [
                              'sliderableId' => $sliderableId,
                              'sliderableType' => $sliderableType]
                              ,
        ];

        // Return the admin sliders view
        return view('pages.core.author.sliders', compact(
            'title',
            'modalTitle',
            'modalTitleOptions',
            'modalContent',
            'sliderableId',
            'sliderableType',
            'sliderableName'

        ));
    }
    /**
     * Display a specific Slider Page with slides and editor.
     *
     * @param Request $request
     * @return View|null
     */
    public function showSliderPage(Request $request)
    {
        // Extract query parameters with defaults and safe access
        $sliderableId   = $request->query('sliderableId');
        $sliderableType = $request->query('sliderableType');
         $sliderableName = $request->query('sliderableName');
        $sliderId       = $request->query('id');
        $sliderName     = $request->query('name');


        // Abort early if required identifiers are missing
        abort_unless($sliderableId && $sliderableType, 404, __('errors.slider.missing_parameters'));

        // Prepare parameters array (clean and type-safe)


        // Localized page title (fallback-safe)
        $title = __('pages.slider.name', [
            'name' => $sliderName ?? __('pages.slider.default_name'),
        ]);

        // Modal configuration for adding a new slide
        $modalTitle = __('modals.slide.actions.add');
        $modalContent = [
            'name' => 'core.slide-modal',
            'parameters' => [
                'sliderId' => $sliderId,
            ],
        ];

        // Include TinyMCE editor flag
        $containsTinyMce = true;

        // Render view with clean compact variables
        return view('pages.core.author.slides', compact(
            'title',
            'modalTitle',
            'modalContent',
            'containsTinyMce',
            'sliderableId',
            'sliderableType',
            'sliderableName',
            'sliderId',
            'sliderName',
        ));
    }

    /**
     * Display the Articles Page for authors.
     *
     * @return View The view for managing articles
     */
    public function showArticlesPage(Request $request): View
    {

        $articleableId = $request->query('articleableId');
        $articleableType = $request->query('articleableType');
        $articleableName = $request->query('articleableName');
        // Abort if either is missing
        abort_unless($articleableId && $articleableType, 404, 'Missing sliderableId or sliderableType.');

        // Localized page title
        $title = __('pages.articles.name');

        // Modal configuration
        $modalTitle = __('modals.article.actions.add');
        $modalTitleOptions =['name'=>$articleableName];
        $modalContent = [
            'name' => 'core.author.article-modal',
            'parameters' => [
                'articleableId' => $articleableId,
                'articleableType' => $articleableType

            ],
        ];

        $containsTinyMce = true;
        // Return the admin sliders view
        return view('pages.core.author.articles', compact(
            'title',
            'modalTitle',
            'modalContent',
            'articleableId',
            'articleableType',
             'articleableName',
             'modalTitleOptions',
            'containsTinyMce'
        ));
    }

    /**
     * Display the Trends Page for authors.
     *
     * @return View The view for managing trends
     */
    public function showTrendsPage(): View
    {
        $title = __("pages.trends.name"); // Localized title for the trends page

        // Modal configuration for adding a new trend
        $modalTitle = "modals.trend.actions.add";
        $modalContent = [
            "name" => 'core.author.trend-modal',
            "parameters" => [],
        ];

        // Include TinyMCE editor in this view
        $containsTinyMce = true;

        // Return the trends view with modal and editor
        return view('pages.core.author.trends', compact('title', 'modalTitle', 'modalContent', 'containsTinyMce'));
    }
}
