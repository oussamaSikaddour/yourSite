<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Livewire\Forms\Core\Reply\SendForm;
use Livewire\Attributes\On;
use Livewire\Component;

class ReplyModal extends Component
{
    public array $message = [];
    public SendForm $form;
    public string $messageContent="";

    #[On('set-message-content')]
    public function setMessage(string $content): void
    {
        $this->form->message = $content;
    }

    public function handleSubmit(): void
    {
        $this->dispatch('form-submitted');

        $response = $this->form->save();

        // Only reset what you changed
        $this->form->reset('message');

        if ($response['status']) {
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    public function mount(): void
    {
        // Init editor once
        $this->dispatch('initialize-tiny-mce');

        // Defensive fill (avoid undefined index errors)
        $this->form->fill([
            'name'  => $this->message['name']  ?? null,
            'email' => $this->message['email'] ?? null,
        ]);
    }

    public function render()
    {
        return view('livewire.core.super-admin.reply-modal');
    }
}
