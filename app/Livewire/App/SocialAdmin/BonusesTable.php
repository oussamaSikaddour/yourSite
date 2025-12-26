<?php

namespace App\Livewire\App\SocialAdmin;

use App\Models\Bonus;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BonusesTable extends Component
{

    use WithPagination, TableTrait, GeneralTrait;
    #[Url()]
    public $titled;
    #[Url()]
    public $amount;
    public $simplisticView=false;
    public $selectedBonuses=[];
    public $oldSelectedBonuses=[];

    protected array $filterable = ['titled', 'amount'];
    protected array $validationRules = [
        'amount' => ['nullable', 'numeric', 'min:0.01', 'max:9999999999999.99'],
        'titled' => ['nullable', 'string', 'max:255'],
    ];





    /**
     * Reset all filters.
     */
    public function resetFilters()
    {
        $this->amount="";
        $this->titled="";
        $this->resetPage();
    }

    /**
     * Get paginated list of our qualities with localized designations.
     */
    #[Computed()]
    public function bonuses()
    {
        $query = Bonus::query();
        if (!empty($this->designation)) {
            $query->where(function ($q) {
                $localeColumn = match (app()->getLocale()) {
                    'ar' => 'titled_ar',
                    'en' => 'titled_en',
                    default => 'titled_fr',
                };
                $q->where($localeColumn, 'like', "%{$this->titled}%");
            });
        }
        $query->where('amount', 'like', "%{$this->amount}%");
        return $query->orderBy($this->sortBy, $this->sortDirection)
                     ->paginate($this->perPage);
    }



    /**
     * Delete an OurQuality entity and its associated images.
     */
    #[On("delete-bonus")]
    public function deleteBonus( bonus $bonus)
    {
        try {
            $bonus->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteBonus method: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Handle property updates and reset pagination if necessary.
     */


    public function openDeleteDialog($bonus){

        $locale = app()->getLocale();
        $name=$bonus["name_$locale"] ?? $bonus['name_fr'] ?? '';
        $data=[
            "question" => "dialogs.title.bonus",
            "details" =>["bonus",$name],
            "actionEvent"=>[
                            "event"=>"delete-bonus",
                            "parameters"=>$bonus
                            ]
            ];

    $this->dispatch("open-dialog", $data);
    }


    public function updated(string $property): void
{

    if ($property  && $this->selectedBonuses != $this->oldSelectedBonuses) {
        $this->oldSelectedBonuses = $this->selectedBonuses;

        $this->dispatch("selected-bonuses",$this->selectedBonuses);

    }
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
        return view('livewire.app.social-admin.bonuses-table');
    }
}
