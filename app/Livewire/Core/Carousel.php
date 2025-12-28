<?php

namespace App\Livewire\Core;

use Livewire\Component;

class Carousel extends Component
{

    public $showControls = true;
    public $variant = "";
    public $slides = [];
    public function render()
    {
        return view('livewire.core.carousel');
    }
}
