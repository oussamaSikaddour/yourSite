<?php

namespace App\View\Components\Core\Nav;

use App\Traits\Core\Common\GeneralTrait;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Link extends Component
{
    use GeneralTrait;

    public string $routeUrl;
    public string $active;

    public function __construct(
        public string $route,       // can be route name OR url
        public string $label,
        public array $parameters = [],
        public ?string $span = null,
        public string $type = 'default'
    ) {
        // 🔹 If it's a URL or anchor → use it directly
        if ($this->isRawLink($route)) {
            $this->routeUrl = $route;
            $this->active = request()->url() === $route ? 'active' : '';
            return;
        }

        // 🔹 Otherwise treat it as Laravel route
        $routeName = $this->resolveRouteName($route);

        $this->routeUrl = $routeName
            ? route($routeName, $this->parameters)
            : '#';

        $this->active = $routeName && Route::is($routeName) ? 'active' : '';
    }

    protected function isRawLink(string $value): bool
    {
        return Str::startsWith($value, ['http://', 'https://', '/', '#']);
    }

    public function render(): View|Closure|string
    {
        return view('components.core.nav.link', [
            'active'   => $this->active,
            'routeUrl' => $this->routeUrl,
            'type'     => $this->type,
        ]);
    }
}
