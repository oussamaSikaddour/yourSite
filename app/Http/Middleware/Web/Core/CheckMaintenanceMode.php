<?php

namespace App\Http\Middleware\Web\Core;

use App\Enum\Core\Web\RoutesNames;
use App\Models\GeneralSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * This middleware checks if the application is in maintenance mode (configured via GeneralSettings).
     * If so, it redirects users to a custom maintenance page.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 🔒 Read the current maintenance mode status from the DB (or cache layer optionally)
        $isMaintenanceMode = GeneralSetting::first()->maintenance;

        // 🚧 If site is in maintenance mode, redirect user to a custom page
        if ($isMaintenanceMode) {
            return redirect()->route(RoutesNames::IS_ON_MAINTENANCE_MODE->value);
        }

        // ✅ Continue processing the request
        return $next($request);
    }
}
