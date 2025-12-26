<?php

namespace App\View\Components\Core;

use App\Traits\Core\Common\GeneralTrait;

use Illuminate\View\Component;

class DashboardLink extends Component
{
    use GeneralTrait;
    public string $routeUrl;

    public function __construct(
        public string $route,
        public string $label,
        public string $img,
        public bool $app=false,
        public array $parameters = [],
    ) {

        // Resolve the route name from the enum or use it directly
        $routeName = $this->resolveRouteName($route);

        // Generate the route URL and determine if it's active
        $this->routeUrl = $routeName ? route($routeName, $this->parameters) : '#';

    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.core.dashboard-link',
        [
            'routeUrl' => $this->routeUrl,
        ]

    );
    }

}
