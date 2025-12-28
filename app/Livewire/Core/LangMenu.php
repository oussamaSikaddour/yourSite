<?php

namespace App\Livewire\Core;

use App\Enum\Core\Web\RoutesNames;
use Livewire\Component;

class LangMenu extends Component
{

    public $forPhone = false;
    public $locale = 'fr';

    public function setLocale($locale)
    {
        $this->locale = strtolower($locale);
        redirect()->route(RoutesNames::SET_LANG, $this->locale);
    }


    public function render()
    {
        return view('livewire.core.lang-menu');
    }
}
