<?php

namespace App\Livewire\App\SocialAdmin;

use App\Livewire\Forms\App\Transfer\AddForm;
use App\Livewire\Forms\App\Transfer\UpdateForm;
use App\Models\BankTransfer;
use App\Models\Person;
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





    #[Computed]
    public function persons()
    {
        $nameLocale = $this->local === 'ar' ? 'ar' : 'fr';
        $lastNameField = 'last_name_' . $nameLocale;
        $firstNameField = 'first_name_' . $nameLocale;

        return Person::query()
            ->select(['id', $lastNameField, $firstNameField])
            ->get()
            ->map(fn($person) => [
                'id'        => $person->id,
                'full_name' => trim($person->{$lastNameField} . ' ' . $person->{$firstNameField}),
            ]);
    }


    /**
     * Mount the component.
     */
    public function mount(): void
    {

        $this->local= app()->getLocale();
        $this->employeeOptions = $this->populateSelectorOption($this->persons(),  'id','full_name', __('selectors.default.employees'));
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
                'person_id' => $this->transfer->person_id,
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
