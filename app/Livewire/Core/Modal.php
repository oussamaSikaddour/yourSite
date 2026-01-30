<?php

namespace App\Livewire\Core;

use Livewire\Attributes\On;
use Livewire\Component;

final class Modal extends Component
{
    public bool $isOpen = false;

    public string $title = '';
    public array $titleOptions = [];
    public bool $transparent = false;

    /** @var array{name?:string,parameters?:array} */
    public array $component = [];

    public bool $containsTinyMce = false;

    public int $modalInstance = 0;

    public bool $needsInertSync = false;

    #[On('fill-modal')]
    public function openModal(array $data = []): void
    {
        $this->title           = (string) ($data['title'] ?? '');
        $this->titleOptions    = (array)  ($data['title_options'] ?? []);
        $this->transparent     = (bool)   ($data['transparent'] ?? false);
        $this->component       = (array)  ($data['component'] ?? []);
        $this->containsTinyMce = (bool)   ($data['containsTinyMce'] ?? false);

        $this->isOpen = true;
        $this->modalInstance++;

        $this->needsInertSync = true;
    }

    public function closeModal(): void
    {
        if ($this->containsTinyMce) {
            $this->dispatch('tinymce-destroy-all');
        }

        $this->isOpen = false;

        $this->title = '';
        $this->titleOptions = [];
        $this->transparent = false;
        $this->component = [];
        $this->containsTinyMce = false;

        $this->needsInertSync = true;
    }

    public function rendered(): void
    {
        if (!$this->needsInertSync) return;

        $this->needsInertSync = false;
        $this->dispatch('modal-sync-inert');
    }

    public function render()
    {
        return view('livewire.core.modal');
    }
}
