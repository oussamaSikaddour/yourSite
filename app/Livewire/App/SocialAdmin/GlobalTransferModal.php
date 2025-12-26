<?php

namespace App\Livewire\App\SocialAdmin;

use App\Livewire\Forms\App\GlobalTransfer\AddForm;
use App\Livewire\Forms\App\GlobalTransfer\UpdateForm;
use App\Models\GlobalBankTransfer;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class GlobalTransferModal extends Component
{

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public GlobalBankTransfer $GlobalTransfer;
    public $id;
    public $form = "addForm";
    public $userId;
    public $establishmentId;



    /**
     * Mount the component.
     */
    public function mount(): void
    {
        if ($this->id) {
            $this->form = "updateForm";
        }
        $this->loadBankData();
    }

    /**
     * Load user data for update form.
     */
    private function loadBankData(): void
    {
        if ($this->id){
        try {
            $this->GlobalTransfer = GlobalBankTransfer::findOrFail($this->id);


            $this->updateForm->fill([
                'id' => $this->id,
                "number"=>$this->globalTransfer->number,
                "date"=>$this->globalTransfer->date,
                 "motive_fr"=>$this->globalTransfer->motive_fr,
                 "motive_ar"=>$this->globalTransfer->motive_ar,
                 "motive_fr"=>$this->globalTransfer->motive_fr,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Error in GlobalTransferModal mount method: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $this->userId,
            ]);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }else{

        $this->addForm->fill([
            'establishment_id' => $this->establishmentId,
            'user_id'=>$this->userId
        ]);
    }
    }



    /**
     * Handle form submission.
     */
    public function handleSubmit(): void
    {
        $this->dispatch('form-submitted');
        $response = isset($this->id)
            ? $this->updateForm->save($this->GlobalTransfer)
            : $this->addForm->save();
        if ($response['status']) {
            $this->dispatch('update-global-transfers-table');
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }
    public function render()
    {
        return view('livewire.app.social-admin.global-transfer-modal');
    }
}
