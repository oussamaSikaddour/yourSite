<?php

namespace App\Livewire\Core;

use Livewire\Attributes\On;
use Livewire\Component;

final class ErrorsHandler extends Component
{
    public bool $isOpen = false;
    public array $errors = [];

    // internal: sync inert after DOM update
    public bool $needsInertSync = false;

    #[On('open-errors')]
    public function openErrors($errors): void
    {
        $this->errors = $this->processErrors($errors);
        $this->isOpen = true;

        // apply inert AFTER DOM updates (when .open exists)
        $this->needsInertSync = true;
    }

    public function closeErrors(): void
    {
        $this->isOpen = false;
        $this->errors = [];

        // remove inert AFTER DOM updates (when .open removed)
        $this->needsInertSync = true;
    }

    public function rendered(): void
    {
        if (!$this->needsInertSync) {
            return;
        }

        $this->needsInertSync = false;

        // Browser event (ErrorsNotifications.js listens to it)
        $this->dispatch('errors-sync-inert');
    }

    private function processErrors($errors): array
    {
        if (is_string($errors)) {
            $errors = explode("\n", $errors);
        }

        $processed = [];

        foreach ((array) $errors as $error) {
            foreach (explode("\n", trim((string) $error)) as $line) {
                $line = trim($line);
                if ($line !== '') {
                    $processed[] = $line;
                }
            }
        }

        return $processed;
    }

    public function render()
    {
        return view('livewire.core.errors-handler');
    }
}
