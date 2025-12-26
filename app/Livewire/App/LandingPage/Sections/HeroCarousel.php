<?php

namespace App\Livewire\App\LandingPage\Sections;

use App\Models\Hero;
use App\Models\Slider;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class HeroCarousel extends Component
{
    public Hero $hero;


    public function render()
    {
        return view('livewire.app.landing-page.sections.hero-carousel');
    }
}
