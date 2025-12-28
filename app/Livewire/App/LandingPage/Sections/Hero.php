<?php

namespace App\Livewire\App\LandingPage\Sections;

use App\Enum\Core\Web\RoutesNames;
use App\Models\Hero as ModelsHero;
use App\Models\Slider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class Hero extends Component
{

    public ModelsHero $hero;


    public function render()
    {
        return view('livewire.app.landing-page.sections.hero');
    }
}
