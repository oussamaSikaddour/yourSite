<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Livewire\Forms\Core\Field\AddForm;
use App\Livewire\Forms\Core\Field\UpdateForm;
use App\Models\Field;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class FieldModal extends Component
{

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public Field $field;
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
        $this->loadfieldData();
    }

    /**
     * Load user data for update form.
     */
    private function loadfieldData(): void
    {
        try {
            $this->field = field::findOrFail($this->id);


            $this->updateForm->fill([

                'id' => $this->id,
                "acronym"=>$this->field->acronym,
                "designation_ar"=>$this->field->designation_ar,
                "designation_fr"=>$this->field->designation_fr,
                "designation_en"=>$this->field->designation_en,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Error in fieldModal mount method: ' . $e->getMessage(), [
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
            ? $this->updateForm->save($this->field)
            : $this->addForm->save();
        if ($response['status']) {
            $this->dispatch('update-fields-table');
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }
    public function render()
    {
        return view('livewire.core.super-admin.field-modal');
    }
}
