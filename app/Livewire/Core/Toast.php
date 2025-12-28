<?php

namespace App\Livewire\Core;

use Livewire\Attributes\On;
use Livewire\Component;

class Toast extends Component
{
    public bool $isOpen = false;
    public string $message = '';
    public string $variant = '';
    public string $afterClosingEvent = '';

    /**
     * Handle opening the toast.
     * Accepts:
     * - string (message only)
     * - array ['message' => '', 'variant' => '', 'function' => '', 'params' => []]
     */
    #[On('open-toast')]
    public function openToast(string|array $data): void
    {
        if (is_string($data)) {
            // Simple message
            $this->message = $data;
            $this->variant = '';
            $this->afterClosingEvent = '';

        }


        if (is_array($data)) {
            $this->message = $data['message'] ?? '';
            $this->variant = $data['variant'] ?? '';
            $this->afterClosingEvent = $data['closing-event'] ?? '';
        }

        $this->isOpen = true;

        // Notify the frontend (JS will animate opening)
        $this->dispatch('set-toast-open');
    }

    #[On('close-toast')]
    public function closeToast(): void
    {

        $this->isOpen = false;

        // Execute callback if defined
        if ($this->afterClosingEvent ) {
        $this->dispatch($this->afterClosingEvent);
        }
    }

    public function render()
    {
        return view('livewire.core.toast');
    }
}
