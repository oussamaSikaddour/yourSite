<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Livewire\Forms\Core\Wilaya\AddForm;
use App\Livewire\Forms\Core\Wilaya\UpdateForm;
use App\Models\Wilaya;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WilayaModal extends Component
{
    use GeneralTrait;

    /* -------------------- Forms -------------------- */
    public AddForm $addForm;
    public UpdateForm $updateForm;

    /* -------------------- Models & State -------------------- */
    public Wilaya $wilaya;
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
            $this->loadWilayaDataSafe();
        }
    }

    /* -------------------- Data Loading -------------------- */

    /**
     * Load Wilaya data safely, handle ModelNotFoundException.
     */
    protected function loadWilayaDataSafe(): void
    {
        try {
            $this->loadWilayaData();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->logModelError($e);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Load Wilaya data and fill update form.
     */
    protected function loadWilayaData(): void
    {
        $this->wilaya = Wilaya::findOrFail($this->id);
        $this->fillUpdateForm();
    }

    /**
     * Fill the update form with the wilaya data.
     */
    protected function fillUpdateForm(): void
    {
        $this->updateForm->fill([
            'id'              => $this->id,
            'code'            => $this->wilaya->code,
            'designation_ar'  => $this->wilaya->designation_ar,
            'designation_fr'  => $this->wilaya->designation_fr,
            'designation_en'  => $this->wilaya->designation_en,
        ]);
    }

    /* -------------------- Form Handling -------------------- */

    /**
     * Handle form submission for add or update.
     */
    public function handleSubmit(): void
    {
        $response = $this->id
            ? $this->updateForm->save($this->wilaya)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-wilayates-table');
            $this->dispatch('open-toast', $response['message']);

            if (!$this->id) {
                $this->addForm->reset();
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
        Log::error("WilayaModal: Wilaya not found.", [
            'message'   => $exception->getMessage(),
            'wilaya_id' => $this->id,
        ]);
    }

    /* -------------------- Render -------------------- */

    public function render()
    {
        return view('livewire.core.super-admin.wilaya-modal');
    }
}
