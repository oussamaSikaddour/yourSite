<?php

namespace App\Livewire\Core;

use Livewire\Component;

class TinyMceTextArea extends Component
{
    public string $content = '';
    public string $htmlId = '';
    public string $contentUpdatedEvent = 'content-updated';
    public bool $viewOnly = false;

    public function mount(): void
    {
        $this->dispatch('initialize-tiny-mce');
    }

    public function setContent(string $value): void
    {
        // Prevent updates in view-only mode
        if ($this->viewOnly) {
            return;
        }

        $this->content = $value;

        // Notify parent / listeners
        $this->dispatch($this->contentUpdatedEvent, $value);
    }

    public function render()
    {
        return view('livewire.core.tiny-mce-text-area');
    }
}
