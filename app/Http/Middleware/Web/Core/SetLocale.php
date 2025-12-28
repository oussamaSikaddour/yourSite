<?php

namespace App\Http\Middleware\Web\Core;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * This middleware sets the application locale based on the session value.
     * It supports persistent localization for web routes.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 🌐 If a locale has been stored in the session, apply it to the app
        if (session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        }

        // ✅ Continue request lifecycle
        return $next($request);
    }
}
