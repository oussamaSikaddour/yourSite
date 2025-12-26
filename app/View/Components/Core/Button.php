<?php

namespace App\View\Components\Core;

use App\Traits\Core\Common\GeneralTrait;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Button extends Component
{
    use GeneralTrait;

    public string $htmlId;
    public string $iconHtml;
    public ?string $routeUrl = null;

    public function __construct(
        public ?string $type = 'button',
        public ?string $text = null,
        public string $tooltip = '',
        public bool $hasTooltip = false,
        public string $icon = '',
        public bool $rounded = false,
        public bool $fullWidth = false,
        public ?string $href = null,
        public ?string $route = null,
        public ?string $variant =null,
        public ?string $function = null,
        public array $parameters = [],
        public array $routeParameters = [],
        public array $wireTargets = [],
        public bool $disabled = false,
        public bool $newTab = false,
        public bool $expectLoading = false,
        public bool $prevent = false, // NEW
        public array $extraClasses =[],
    ) {
        $this->htmlId = 'btn-' . Str::random(8);
        $this->iconHtml = $this->resolveIconHtml($icon);

        // Resolve route only if provided
        if ($this->route) {
            $routeName = $this->resolveRouteName($this->route);
            $this->routeUrl = $routeName
                ? route($routeName, $this->routeParameters)
                : null;
        }
    }

    private function resolveIconHtml(string $icon): string
    {
        if (!$icon) {
            return '';
        }

        $fromConfig = config("core.icons.FONTAWESOME.$icon");

        if (!$fromConfig) {
            Log::warning("Icon '$icon' not found in config.");
            return '<i class="fa-solid fa-question"></i>';
        }

        return $fromConfig;
    }

    public function render()
    {
        return view('components.core.button');
    }
}
