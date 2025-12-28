<?php

namespace App\Livewire\Core;

use Livewire\Attributes\On;
use Livewire\Component;

class TableSelector extends Component
{

    public $data=[];
    public $htmlId="";
    public $entity;
    public $selectedValue="";

    public function selectedValueChanged(){
        $this->dispatch('selected-value-updated', $this->entity, $this->selectedValue);
    }

    #[On("selected-value-reset")]
    public function selectedValueRest($entityId,$value) {
    if($this->entity->id === $entityId){
     $this->selectedValue =$value;
    }
    }
    public function render()
    {
        return view('livewire.core.table-selector');
    }
}
