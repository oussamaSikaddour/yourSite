<?php

namespace App\Livewire\App\LandingPage\Sections;

use Livewire\Component;

class Services extends Component
{
    public $services;
    public function render()
    {
        return view('livewire.app.landing-page.sections.services');
    }
}
