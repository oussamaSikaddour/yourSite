<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Livewire\Forms\Core\Reply\SendForm;
use Livewire\Attributes\On;
use Livewire\Component;

class ReplyModal extends Component
{

    public  $message =[];
    public SendForm $form;
    public $messageContent="";





    #[On('set-message-content')]
    public function setMessage($content)
    {
        $this->form->fill([
            'message'=>$content
        ]);

    }


        public function handleSubmit()
        {
            $this->dispatch('form-submitted');
            $response = $this->form->save();
            $this->form->reset('message');
            if ($response['status']) {
                $this->dispatch('open-toast', $response['message']);
            } else {
                $this->dispatch('open-errors', $response['errors']);
            }
        }

    public function mount(){

        $this->dispatch('initialize-tiny-mce');
        $this->form->fill([
            'name'=>$this->message['name'],
            'email'=>$this->message['email'],
        ]);
    }
    public function render()
    {
        return view('livewire.core.super-admin.reply-modal');
    }
}
