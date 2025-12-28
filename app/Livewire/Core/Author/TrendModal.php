<?php

namespace App\Livewire\Core\Author;

use App\Livewire\Forms\Core\Trend\AddForm;
use App\Livewire\Forms\Core\Trend\UpdateForm;
use App\Models\Trend;
use App\Traits\Core\Common\DateAndTimeTrait;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class TrendModal extends Component
{


    use  GeneralTrait, DateAndTimeTrait;

    // Form bindings
    public AddForm $addForm;
    public UpdateForm $updateForm;

    // Models
    public Trend $trend;
    // State
    public $id;
    public $form = 'addForm';
    public $local = 'fr';

    // Editor preview (optional if using only TinyMCE)
    public $contentFr = '';
    public $contentAr = '';
    public $contentEn = '';

    /* ---------------- Computed Properties ---------------- */

    #[Computed()]
    public function formEntity()
    {
        return $this->id ? $this->updateForm : $this->addForm;
    }


    /* ---------------- Lifecycle ---------------- */

    public function mount()
    {
        $this->dispatch('initialize-tiny-mce');
        $this->local = app()->getLocale();

        if ($this->id) {
            $this->form = 'updateForm';
            $this->loadTrendDataSafe();
        } else {
            $this->addForm->fill([ 'user_id' => auth()->id()]);
        }
    }

    public function render()
    {
        return view('livewire.core.author.trend-modal');
    }

    /* ---------------- Slide Data Handling ---------------- */

    protected function loadTrendDataSafe()
    {
        try {
            $this->loadTrendData();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->logModelError($e, 'trend');
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    protected function loadTrendData()
    {
        $this->trend = Trend::findOrFail($this->id);
        $this->fillUpdateForm();
    }

    protected function fillUpdateForm()
    {
        $this->updateForm->fill([
            'id'         => $this->id,
            'title_ar'   => $this->trend->title_ar,
            'title_fr'   => $this->trend->title_fr,
            'title_en'   => $this->trend->title_en,
            'content_ar' => $this->trend->content_ar,
            'content_fr' => $this->trend->content_fr,
            'content_en' => $this->trend->content_en,
            'start_at' => $this->parseDate($this->trend->start_at),
            'end_at' => $this->parseDate($this->trend->end_at),
            'user_id'  => auth()->id(),
        ]);

        // Optional preview state for multilingual editors
        $this->contentFr = $this->trend->content_fr;
        $this->contentAr = $this->trend->content_ar;
        $this->contentEn = $this->trend->content_en;
    }

    /* ---------------- Form Submission ---------------- */

    public function handleSubmit()
    {
        $response = $this->id
            ? $this->updateForm->save($this->trend)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-trends-table');
            $this->dispatch('open-toast', $response['message']);

            if (!$this->id) {
                $this->addForm->reset();
                 $this->addForm->fill([ 'user_id' => auth()->id()]);
            }
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }




    #[On('set-content-fr')]
    public function setContentFr($content)
    {
        $this->formEntity->fill(['content_fr' => $content]);
    }

    #[On('set-content-en')]
    public function setContentEn($content)
    {
        $this->formEntity->fill(['content_en' => $content]);
    }

    #[On('set-content-ar')]
    public function setContentAr($content)
    {
        $this->formEntity->fill(['content_ar' => $content]);
    }

    /* ---------------- Helpers ---------------- */

    protected function logModelError($exception, string $model)
    {
        Log::error("Error in SlideModal mount ({$model} not found):", [
            'message' => $exception->getMessage(),
            'exception' => $exception,
            'trend_id' => $this->id,
        ]);
    }
}
