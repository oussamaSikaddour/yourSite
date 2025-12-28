<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Livewire\Forms\Core\Daira\AddForm;
use App\Livewire\Forms\Core\Daira\UpdateForm;
use App\Models\Daira;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DairaModal extends Component
{
    use GeneralTrait;

    /* -------------------- Forms -------------------- */
    public AddForm $addForm;
    public UpdateForm $updateForm;

    /* -------------------- Models & State -------------------- */
    public $wilayaId;
    public Daira $daira;
    public $id;
    public $form = 'addForm';
    public $local = 'fr';


    /* -------------------- Computed -------------------- */

    #[Computed]
    public function formEntity()
    {
        return $this->id ? $this->updateForm : $this->addForm;
    }

    /* -------------------- Lifecycle -------------------- */

    public function mount(): void
    {
        $this->local = app()->getLocale();

        if ($this->id) {
            $this->form = 'updateForm';
            $this->loadDairaDataSafe();
        }else{
                   $this->addForm->fill([
            'wilaya_id' => $this->wilayaId
        ]);
        }
    }

    /* -------------------- Data Loading -------------------- */

    /**
     * Load Daira data safely, handle ModelNotFoundException.
     */
    protected function loadDairaDataSafe(): void
    {
        try {
            $this->loadDairaData();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->logModelError($e);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Load Daira data and fill update form.
     */
    protected function loadDairaData(): void
    {
        $this->daira = Daira::findOrFail($this->id);
        $this->fillUpdateForm();
    }

    /**
     * Fill the update form with the daira data.
     */
    protected function fillUpdateForm(): void
    {
        $this->updateForm->fill([
            'id'              => $this->id,
            'code'            => $this->daira->code,
            'designation_ar'  => $this->daira->designation_ar,
            'designation_fr'  => $this->daira->designation_fr,
            'designation_en'  => $this->daira->designation_en,
            'wilaya_id'       => $this->daira->wilaya_id,
        ]);
    }

    /* -------------------- Form Handling -------------------- */

    /**
     * Handle form submission for add or update.
     */
    public function handleSubmit(): void
    {
        $response = $this->id
            ? $this->updateForm->save($this->daira)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-dairates-table');
            $this->dispatch('open-toast', $response['message']);

            if (!$this->id) {
                $this->addForm->reset();
             $this->addForm->fill([
            'wilaya_id' => $this->wilayaId,
        ]);
            }
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    /* -------------------- Helpers -------------------- */

    /**
     * Log ModelNotFoundException for debugging.
     */
    protected function logModelError(\Throwable $exception): void
    {
        Log::error("DairaModal: Daira not found.", [
            'message'   => $exception->getMessage(),
            'daira_id'  => $this->id,
        ]);
    }

    /* -------------------- Render -------------------- */

    public function render()
    {
        return view('livewire.core.super-admin.daira-modal');
    }
}
