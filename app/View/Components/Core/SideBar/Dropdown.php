<?php

namespace App\View\Components\Core\SideBar;

use App\Traits\Core\Common\GeneralTrait;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Dropdown extends Component
{
    use GeneralTrait;

    private const HTML_ID_PREFIX = 'dropdown';
    private const ICONS_CONFIG_KEY = 'core.icons.FONTAWESOME';

    public string $renderedItems;
    public array $icons;
    public string $htmlId;
    public string $subItemsId;

    public function __construct(
        public array $items = [],
        public string $dropdownLink = '',
        public int $badge = 0,
        public string $badgeVariant = ''
    ) {
        $this->icons = config(self::ICONS_CONFIG_KEY, []);
        $this->htmlId = $this->generateHtmlId();
        $this->subItemsId = "{$this->htmlId}-subItems";
        $this->renderedItems = $this->renderItems();
    }

    /**
     * Generate a unique, valid HTML id.
     */
    protected function generateHtmlId(): string
    {
        return self::HTML_ID_PREFIX . '-' . Str::random(8);
    }

    /**
     * Render the dropdown menu items.
     */
    protected function renderItems(): string
    {
        if (empty($this->items)) {
            return '';
        }

        return collect($this->items)->map(fn($item) => $this->renderItem($item))->implode('');
    }

    /**
     * Render a single dropdown item.
     */
    protected function renderItem(array $item): string
    {
        $isDirectLink = $item['directLink'] ?? false;
        $routeUrl = $this->resolveRouteUrl($item, $isDirectLink);
        $isActive = !$isDirectLink && $this->isRouteActive($item['route'] ?? '');
        $label = e($item['label'] ?? '');
        $iconHtml = $this->getIconHtml($item['icon'] ?? null);
        $badgeHtml = $this->renderItemBadge($item);
        $activeClass = $isActive ? 'active' : '';

        return <<<HTML
            <li role="none" class="{$activeClass}">
                <a href="{$routeUrl}" role="menuitem">
                    {$label}
                    {$badgeHtml}
                 {$iconHtml}
                </a>
            </li>
        HTML;
    }

    /**
     * Render badge for an individual dropdown item.
     */
    protected function renderItemBadge(array $item): string
    {
        $badgeValue = $item['badge'] ?? null;
        $badgeVariant = $item['badgeVariant'] ?? '';

        if (empty($badgeValue)) {
            return '';
        }

        $badgeClass = !empty($badgeVariant) ? "badge badge--{$badgeVariant}" : 'badge';
        $escapedBadgeValue = e($badgeValue);

        return <<<HTML
            <span class="{$badgeClass}">{$escapedBadgeValue}</span>
        HTML;
    }

    /**
     * Resolve the route URL for an item.
     */
    protected function resolveRouteUrl(array $item, bool $isDirectLink): string
    {
        if ($isDirectLink) {
            return e($item['route'] ?? '#');
        }

        $routeName = $this->resolveRouteName($item['route'] ?? '');

        if (!$routeName) {
            return '#';
        }

        try {
            return e(route($routeName, $item['parameters'] ?? []));
        } catch (\Exception $e) {
            return '#';
        }
    }

    /**
     * Check if a route is active.
     */
    protected function isRouteActive(string $routeName): bool
    {
        return !empty($routeName) && request()->routeIs($routeName);
    }

    /**
     * Render an icon HTML element if defined.
     */
    protected function getIconHtml(?string $icon): string
    {
        if (empty($icon) || !isset($this->icons[$icon])) {
            return '';
        }

        return sprintf('<span aria-hidden="true" class="item-icon">%s</span>', $this->icons[$icon]);
    }

    /**
     * Get the main badge class string.
     */
    public function mainBadgeClass(): string
    {
        return $this->badgeVariant ? "badge badge--{$this->badgeVariant}" : 'badge';
    }

    /**
     * Determine if main badge should be shown.
     */
    public function shouldShowMainBadge(): bool
    {
        return $this->badge > 0;
    }

    /**
     * Render the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.core.side-bar.dropdown');
    }
}
