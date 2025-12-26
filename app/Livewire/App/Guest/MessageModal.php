<?php

namespace App\Livewire\App\Guest;

use App\Livewire\Forms\Core\Message\SendForm;
use Livewire\Component;

class MessageModal extends Component
{


    public SendForm $form;
    public bool $showToast = false;   // controls toast visibility
    public string $toastMessage = ''; // text inside the toast


    /* ------------------------------------------------------------------
     |  Submit
     | ------------------------------------------------------------------ */

    public function handleSubmit(): void
    {

        $response = $this->form->save();
        $this->form->reset();
        if ($response['status']) {
            $this->toastMessage = $response['message'];
            $this->showToast = true;
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }
    public function render()
    {
        return view('livewire.custom.app.message-modal');
    }
}
