<?php

namespace App\Livewire\Core\Guest;

use App\Enum\Core\Web\RoutesNames;
use App\Livewire\Forms\Core\LoginForm;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Login extends Component
{

    public LoginForm $form;


    #[Computed()]
    public function forgetPasswordRoute(){
        return RoutesNames::FORGET_PASSWORD->value;
    }
    #[Computed()]
    public function registerPageRoute(){
        return RoutesNames::REGISTER->value;
    }





    public function handelSubmit()
    {

        $this->dispatch('form-submitted');
        $response =  $this->form->save();
        $this->form->reset();
       if ($response['status']) {
        return  $this->redirectRoute($response['data']['route']);
       }else{
        $this->dispatch('open-errors', $response['errors']);
         }
    }
    public function render()
    {
        return view('livewire.core.guest.login');
    }
}
