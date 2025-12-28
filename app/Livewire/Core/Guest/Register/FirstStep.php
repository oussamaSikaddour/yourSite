<?php

namespace App\Livewire\Core\Guest\Register;

use App\Enum\Core\Web\RoutesNames;
use App\Livewire\Forms\Core\Register\FirstForm;
use Livewire\Attributes\Computed;
use Livewire\Component;

class FirstStep extends Component
{


    public $registrationEmail;
    public FirstForm $form;

     #[Computed()]
    public function loginRoute(){
        return RoutesNames::LOGIN->value;
    }


    public function mount() {

       $this->dispatch('register-multi-form-init', 'register-multi-form-step' );
    }
    public function handleSubmit()
    {
        $this->dispatch('form-submitted');

        $response =  $this->form->save();
       if ($response['status']) {
        $this->dispatch('open-toast', $response['message']); // Corrected the variable name
        $this->dispatch('register-first-step-succeeded', ['register-multi-form-step' ,2]);
        session()->put('register_email',$this->form->email);
        $this->dispatch('set-second-step-email');
        $this->form->reset();
         }else{
            $this->dispatch('open-errors', $response['errors']);
         }

    }
    public function render()
    {
        return view('livewire.core.guest.register.first-step');
    }
}
