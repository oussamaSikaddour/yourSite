<?php

namespace App\Livewire\Core\SuperAdmin\SiteParameters;

use App\Livewire\Forms\Core\SiteParameters\FirstStepForm;

use Livewire\Component;

class FirstStep extends Component
{


    public FirstStepForm $form;


 public function mount() {
       $this->dispatch('site-params-multi-form-init', 'site-params-multi-form-step' );
 }
    public function handleSubmit()
    {
        $this->dispatch('form-submitted');
        $response =  $this->form->save();
       if ($response['status']) {
           $this->dispatch('open-toast', $response['message']); // Corrected the variable name

           $this->dispatch('site-params-first-step-succeeded', ['site-params-multi-form-step' ,2]);

            $this->form->reset();
         }else{
            $this->dispatch('open-errors', $response['errors']);
         }

    }


    public function render()
    {
        return view('livewire.core.super-admin.site-parameters.first-step');
    }
}
