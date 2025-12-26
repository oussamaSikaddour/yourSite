<?php

namespace App\Livewire\Core\Author;

use App\Models\Trend;
use App\Traits\Core\Common\DateAndTimeTrait;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TrendsTable extends Component
{

        use WithPagination, TableTrait, GeneralTrait,DateAndTimeTrait;
    #[Url()]
    public $title = "";
     #[Url()]
    public $state = "";
    public $local="fr";
    public array $stateOptions=[];
    public array $menuTypesOptions=[];
    protected array $filterable = ['title','state'];
    protected array $validationRules = [
        'title' => ['nullable', 'string', 'max:255'],
        'state' => 'nullable|in:published,not_published'
    ];




    public function mount(){
     $this->local = app()->getLocale();
    $this->stateOptions = config('core.options.PUBLISHING_STATE')[$this->local];
    }

    /**
     * Reset all filters.
     */
    public function resetFilters()
    {
        $this->title="";
        $this->state="";
        $this->resetPage();
    }

    /**
     * Get paginated list of our qualities with localized designations.
     */
    #[Computed()]
public function trends()
{
    $query = Trend::query()
          ->where('title_'.$this->local, 'like', "%{$this->title}%")
          ->where('state', 'like', "{$this->state}%")
          ->orderBy($this->sortBy, $this->sortDirection);
    return $query->paginate($this->perPage);
}



    #[On("selected-value-updated")]
    public function changeTrendState(Trend $trend, $value)
    {
        try {
        $trend->update(['state' => $value]);
        } catch (\Exception $e) {
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }


    /**
     * Delete an OurQuality entity and its associated images.
     */
    #[On("delete-trend")]
    public function deleteTrend( Trend $trend)
    {
        try {
            $trend->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteTrend method: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Handle property updates and reset pagination if necessary.
     */


    public function openDeleteDialog($trend){
        $data=[
            "question" => "dialogs.title.trend",
            "details" =>["trend",$trend['title_'.$this->local]],
            "actionEvent"=>[
                            "event"=>"delete-trend",
                            "parameters"=>$trend
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
        return view('livewire.core.author.trends-table');
    }
}
