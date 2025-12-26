<?php

namespace App\Livewire\Core;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class OpenModalButton extends Component
{
    // Button props
    public string $htmlId = "";
    public string $iconHtml = "";
    public ?string $text = null;
    public string $tooltip = '';
    public bool $hasTooltip = false;
    public string $icon = '';
    public bool $rounded = false;
    public bool $fullWidth = false;
    public ?string $variant = null;
    public array $extraClasses = [];

    // Modal configuration props
    public string $modalTitle = '';
    public bool $transparent = false;
    public array $modalTitleOptions = [];
    public array $modalContent = [];
    public bool $containsTinyMce = false;


    public function mount()
    {
        $this->htmlId = 'btn-' . Str::random(8);
        $this->iconHtml = $this->resolveIconHtml($this->icon);
    }

    private function resolveIconHtml(string $icon): string
    {
        if (!$icon) {
            return '';
        }

        $configIcon = config("core.icons.FONTAWESOME.$icon");

        if (!$configIcon) {
            Log::warning("Icon '$icon' not found in config(core.icons.FONTAWESOME.*)");
            return '<i class="fa-solid fa-question"></i>';
        }

        return $configIcon;
    }


    /**
     * Called when any component dispatches: $dispatch('open-modal')
     */

    public function fillModal()
    {

        $this->dispatch('fill-modal', [
            'transparent'       => $this->transparent,
            'title'             => $this->modalTitle,
            'title_options'     => $this->modalTitleOptions,
            'component'         => $this->modalContent,
            'containsTinyMce'   => $this->containsTinyMce,
        ]);
    }


    public function render()
    {
        return view('livewire.core.open-modal-button');
    }
}
