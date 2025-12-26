<?php

namespace App\Livewire\Core;

use App\Models\GeneralSetting;
use Livewire\Attributes\Computed;
use Livewire\Component;


class Footer extends Component
{
    public $gSettings;
    public $currentYear;

    public function mount()
    {
        // Fetch the current year
        $this->currentYear = now()->year;

        // Retrieve general settings with caching
        $this->gSettings = cache()->remember('general_settings', 3600, function () {
            return GeneralSetting::with('logo')->first();
        });
    }

    // Computed property for logo URL
    #[Computed]
    public function logoUrl()
    {
        return $this->gSettings?->logo?->url ?? asset('img/logo.png');
    }

    public function render()
    {
        return view('livewire.core.footer');
    }
}



