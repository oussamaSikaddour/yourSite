<?php

namespace App\Livewire\Core\Admin;

use App\Livewire\Forms\Core\Menu\AddForm;
use App\Livewire\Forms\Core\Menu\UpdateForm;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MenuModal extends Component
{


    public AddForm $addForm;
    public UpdateForm $updateForm;
    public Menu $menu;
    public $id;
    public $form = "addForm";
    public $local="fr";

    public $stateOptions=[];




    /**
     * Mount the component.
     */
    public function mount(): void
    {

       $this->local = app()->getLocale();
         $this->stateOptions = config('core.options.PUBLISHING_STATE')[$this->local];
        if ($this->id) {
            $this->form = "updateForm";
        }
        $this->loadMenuData();
    }

    /**
     * Load user data for update form.
     */
    private function loadMenuData(): void
    {
        if (!$this->id){
            return;
        }
        try {
            $this->menu = menu::findOrFail($this->id);


            $this->updateForm->fill([
                'id' => $this->id,
                'title_fr' => $this->menu->title_fr,
                'title_ar' => $this->menu->title_ar,
                'title_en' => $this->menu->title_en,
                    'state' => $this->menu->state,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Error in menuModal mount method: ' . $e->getMessage());
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
            ? $this->updateForm->save($this->menu)
            : $this->addForm->save();
        if ($response['status']) {
            $this->dispatch('update-menus-table');
            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }
    public function render()
    {
        return view('livewire.core.admin.menu-modal');
    }
}
