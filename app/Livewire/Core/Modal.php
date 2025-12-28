<?php

namespace App\Livewire\Core;

use Livewire\Attributes\On;
use Livewire\Component;

class Modal extends Component
{


    public $isOpen = false;
    public $title = "";
    public $titleOptions = [];
    public $transparent = false;
    public $component = [];
    public $containsTinyMce = false;

    #[On("fill-modal")]
    public function openModal($data)
    {
        $this->isOpen = true;
        $this->title = $data['title'] ?? '';
        $this->titleOptions = $data['title_options'] ?? [];
        $this->transparent = $data['transparent'] ?? '';
        $this->component = $data['component'] ?? [];
        $this->containsTinyMce = $data['containsTinyMce'] ?? false;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset();

    }


    public function render()
    {
        return view('livewire.core.modal');
    }
}
