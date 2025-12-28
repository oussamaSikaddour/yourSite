<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Livewire\Forms\Core\Bank\AddForm;
use App\Livewire\Forms\Core\Bank\UpdateForm;
use App\Models\Bank;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BankModal extends Component
{

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public Bank $bank;
    public $id;
    public $form = "addForm";


    /**
     * Mount the component.
     */
    public function mount(): void
    {
        if (!$this->id) {
            return;
        }
        $this->form = "updateForm";
        $this->loadBankData();
    }

    /**
     * Load user data for update form.
     */
    private function loadBankData(): void
    {
        try {
            $this->bank = bank::findOrFail($this->id);


            $this->updateForm->fill([

                'id' => $this->id,
                "acronym"=>$this->bank->acronym,
                "code"=>$this->bank->code,
                "designation_ar"=>$this->bank->designation_ar,
                "designation_fr"=>$this->bank->designation_fr,
                "designation_en"=>$this->bank->designation_en,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Error in bankModal mount method: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $this->id,
            ]);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }



    /**
     * Handle form submission.
     */
    public function handleSubmit(): void
    {
        $this->dispatch('form-submitted');
        $response = isset($this->id)
            ? $this->updateForm->save($this->bank)
            : $this->addForm->save();
        if ($response['status']) {
            $this->dispatch('update-banks-table');
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }
    public function render()
    {
        return view('livewire.core.super-admin.bank-modal');
    }
}
