<?php

namespace App\Http\Middleware\Api\Core;

use App\Traits\Core\Api\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;


class SetLocaleApi
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * This middleware sets the application's locale dynamically from the API route segment.
     *
     * Example: /api/v1/en/users → $request->segment(2) = 'en'
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(2); // 🌍 Extract locale from URI

        // 🔍 Validate that the locale exists in your configured options
        if (!$this->isValidLocale($locale)) {
            return $this->respondWithInvalidLocale(); // ❌ Return standardized error
        }

        // ✅ Set the Laravel application's locale
        App::setLocale($locale);

        // 👉 Proceed to the next middleware or controller
        return $next($request);
    }

    /**
     * Validate if the provided locale is available in app configuration.
     *
     * @param string|null $locale
     * @return bool
     */
    protected function isValidLocale(?string $locale): bool
    {
        if (empty($locale)) {
            return false;
        }

        // 🔄 Check against the available_locales array from config/app.php
        return in_array($locale, config('app.available_locales', []));
    }

    /**
     * Return a localized, structured error response for invalid locale.
     *
     * @return Response
     */
    protected function respondWithInvalidLocale(): Response
    {
        return $this->responseError('lang', __('api.common.errors.lang')); // ✨ Keyed error response
    }
}
