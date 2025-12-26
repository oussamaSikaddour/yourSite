<?php

namespace App\Livewire\Core;

use App\Livewire\Forms\Core\Slider\AddForm;
use App\Livewire\Forms\Core\Slider\UpdateForm;
use App\Models\Slider;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SliderModal extends Component
{
    use GeneralTrait;

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public ?Slider $slider = null;

    public ?int $id = null;
    public string $form = 'addForm';
    public string $local = 'fr';
    public ?int $sliderableId = null;
    public string $sliderableType = '';
    public array $stateOptions = [];

    #[Computed]
    public function formEntity()
    {
        return $this->id ? $this->updateForm : $this->addForm;
    }

    /** Mount lifecycle hook */
    public function mount(): void
    {
        $this->local = app()->getLocale();
        $this->stateOptions = config('constants.PUBLISHING_STATE')[$this->local] ?? [];
        $this->dispatch('initialize-tiny-mce');
        if ($this->id) {
            $this->form = 'updateForm';
            $this->loadSliderDataSafely();
        } else {
            $this->prefillAddForm();
        }
    }

    /** Loads the slider safely with proper error handling */
    protected function loadSliderDataSafely(): void
    {
        try {
            $this->slider = Slider::findOrFail($this->id);
            $this->fillUpdateForm();
        } catch (ModelNotFoundException $e) {
            Log::error('SliderModal mount failed', [
                'message' => $e->getMessage(),
                'slider_id' => $this->id,
            ]);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /** Prefills AddForm defaults */
    protected function prefillAddForm(): void
    {
        $this->addForm->fill([
            'user_id' => auth()->id(),
            'sliderable_id' => $this->sliderableId,
            'sliderable_type' => $this->sliderableType,
        ]);
    }

    /** Fill UpdateForm from model */
    protected function fillUpdateForm(): void
    {
        $this->updateForm->fill([
            'id' => $this->id,
            'user_id' => auth()->id(),
            'name' => $this->slider->name,
            'position' => $this->slider->position,
            'sliderable_type' => $this->slider->sliderable_type,
            'sliderable_id' => $this->slider->sliderable_id,
            'state' => $this->slider->state,
        ]);
    }

    /** Handles create or update submission */
    public function handleSubmit(): void
    {
        $form = $this->id ? $this->updateForm : $this->addForm;
        $response = $this->id
            ? $form->save($this->slider)
            : $form->save();

        if ($response['status']) {
            $this->dispatch('update-sliders-table');
            $this->dispatch('open-toast', $response['message']);

            if (!$this->id) {
                $this->resetAddForm();
            }
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    /** Resets AddForm and reinitializes defaults */
    protected function resetAddForm(): void
    {
        $this->addForm->reset();
        $this->prefillAddForm();
    }



    public function render()
    {
        return view('livewire.core.slider-modal');
    }
}
