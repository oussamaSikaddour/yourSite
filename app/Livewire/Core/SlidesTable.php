<?php

namespace App\Livewire\Core;

use App\Models\Slide;
use App\Models\Slider;

use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SlidesTable extends Component
{

    use WithPagination, TableTrait, GeneralTrait;
    #[Url()]
    public $title;
    #[Url()]
    public $sliderId;
    public $slider;
    public $local = "fr";

    protected array $filterable = ['title'];
    protected array $validationRules = [
        'title' => ['nullable', 'string', 'max:255'],
    ];

    public function mount()
    {
        $this->local = app()->getLocale();
        try {
            $this->slider = Slider::findOrFail($this->sliderId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                Log::error('Error Mount:', ['message' => $e->getMessage()]);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
            return;
        }
    }

    public function resetFilters()
    {
        $this->reset(['title']);
        $this->resetPage();
    }




#[Computed()]
public function slides()
{
    $query = Slide::query()->with(['image'])
        ->where('slider_id',$this->sliderId)
        ->where('title_'.$this->local,'like',"%{$this->title}%");
    return $query->orderBy($this->sortBy, $this->sortDirection)
        ->paginate($this->perPage);
}





    #[On("delete-slide")]
    public function deleteMenu(Slide $slide)
    {
        try {
            $data['order']=$slide->order;
            $this->setSlideOrder($data,$this->slider,true);
            $slide->delete();

        } catch (\Exception $e) {
            Log::error('Error deleting slide: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    public function openDeleteDialog($slide)
    {
        $data = [
            "question" => "dialogs.name.slide",
            "details" => ["slide", $slide['title_'.$this->local]],
            "actionEvent" => [
                "event" => "delete-slide",
                "parameters" => $slide
            ]
        ];

        $this->dispatch("open-dialog", $data);
    }



    public function updated(string $property): void
    {

        if (in_array($property, $this->filterable) || $property === 'perPage') {
            $this->resetPage();
        }
        if (array_key_exists($property, $this->validationRules)) {
            try {
                $this->validateOnly($property, $this->validationRules);
            } catch (ValidationException $e) {
                $this->dispatch('open-errors', $e->validator->errors()->all());
            }
        }
    }



    public function render()
    {
        return view('livewire.core.slides-table');
    }
}
