<?php

namespace App\Http\Middleware\Api\Core;

use App\Traits\Core\Api\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActiveApi
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
                $user = Auth::user();

        if ($user && !$user->is_active) {
            // Optional: revoke token immediately
            $user->currentAccessToken()?->delete();

            return $this->responseError(
                'authorization',
                __('api.common.errors.deactivated_account'),
                'forbidden',
                403
            );
        }
        return $next($request);
    }
}
