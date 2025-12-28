<?php

namespace App\Livewire\Core\Guest\ForgotPassword;

use App\Enum\Core\Web\RoutesNames;
use App\Livewire\Forms\Core\ForgotPassword\LastForm;
use Livewire\Attributes\On;
use Livewire\Component;

class LastStep extends Component
{


    public LastForm $form;


    #[On("set-forget-password-second-step-email")]
    public function setEmail()
    {
        $this->form->email = session('forget_password_email');
    }

    public function mount()
    {
        $this->form->email = session('forget_password_email');
    }
    public function handleSubmit()
    {

        $response =  $this->form->save();
        if ($response['status']) {
            $this->reset();
               session()->forget('forget_password_email');
        $this->dispatch('forget-password-multi-form-clear', 'forget-password-multi-form-step' );
             return  $this->redirectRoute($response['data']['route']);
            }else{
               $this->dispatch('open-errors', $response['errors']);
            }

    }
    public function render()
    {
        return view('livewire.core.guest.forgot-password.last-step');
    }
}
