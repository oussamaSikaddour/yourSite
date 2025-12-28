<?php

namespace App\Providers;

use App\Enum\Core\Web\RoutesNames;
use App\Policies\Core\UserPolicy;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // No additional services to register here
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Redirect unauthenticated users to login route
        Authenticate::redirectUsing(
            fn ($request) => route(RoutesNames::LOGIN->value)
        );

        $this->definePolicyGates();
    }

    /**
     * Define gates that use UserPolicy methods.
     */
    protected function definePolicyGates(): void
    {
        $gates = [
            'super-admin-access' => 'isSuperAdmin',
            'admin-access'       => 'isAdmin',
            'author-access'      => 'isAuthor',
            'user-access'        => 'isUser',
            'approver-access'    => 'isApprover',
            'admin-or-author-access'=>"isAdminOrAuthor",
            'social-admin-access'=>"isSocialAdmin",

        ];

        foreach ($gates as $gate => $method) {
            Gate::define($gate, [UserPolicy::class, $method]);
        }
    }

}
