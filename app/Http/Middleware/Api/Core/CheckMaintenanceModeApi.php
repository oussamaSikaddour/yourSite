<?php

namespace App\Http\Middleware\Api\Core;

use App\Models\GeneralSetting;
use App\Traits\Core\Api\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceModeApi
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * This middleware checks if the application is in maintenance mode
     * based on the "maintenance" flag in the general settings table.
     *
     * If so, it halts the request and returns a success response with
     * a localized message (not an error) indicating that maintenance is ongoing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Retrieve the first (singleton) general settings record
        $generalSettings = GeneralSetting::first();

        // 🛠️ Check if maintenance mode is enabled
        if ($generalSettings && $generalSettings->maintenance) {
            return $this->responseSuccess(
                'Site Maintenance', // Message for devs
                null,               // No data payload
                [
                    // 🎯 Translated message (e.g., for UI display)
                    'message' => __('forms.site_parameters.responses.success')
                ]
            );
        }

        // ✅ Proceed with the request if no maintenance
        return $next($request);
    }
}
