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

    // ✅ key changes every open => inner component remounts
    public int $modalInstance = 0;

    // ✅ init once per open (after DOM exists)
    private bool $initDispatchedForThisOpen = false;

    #[On('fill-modal')]
    public function openModal(array $data = []): void
    {
        $this->title           = (string) ($data['title'] ?? '');
        $this->titleOptions    = (array)  ($data['title_options'] ?? []);
        $this->transparent     = (bool)   ($data['transparent'] ?? false);
        $this->component       = (array)  ($data['component'] ?? []);
        $this->containsTinyMce = (bool)   ($data['containsTinyMce'] ?? false);

        $this->isOpen = true;

        // ✅ force fresh mount on every open
        $this->modalInstance++;

        // ✅ allow init again
        $this->initDispatchedForThisOpen = false;
    }

    public function rendered(): void
    {
        if (!$this->isOpen) return;
        if (!$this->containsTinyMce) return;
        if ($this->initDispatchedForThisOpen) return;

        $this->initDispatchedForThisOpen = true;

        // ✅ your existing listener ($wire.on('initialize-tiny-mce')) will catch this
        $this->dispatch('initialize-tiny-mce');
    }

    #[On('close-modal')]
    public function closeModal(): void
    {
        // ✅ IMPORTANT: destroy editors before hiding modal
        if ($this->containsTinyMce) {
            $this->dispatch('tinymce-destroy');
        }

        $this->isOpen = false;

        $this->title = '';
        $this->titleOptions = [];
        $this->transparent = false;
        $this->component = [];
        $this->containsTinyMce = false;

        $this->initDispatchedForThisOpen = false;
    }

    public function render()
    {
        return view('livewire.core.modal');
    }
}
