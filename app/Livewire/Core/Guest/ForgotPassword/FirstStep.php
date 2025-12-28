<?php

namespace App\Livewire\Core\Guest\ForgotPassword;

use App\Livewire\Forms\Core\ForgotPassword\FirstForm;
use Livewire\Component;

class FirstStep extends Component
{

    public FirstForm $form;



 public function mount() {
       $this->dispatch('forget-password-multi-form-init', 'forget-password-multi-form-step' );
 }
 public function handleSubmit()
 {
     $this->dispatch('form-submitted');

     $response =  $this->form->save();
    if ($response['status']) {
        $this->dispatch('open-toast', $response['message']); // Corrected the variable name
        $this->dispatch('forget-password-first-step-succeeded', ['forget-password-multi-form-step' ,2]);
        session()->put('forget_password_email',$this->form->email);
        $this->dispatch('set-forget-password-second-step-email');
         $this->form->reset();
      }else{
         $this->dispatch('open-errors', $response['errors']);
      }

 }

    public function render()
    {
        return view('livewire.core.guest.forgot-password.first-step');
    }
}
