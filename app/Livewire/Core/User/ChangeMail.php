<?php

namespace App\Livewire\Core\User;

use App\Enum\Core\Web\RoutesNames;
use App\Livewire\Forms\Core\ChangeMailForm;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ChangeMail extends Component
{

    public ChangeMailForm $form;

    public function handelSubmit()
    {
        $this->dispatch('form-submitted');
        $response = $this->form->save();

        if ($response['status']) {
            $this->dispatch('redirect-page');
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }


    #[Computed()]
    public function logoutRoute(){
        return RoutesNames::LOG_OUT->value;
    }

    public function render()
    {
        return view('livewire.core.user.change-mail');
    }
}
