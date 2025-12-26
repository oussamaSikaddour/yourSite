<?php

namespace App\Livewire\App\SocialAdmin;

use App\Livewire\Forms\App\Transfer\AddForm;
use App\Livewire\Forms\App\Transfer\UpdateForm;
use App\Models\BankTransfer;
use App\Models\User;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TransferModal extends Component
{

    use GeneralTrait;
    public AddForm $addForm;
    public UpdateForm $updateForm;
    public BankTransfer $transfer;
    public $id;
    public $form = "addForm";
    public $userId;
    public $globalTransferId;
    public $employeeOptions =[];
    public $local ="fr";




 #[Computed()]
 public function employees()
 {


    $this->local =app()->getLocale()?? 'fr';
    return User::where('is_paid', true)
    ->where('establishment_id', auth()->user()->establishment_id)
    ->get(['id', 'name_'.$this->local]);
 }



    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->employeeOptions = $this->populateSelectorOption($this->employees(),  'id','name_'.$this->local, __('selectors.default.employees'));
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
            $this->transfer = BankTransfer::findOrFail($this->id);


            $this->updateForm->fill([
                "global_bank_transfer_id"=>$this->globalTransferId,
                'id' => $this->id,
                'amount' => $this->transfer->amount,
                'user_id' => $this->transfer->user_id,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Error in transferModal mount method: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $this->userId,
            ]);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }else{
        $this->addForm->fill([
            "global_bank_transfer_id"=>$this->globalTransferId,
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
            ? $this->updateForm->save($this->transfer)
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
        return view('livewire.app.social-admin.transfer-modal');
    }
}
