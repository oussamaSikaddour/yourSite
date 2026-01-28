<?php

namespace App\Livewire\Core;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ColorPicker extends Component
{

    public string $uid;
    public GeneralSetting $gSetting;

    public function mount(): void
    {
        $this->uid = $this->uid ?? uniqid('cp_', true);
         try {
            $this->gSetting = GeneralSetting::first();

            $this->dispatch('init_theme_color',$this->gSetting->theme_color);

        } catch (\Exception $e) {
            Log::error('Error in GeneralInfos mount method: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.core.color-picker');
    }
}
