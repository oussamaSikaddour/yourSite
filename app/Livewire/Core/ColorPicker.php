<?php

namespace App\Livewire\Core;

use Livewire\Component;

class ColorPicker extends Component
{

    public string $uid;

    public function mount(): void
    {
        $this->uid = $this->uid ?? uniqid('cp_', true);
    }

    public function render()
    {
        return view('livewire.core.color-picker');
    }
}
