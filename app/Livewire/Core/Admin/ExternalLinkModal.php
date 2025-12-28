<?php

namespace App\Livewire\Core\Admin;

use App\Livewire\Forms\Core\ExternalLink\AddForm;
use App\Livewire\Forms\Core\ExternalLink\UpdateForm;
use App\Models\ExternalLink;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ExternalLinkModal extends Component
{


    public AddForm $addForm;
    public UpdateForm $updateForm;
    public ExternalLink $externalLink;
    public $id;
    public $form = "addForm";
    public $menuId;



    /**
     * Mount the component.
     */
    public function mount(): void
    {
        if ($this->id) {
            $this->form = "updateForm";
        }
        $this->loadExternalLinkData();
    }

    /**
     * Load user data for update form.
     */
    private function loadExternalLinkData(): void
    {
        if ($this->id){
        try {
            $this->externalLink = ExternalLink::findOrFail($this->id);
            $this->updateForm->fill([
                'id' => $this->id,
                'name_fr' => $this->externalLink->name_fr,
                'name_ar' => $this->externalLink->name_ar,
                'name_en' => $this->externalLink->name_en,
                'url' => $this->externalLink->url,
                'menu_id' =>  $this->externalLink->menu_id,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Error in externalLinkModal mount method: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }else{
            $this->addForm->fill([
                'menu_id' => $this->menuId,
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
            ? $this->updateForm->save($this->externalLink)
            : $this->addForm->save();
        if ($response['status']) {
            $this->dispatch('update-external-links-table');
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }
    public function render()
    {
        return view('livewire.core.admin.external-link-modal');
    }
}
