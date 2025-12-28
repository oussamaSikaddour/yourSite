<?php

namespace App\Livewire\App\Cards;

use App\Models\Service as ModelsService;
use Livewire\Component;

class Service extends Component
{
    public ModelsService $service;
    public bool $formServicesPublic =false;
    public int $serviceId;

    public function mount()
    {
        if (isset($this->serviceId)) {
            $this->service = ModelsService::with('icon')->find($this->serviceId);
        }
    }

    public function render()
    {
        return view('livewire.app.cards.service');
    }
}
