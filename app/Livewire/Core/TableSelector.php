<?php

namespace App\Livewire\Core;

use Livewire\Attributes\On;
use Livewire\Component;

class TableSelector extends Component
{
    public array $data = [];
    public string $htmlId = "";

    // Instead of $entity (model), we store only the id
    public int|string|null $entityId = null;

    public string $selectedValue = "";

    public function selectedValueChanged(): void
    {
        // Dispatch only the id + value
        $this->dispatch('selected-value-updated', $this->entityId, $this->selectedValue);
    }

    #[On("selected-value-reset")]
    public function selectedValueRest(int|string $entityId, string $value): void
    {
        if ((string) $this->entityId === (string) $entityId) {
            $this->selectedValue = $value;
        }
    }

    public function render()
    {
        return view('livewire.core.table-selector');
    }
}
