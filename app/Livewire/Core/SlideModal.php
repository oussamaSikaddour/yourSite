<?php

namespace App\Livewire\Core;

use App\Livewire\Forms\Core\Slide\AddForm;
use App\Livewire\Forms\Core\Slide\UpdateForm;
use App\Models\Slide;
use App\Models\Slider;
use App\Traits\Core\Common\DateAndTimeTrait;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class SlideModal extends Component
{
    use WithFileUploads, GeneralTrait, DateAndTimeTrait;

    // Form bindings
    public AddForm $addForm;
    public UpdateForm $updateForm;

    // Models
    public Slide $slide;
    public Slider $slider;

    // State
    public $id;
    public $form = 'addForm';
    public $local = 'fr';
    public $sliderId;
    public $temporaryImageUrl;
    public $orderOptions = [];



    /* ---------------- Computed Properties ---------------- */

    #[Computed]
    public function formEntity()
    {
        return $this->id ? $this->updateForm : $this->addForm;
    }

    #[Computed]
    public function orders()
    {
        return Slide::where('slider_id', $this->sliderId)->get(['order']);
    }


    /* ---------------- Lifecycle ---------------- */

    public function mount()
    {

        $this->local = app()->getLocale();

        try {
            $this->slider = Slider::findOrFail($this->sliderId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->logModelError($e, 'slider');
            $this->dispatch('open-errors', __('forms.common.errors.default'));
            return;
        }

        if ($this->orders->isNotEmpty()) {
            $this->setOrderOptions();
        }
        if ($this->id) {
            $this->form = 'updateForm';
            $this->loadSlideDataSafe();
        } else {
            $this->addForm->fill(['slider_id' => $this->sliderId]);
        }
    }

    public function render()
    {
        return view('livewire.core.slide-modal');
    }

    /* ---------------- Slide Data Handling ---------------- */

    protected function loadSlideDataSafe()
    {
        try {
            $this->loadSlideData();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->logModelError($e, 'slide');
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    protected function loadSlideData()
    {
        $this->slide = Slide::with(['image' => fn($q) => $q->where('use_case', 'slide')])
            ->findOrFail($this->id);

        $this->temporaryImageUrl = $this->slide->image->url;
        $this->fillUpdateForm();
    }

    protected function fillUpdateForm()
    {
        $this->updateForm->fill([
            'id'         => $this->id,
            'title_ar'   => $this->slide->title_ar,
            'title_fr'   => $this->slide->title_fr,
            'title_en'   => $this->slide->title_en,
            'content_ar' => $this->slide->content_ar,
            'content_fr' => $this->slide->content_fr,
            'content_en' => $this->slide->content_en,
            'slider_id'  => $this->sliderId,
            'order'      => $this->slide->order,
        ]);
    }

    /* ---------------- Form Submission ---------------- */

    public function handleSubmit()
    {
        $response = $this->id
            ? $this->updateForm->save($this->slide, $this->slider)
            : $this->addForm->save($this->slider);

        if ($response['status']) {
            $this->dispatch('update-slides-table');
            $this->dispatch('open-toast', $response['message']);

            if (!$this->id) {
                $this->addForm->reset();
                $this->addForm->fill(['slider_id' => $this->sliderId]);
                $this->temporaryImageUrl = null;
                if ($this->orders->isNotEmpty()) {
                    $this->setOrderOptions();
                }
            }
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    /* ---------------- Order Options ---------------- */

    public function setOrderOptions()
    {
        $this->orderOptions = $this->populateSelectorOption(
            $this->orders(),
            'order',
            'order',
            ignoreDefaultValue: true
        );

        // Get the maximum value instead of the maximum key
        $maxOrder = max($this->orderOptions);

        if (!isset($this->id)) {

            $this->orderOptions[$maxOrder + 1] =  $maxOrder + 1;
        }
        $this->orderOptions = array_reverse($this->orderOptions, true);
    }


    /* ---------------- Image Handling ---------------- */

    protected function updateTemporaryImageUrl()
    {
        try {
            $this->temporaryImageUrl = $this->formEntity->image->temporaryUrl();
        } catch (\Exception $e) {
            $this->dispatch('open-errors', __('forms.common.errors.img.not_img'));
        }
    }

    /* ---------------- Livewire Hooks ---------------- */

    public function updated($property)
    {
        if (in_array($property, ['addForm.image', 'updateForm.image'])) {
            $this->updateTemporaryImageUrl();
        }
    }



    /* ---------------- Helpers ---------------- */

    protected function logModelError($exception, string $model)
    {
        Log::error("Error in SlideModal mount ({$model} not found):", [
            'message' => $exception->getMessage(),
            'exception' => $exception,
            'slide_id' => $this->id,
        ]);
    }
}
