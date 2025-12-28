<?php

namespace App\Livewire\Core\Nav;

use App\Models\Menu;
use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ForPhone extends Component
{

  public bool $forLandingPage=false;




    #[Computed()]
    public function menus()
    {
        return Menu::query()
            ->where('state', "published")
            ->get();
    }




    protected function getRoutesOfMenu(Menu $menu): array
    {
        $defaultIcon = 'link';

        return [
            'dropdownName' => __('menus.nav', ['name' => $menu->title]) ?? __('menus.default'),
            'icon' => $menu->icon ?? $defaultIcon,
            'routes' => $menu->externalLinks
                ->map(fn($link) => [
                    'route' => $link->url,
                    'label' => $link->name,
                    'parameters' => [],
                    'icon' => $link->icon ?? $defaultIcon,
                    'directLink' => true
                ])
                ->toArray(),
        ];
    }




    public function render()
    {
        return view('livewire.core.nav.for-phone');
    }
}
