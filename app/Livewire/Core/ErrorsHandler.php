<?php

namespace App\Livewire\Core;

use Livewire\Attributes\On;
use Livewire\Component;

class ErrorsHandler extends Component
{
    public bool $isOpen = false;
    public array $errors = [];

    // -------------------------
    // EVENT : OPEN ERRORS PANEL
    // -------------------------
    #[On('open-errors')]
    public function openErrors($errors)
    {

        $this->errors = $this->processErrors($errors);
        $this->isOpen = true;

        $this->dispatch("errors-notifications", isOpen: true);
    }

    // -------------------------
    // CLOSE ERRORS PANEL
    // -------------------------
    public function closeErrors()
    {
        $this->isOpen = false;
        $this->errors = [];

        $this->dispatch("handle-errors-state", isOpen: false);
    }


    // -------------------------
    // CLEAN & NORMALIZE ERRORS
    // -------------------------
    private function processErrors($errors): array
    {
        // Normalize string → array
        if (is_string($errors)) {
            $errors = explode("\n", $errors);
        }

        $processed = [];

        foreach ($errors as $error) {
            foreach (explode("\n", trim($error)) as $line) {
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
