<?php

namespace App\View\Components\Core\Nav;

use App\Traits\Core\Common\GeneralTrait;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DropdownLink extends Component
{
    use GeneralTrait;

    public string $renderedItems;
    public array $icons;
    public string $htmlId;

    public function __construct(
        public array $items = [],
        public string $dropdownLink = ''
    ) {
        $this->icons = config('core.icons.FONTAWESOME', []);
        $this->renderedItems = $this->renderItems($items);
        $this->htmlId = $this->generateHtmlId('dropdown'); // random HTML id
    }

    /**
     * Generate a unique, valid HTML id.
     */
    protected function generateHtmlId(string $prefix = 'id', int $bytes = 4): string
    {
        return $prefix . '-' . substr(bin2hex(random_bytes($bytes)), 0, $bytes * 2);
    }

    /**
     * Render the dropdown menu items.
     */
    protected function renderItems(array $items): string
    {
        return collect($items)->map(function ($item) {
            $isDirectLink = $item['directLink'] ?? false;
            $routeUrl = $this->resolveRouteUrl($item, $isDirectLink);
            $isActive = !$isDirectLink && $this->isRouteActive($item['route'] ?? '');

            return sprintf(
                '<li role="none" class="%s"><a href="%s" role="menuitem">%s%s</a></li>',
                e($isActive ? 'active' : ''),
                e($routeUrl),
                e($item['label'] ?? ''),
                $this->getIconHtml($item['icon'] ?? null)
            );
        })->implode('');
    }

    /**
     * Resolve the route URL for an item.
     */
    protected function resolveRouteUrl(array $item, bool $isDirectLink): string
    {

        if ($isDirectLink) {
            return $item['route'] ?? '#';
        }
        $routeName = $this->resolveRouteName($item['route'] ?? '');

        return $routeName ? route($routeName, $item['parameters'] ?? []) : '#';
    }

    /**
     * Check if a route is active.
     */
    protected function isRouteActive(string $routeName): bool
    {
        return $routeName && request()->routeIs($routeName);
    }

    /**
     * Render an icon HTML element if defined.
     */
    protected function getIconHtml(?string $icon): string
    {
        return $icon && isset($this->icons[$icon])
            ? sprintf('<span aria-hidden="true">%s</span>', $this->icons[$icon])
            : '';
    }

    /**
     * Render the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.core.nav.dropdown-link', [
            'dropdownLink'   => $this->dropdownLink,
            'renderedItems'  => $this->renderedItems,
            'htmlId'         => $this->htmlId,
        ]);
    }
}
