<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Livewire\Forms\Core\OurQuality\AddForm;
use App\Livewire\Forms\Core\OurQuality\UpdateForm;
use App\Models\Image;
use App\Models\OurQuality;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class OurQualityModal extends Component
{
    use WithFileUploads, GeneralTrait;

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public OurQuality $ourQuality;
    public $id;
    public $form = "addForm";
    public $temporaryImageUrl = null;

    public function updated($property)
    {
        if (str_contains($property, 'image')) {
            try {
                $this->updateTemporaryImageUrl($property);
            } catch (\Exception $e) {
                $this->dispatch('open-errors', __('forms.common.errors.img.not_img'));
            }
        }
    }

    private function updateTemporaryImageUrl($property)
    {
        $form = $property === "addForm.image" ? $this->addForm : $this->updateForm;
        $this->temporaryImageUrl = $form->image?->temporaryUrl() ;
    }

    public function mount()
    {
        if (!$this->id) {
            return;
        }

        $this->form = "updateForm";

        try {
            $this->loadOurQualityData();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Error in OurQualityModal mount method: ' . $e->getMessage(), [
                'exception' => $e,
                'our_quality_id' => $this->id,
            ]);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    protected function loadOurQualityData()
    {
        $this->ourQuality = OurQuality::with(['image' => function ($query) {
            $query->where('use_case', 'our_quality');
        }])->findOrFail($this->id);

        $this->loadImage();
        $this->fillUpdateForm();
    }

    protected function loadImage()
    {
        $this->temporaryImageUrl = $this->ourQuality->image?->url ;
    }

    protected function fillUpdateForm()
    {
        $this->updateForm->fill([
            'id' => $this->id,
            'name_fr' => $this->ourQuality->name_fr,
            'name_ar' => $this->ourQuality->name_ar,
            'name_en' => $this->ourQuality->name_en,
        ]);
    }

    public function handleSubmit()
    {
        $response = isset($this->id)
            ? $this->updateForm->save($this->ourQuality)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-our-qualities-table');
            $this->dispatch('open-toast', $response['message']);

            if (!isset($this->id)) {
                $this->addForm->reset();
                $this->temporaryImageUrl = null;
            }
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    public function render()
    {
        return view('livewire.core.super-admin.our-quality-modal');
    }
}
