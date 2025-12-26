<?php

namespace App\Livewire\Core\Admin;

use App\Models\Menu;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MenusTable extends Component
{
    use WithPagination, TableTrait, GeneralTrait;
    #[Url()]
    public $title = "";

    #[Url()]
    public $state = "";
    public $local = "fr";
    public array $stateOptions = [];
    protected array $filterable = ['title', 'state'];
    protected array $validationRules = [
        'title' => ['nullable', 'string', 'max:255'],
        'state' => 'nullable|in:published,not_published',

    ];




    public function mount()
    {
        $this->local = app()->getLocale();
        $this->stateOptions = config('core.options.PUBLISHING_STATE')[$this->local];
    }

    /**
     * Reset all filters.
     */
    public function resetFilters()
    {
        $this->title = "";
        $this->state = "";
        $this->resetPage();
    }

    /**
     * Get paginated list of our qualities with localized designations.
     */
    #[Computed()]
    public function menus()
    {
        $query = Menu::query()
            ->where('title_' . $this->local, 'like', "%{$this->title}%")
            ->where('state', 'like', "{$this->state}%")
            ->orderBy($this->sortBy, $this->sortDirection);
        return $query->paginate($this->perPage);
    }



    #[On("selected-value-updated")]
    public function changeMenuState(Menu $menu, $value)
    {
        try {
            $menu->update(['state' => $value]);
        } catch (\Exception $e) {
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }


    /**
     * Delete an OurQuality entity and its associated images.
     */
    #[On("delete-menu")]
    public function deleteMenu(Menu $Menu)
    {
        try {
            $Menu->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteMenu method: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Handle property updates and reset pagination if necessary.
     */


    public function openDeleteDialog($menu)
    {
        $data = [
            "question" => "dialogs.title.menu",
            "details" => ["menu", $menu['title_' . $this->local]],
            "actionEvent" => [
                "event" => "delete-menu",
                "parameters" => $menu
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
        return view('livewire.core.admin.menus-table');
    }
}
