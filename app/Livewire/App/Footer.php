<?php

namespace App\Livewire\App;

use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Footer extends Component
{
    #[Computed]
    public function services()
    {
        return Service::with('icon')
            ->inRandomOrder()
            ->limit(15)
            ->get()
            ->chunk(5)
            ->values(); // optional, for clean indexes
    }

    public function render()
    {
        return view('livewire.app.footer');
    }
}
