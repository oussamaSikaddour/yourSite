<?php

namespace App\Livewire\Core;

use Livewire\Attributes\On;
use Livewire\Component;

final class Toast extends Component
{
    public bool $isOpen = false;
    public string $message = '';
    public string $variant = '';
    public string $afterClosingEvent = '';

    #[On('open-toast')]
    public function openToast(string|array $data): void
    {
        if (is_string($data)) {
            $this->message = $data;
            $this->variant = '';
            $this->afterClosingEvent = '';
        } else {
            $this->message = (string) ($data['message'] ?? '');
            $this->variant = (string) ($data['variant'] ?? '');
            $this->afterClosingEvent = (string) ($data['closing-event'] ?? '');
        }

        $this->isOpen = true;
    }

    #[On('close-toast')]
    public function closeToast(): void
    {
        $this->isOpen = false;

        if ($this->afterClosingEvent !== '') {
            $this->dispatch($this->afterClosingEvent);
        }

        // reset payload (optional but cleaner)
        $this->message = '';
        $this->variant = '';
        $this->afterClosingEvent = '';
    }

    public function render()
    {
        return view('livewire.core.toast');
    }
}
