<?php

namespace App\Livewire\Core\Guest\Register;

use App\Events\Core\Auth\VerificationEmailEvent;
use App\Livewire\Forms\Core\Register\LastForm;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class LastStep extends Component
{

    public LastForm $form;


    #[On("set-second-step-email")]
    public function setEmail()
    {
        $this->form->email = session('register_email');
    }

    public function mount()
    {
        $this->form->email = session('register_email');
    }
    public function setNewValidationCode(){
        try {
            $user = User::where('email', $this->form->email)->first();
            event(new VerificationEmailEvent($user));
            $this->dispatch('open-toast',__('forms.register.responses.new_code'));
            } catch (\Exception $e) {
                Log::error('Register setNewValidationCode error: ' . $e->getMessage());
                $this->dispatch('open-errors', __('forms.common.errors.default'));
            }
    }
    public function handleSubmit()
    {

        $response =  $this->form->save();
       if ($response['status']) {
        $this->reset();
        session()->forget('register_email');
        $this->dispatch('register-multi-form-clear', 'register-multi-form-step' );
         return  $this->redirectRoute($response['data']['route']);
         }else{
            $this->dispatch('open-errors', $response['errors']);
         }

    }

    public function render()
    {
        return view('livewire.core.guest.register.last-step');
    }
}
