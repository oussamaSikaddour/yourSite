<?php

namespace App\Livewire\Core\Nav;

use App\Enum\Core\Web\RoutesNames;
use App\Models\Menu;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ForDesktop extends Component
{

    public bool $forLandingPage = false;


    // fired from toast success after admin changed their own roles
    #[On('logout-yourself')]
    public function logout()
    {
     return redirect()->route(RoutesNames::LOG_OUT->value);
    }

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
        return view('livewire.core.nav.for-desktop');
    }
}
