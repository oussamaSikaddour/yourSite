<?php

use App\Http\Middleware\Api\Core\CheckMaintenanceModeApi;
use App\Http\Middleware\Api\Core\EnsureAccountIsActiveApi;
use App\Http\Middleware\Api\Core\SetLocaleApi;
use App\Http\Middleware\Web\Core\CheckMaintenanceMode;
use App\Http\Middleware\Web\Core\EnsureAccountIsActiveWeb;
use App\Http\Middleware\Web\Core\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

// Bootstrap and configure the Laravel application
return Application::configure(basePath: dirname(__DIR__))

    // Define routing files
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up', // health check route for uptime monitoring

        // Additional route grouping
        then: function () {
            // Group and include role-based web routes
            Route::middleware('web')->group(function () {
                foreach (
                    [
                        'routes/web/core/guest.php',
                        'routes/web/core/user.php',
                        'routes/web/core/admin.php',
                        'routes/web/core/author.php',
                        'routes/web/core/superAdmin.php',
                        //app
                        'routes/web/app/guest.php',
                        'routes/web/app/admin.php',
                        'routes/web/app/author.php',
                        'routes/web/app/socialAdmin.php',

                    ] as $file
                ) {
                    require base_path($file);
                }
            });

            // Group and include modular API routes
            Route::middleware('api')->group(function () {
                foreach (
                    [
                        'routes/api/core/auth.php',
                        'routes/api/core/users.php',
                        'routes/api/core/roles.php',
                        'routes/api/core/occupations.php',
                        'routes/api/core/bankingInformation.php',
                        'routes/api/core/persons.php',
                    ] as $file
                ) {
                    require base_path($file);
                }
            });
        }
    )

    // Define middleware
    ->withMiddleware(function (Middleware $middleware) {
        // Global web middleware
        $middleware->web([
            SetLocale::class, // Automatically sets locale from request/session
        ]);

        // Global API middleware
        $middleware->api([
            SetLocaleApi::class, // Same as above, but for API
        ]);

        // Middleware aliases for easy reuse in routes
        $middleware->alias([
            'maintenance' => CheckMaintenanceMode::class,
            'apiMaintenance' => CheckMaintenanceModeApi::class,
            'web.account.active' => EnsureAccountIsActiveWeb::class,
            'api.account.active' => EnsureAccountIsActiveApi::class,
        ]);
    })

    // Handle exception rendering
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return match ($e->getStatusCode()) {
                403 => response()->view('errors.403', [], 403), // Forbidden
                404 => response()->view('errors.404', [], 404), // Not Found
                419 => response()->view('errors.419', [], 419), // CSRF token expired
                429 => response()->view('errors.429', [], 429), // Too Many Requests
                500 => response()->view('errors.500', [], 500), // Internal Server Error
                503 => response()->view('errors.503', [], 503), // Service Unavailable (e.g. maintenance)
                default => null, // Let Laravel handle other exceptions
            };
        });
    })
    ->create(); // Finally, create and return the Application instance
