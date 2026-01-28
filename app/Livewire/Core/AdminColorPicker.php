<?php
// app/Livewire/Core/AdminColorPicker.php

namespace App\Livewire\Core;

use App\Livewire\Forms\Core\GeneralInfos\ManageForm;
use App\Models\GeneralSetting;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminColorPicker extends Component
{

    public GeneralSetting $gSetting;

    public array $allowedColors = [
        'default','emerald','gold','lime','ocean','rose','sky','slate','sunset','violet'
    ];

    // UI active state
    public string $color = 'default';

    public function mount(): void
    {
        $this->gSetting = GeneralSetting::first() ?? new GeneralSetting();

        // UI shows DB if exists, otherwise default (JS may override UI on load)
        $db = strtolower($this->gSetting->theme_color ?? '');
        $initial = $db !== '' && in_array($db, $this->allowedColors, true) ? $db : 'default';
        $this->color = $initial;

    }


    public function setThemeColor(string $color): void
    {
        $color = strtolower($color);
        $color = in_array($color, $this->allowedColors, true) ? $color : 'default';

        $this->color = $color;

      $this->dispatch("set_manage_form_theme_color", $this->color);

    }

    public function render()
    {
        return view('livewire.core.admin-color-picker');
    }
}
