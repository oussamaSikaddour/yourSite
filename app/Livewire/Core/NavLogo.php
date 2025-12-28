<?php

namespace App\Livewire\Core;

use App\Enum\Core\Web\RoutesNames;
use Livewire\Component;

class NavLogo extends Component
{

    public $route;

    public function mount()
    {
        // Retrieve general settings with caching

        $this->route= RoutesNames::INDEX;

    }

    public function render()
    {
        return view('livewire.core.nav-logo');
    }
}
