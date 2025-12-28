<?php

namespace App\Livewire\App\SocialAdmin;

use App\Livewire\Forms\App\Bonus\AddForm;
use App\Livewire\Forms\App\Bonus\UpdateForm;
use App\Models\Bonus;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BonusModal extends Component
{


    public AddForm $addForm;
    public UpdateForm $updateForm;
    public Bonus $bonus;
    public $id;
    public $form = "addForm";



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
            $this->bonus = Bonus::findOrFail($this->id);


            $this->updateForm->fill([
                'id' => $this->id,
                "amount"=>$this->bonus->amount,
                "titled_ar"=>$this->bonus->titled_ar,
                "titled_fr"=>$this->bonus->titled_fr,
                "titled_en"=>$this->bonus->titled_en,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Error in BonusModal mount method: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $this->id,
            ]);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }
    }



    /**
     * Handle form submission.
     */
    public function handleSubmit(): void
    {
        $this->dispatch('form-submitted');
        $response = isset($this->id)
            ? $this->updateForm->save($this->bonus)
            : $this->addForm->save();
        if ($response['status']) {
            $this->dispatch('update-bonuses-table');
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }
    public function render()
    {
        return view('livewire.app.social-admin.bonus-modal');
    }
}
