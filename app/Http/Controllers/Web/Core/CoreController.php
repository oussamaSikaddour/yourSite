<?php

namespace App\Http\Controllers\Web\Core;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CoreController extends Controller
{
    /**
     * Display the Index Page.
     *
     * @return View The landing/index page view
     */
    public function showIndexPage(): View
    {
        // Get 5 random services with their icon
        $services = Service::with('icon')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $hero = Hero::with('images')->first();
        // Page title (localized)
        $title = __('pages.landing_page.name');

        $forLandingPage=true;
        // Return view with data
        return view('pages.core.index', compact('title', 'services', 'hero', 'forLandingPage'));
    }

    /**
     * Set the Application Language.
     *
     * @param string $lang The requested language code
     * @return RedirectResponse Redirect back to the previous page
     */
    public function setLang(string $lang): RedirectResponse
    {
        // Define supported locale options
        $supportedLocales = ['en', 'fr', 'ar'];

        // Abort with a 400 Bad Request if the provided locale is not supported
        if (!in_array($lang, $supportedLocales)) {
            abort(400, 'Unsupported language.');
        }

        // Set the application locale for the current request
        app()->setLocale($lang);

        // Store the selected locale in the session for persistence
        session()->put('locale', $lang);

        // Redirect back to the previous page
        return redirect()->back();
    }

    /**
     * Display the Maintenance Mode Page.
     *
     * @return View The maintenance mode view
     */
    public function showIsOnMaintenanceModePage(): View
    {
        // Set the page title using localization
        $title = __("pages.maintenance-mode.page-title");

        // Return the 'pages.maintenance-mode' view with the title
        return view('pages.core.maintenance-mode', compact('title'));
    }
    public function showToggleAccountStatusPage(): View
    {
        // Set the page title using localization
        $title = __("pages.toggle-account-status.page-title");

        // Return the 'pages.maintenance-mode' view with the title
        return view('pages.core.toggle-account-status', compact('title'));
    }
}
