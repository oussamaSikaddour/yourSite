<?php

namespace App\View\Components\Core\SideBar;

use App\Traits\Core\Common\GeneralTrait;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Item extends Component
{
    use GeneralTrait;

    public string $routeUrl;
    public string $activeClass;
    public ?string $iconHtml;

    public function __construct(
        public string $route,
        public string $routeName,
        public string $icon,
        public ?string $badge = null,
        public string $badgeClass = '',
        public array $parameters = []
    ) {
        $this->initialize();
    }

    /**
     * Initialize component properties.
     */
    private function initialize(): void
    {
        // Resolve the route URL and active class
        $routeName = $this->resolveRouteName($this->route);
        $this->routeUrl = $routeName ? route($routeName, $this->parameters) : '#';
        $this->activeClass = $routeName && Route::is($routeName) ? 'active' : '';

        // Resolve the icon HTML
        $iconsArray = config('core.icons.FONTAWESOME')?? [];
        $this->iconHtml = $iconsArray[$this->icon] ?? null;
    }

    /**
     * Render the view with precomputed data.
     */
    public function render(): View|Closure|string
    {
        return view('components.core.side-bar.item', [
            'routeUrl' => $this->routeUrl,
            'activeClass' => $this->activeClass,
            'iconHtml' => $this->iconHtml,
        ]);
    }
}
