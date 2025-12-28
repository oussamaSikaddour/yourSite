<?php

namespace App\Livewire\Core\Admin;

use App\Models\ExternalLink;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ExternalLinksTable extends Component
{
            use WithPagination, TableTrait;
    #[Url()]
    public $name = "";
    #[Url()]
    public $url = "";
    public $local="fr";
    public $menuId;
    protected array $filterable = ['name','url'];
    protected array $validationRules = [
        'name' => ['nullable', 'string', 'max:255'],
        'url' => 'nullable|url',
    ];




    public function mount(){
     $this->local = app()->getLocale();
    }

    /**
     * Reset all filters.
     */
    public function resetFilters()
    {
        $this->name="";
        $this->url="";
        $this->resetPage();
    }

    /**
     * Get paginated list of our qualities with localized designations.
     */
    #[Computed()]
public function externalLinks()
{
    $query = ExternalLink::query()
          ->where("menu_id",$this->menuId)
          ->where('name_'.$this->local, 'like', "%{$this->name}%")
          ->where('url', 'like', "%{$this->url}%")
          ->orderBy($this->sortBy, $this->sortDirection);
    return $query->paginate($this->perPage);
}





    /**
     * Delete an OurQuality entity and its associated images.
     */
    #[On("delete-external-link")]
    public function deleteExternalLink( ExternalLink $externalLink)
    {
        try {
            $externalLink->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteExternalLink method: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Handle property updates and reset pagination if necessary.
     */


    public function openDeleteDialog($externalLink){
        $data=[
            "question" => "dialogs.title.external_link",
            "details" =>["external_link",$externalLink['name_'.$this->local]],
            "actionEvent"=>[
                            "event"=>"delete-external-link",
                            "parameters"=>$externalLink
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
        return view('livewire.core.admin.external-links-table');
    }
}
