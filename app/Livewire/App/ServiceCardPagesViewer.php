<?php

namespace App\Livewire\App;

use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceCardPagesViewer extends Component
{
    use WithPagination;

    public  bool  $formServicesPublic = true;
    #[Computed()]
    public function services()
    {
        return Service::with('icon')->paginate(5);
    }

    public function render()
    {
        return view('livewire.app.service-card-pages-viewer');
    }
}
