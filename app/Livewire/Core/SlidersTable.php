<?php

namespace App\Livewire\Core;

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

class SlidersTable extends Component
{
    use WithPagination, TableTrait, GeneralTrait;

    // 🔗 Query parameters
    #[Url]
    public string $creator = '';
    #[Url]
    public string $name = '';
    #[Url]
    public string $sliderableType = '';
    #[Url]
    public string $sliderableName = '';
    #[Url]
    public ?int $sliderableId = null;

    public string $local = 'fr';
    public array $stateOptions = [];

    protected array $filterable = ['name', 'creator'];

    protected array $validationRules = [
        'name' => ['nullable', 'string', 'max:255'],
        'creator' => ['nullable', 'string', 'max:255'],
    ];

    /** Initialize component */
    public function mount(): void
    {
        $this->local = app()->getLocale();
        $this->stateOptions = config('core.options.PUBLISHING_STATE')[$this->local] ?? [];
    }

    /** Reset filters and pagination */
    public function resetFilters(): void
    {
        $this->reset(['name', 'creator', 'sliderableId', 'sliderableType']);
        $this->resetPage();
    }

    /** Fetch and filter sliders */
    #[Computed]
    public function sliders()
    {


        $query = Slider::query()
            ->with(['creator'])
            ->leftJoin('users', 'sliders.user_id', '=', 'users.id')
            ->when($this->sliderableId, fn($q) =>
                $q->where('sliders.sliderable_id', 'like', "%{$this->sliderableId}%")
            )
            ->when($this->sliderableType, fn($q) =>
                $q->where('sliders.sliderable_type', $this->sliderableType)
            )
            ->when($this->name, fn($q) =>
                $q->where('sliders.name', 'like', "%{$this->name}%")
            )
            ->when($this->creator, fn($q) =>
                $q->whereHas('creator', fn($sub) =>
                    $sub->where("users.name", 'like', "%{$this->creator}%")
                )
            )
            ->select([
                'sliders.*',
                "users.name as creator",
            ])
            ->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    /** Change slider state dynamically */
    #[On('selected-value-updated')]
    public function changeSliderState(Slider $slider, string $value): void
    {
        try {
            $slider->update(['state' => $value]);
        } catch (\Throwable $e) {
            Log::error('Error updating slider state:', ['message' => $e->getMessage()]);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /** Delete a slider */
    #[On('delete-slider')]
    public function deleteSlider(Slider $slider): void
    {
        try {
            $slider->delete();
        } catch (\Throwable $e) {
            Log::error('Error deleting slider:', ['message' => $e->getMessage()]);
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /** Opens confirmation dialog before deletion */
    public function openDeleteDialog(array $slider): void
    {
        $this->dispatch('open-dialog', [
            'question' => 'dialogs.name.slider',
            'details' => ['slider', $slider['name']],
            'actionEvent' => [
                'event' => 'delete-slider',
                'parameters' => $slider,
            ],
        ]);
    }

    /** Handle live updates + validation */
    public function updated(string $property): void
    {
        if (in_array($property, $this->filterable) || $property === 'perPage') {
            $this->resetPage();
        }

        if (isset($this->validationRules[$property])) {
            try {
                $this->validateOnly($property, $this->validationRules);
            } catch (ValidationException $e) {
                $this->dispatch('open-errors', $e->validator->errors()->all());
            }
        }
    }

    public function render()
    {
        return view('livewire.core.sliders-table');
    }
}
