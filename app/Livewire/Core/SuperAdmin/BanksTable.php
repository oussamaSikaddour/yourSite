<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Models\Bank;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BanksTable extends Component
{

    use WithPagination, TableTrait, GeneralTrait;
    #[Url()]
    public $acronym = "";
    #[Url()]
    public $designation = "";
    #[Url()]
    public $code= "";
    public $local ="fr";
    protected array $filterable = ['designation', 'acronym', 'code'];
    protected array $validationRules = [
        'code' => ['nullable', 'string', 'max:255'],
        'acronym' => ['nullable', 'string', 'max:255'],
        'designation' => ['nullable', 'string', 'max:255'],
    ];





    /**
     * Reset all filters.
     */
    public function resetFilters()
    {
        $this->designation="";
        $this->acronym="";
        $this->code="";
        $this->resetPage();
    }

    /**
     * Get paginated list of our qualities with localized designations.
     */
#[Computed]
public function banks()
{

    $this->local = in_array($this->local, ['fr', 'en']) ? $this->local : 'fr';

    return Bank::query()
       // Filter by designation (Arabic or localized)
        ->when(!empty($this->designation), function ($q) {
            $containsArabic = preg_match('/\p{Arabic}/u', $this->designation);
            $column = $containsArabic ? 'designation_ar' : "designation_{$this->local}";
            $q->where($column, 'like', "%{$this->designation}%");
        })

        // Filter by acronym if provided
        ->when(!empty($this->acronym), fn ($q) =>
            $q->where('acronym', 'like', "%{$this->acronym}%")
        )

        // Filter by code if provided
        ->when(!empty($this->code), fn ($q) =>
            $q->where('code', 'like', "%{$this->code}%")
        )

        // Dynamic sorting & pagination
        ->orderBy($this->sortBy, $this->sortDirection)
        ->paginate($this->perPage);
}




    /**
     * Delete an OurQuality entity and its associated images.
     */
    #[On("delete-bank")]
    public function deleteBank( Bank $bank)
    {
        try {
            $bank->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteBank method: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Handle property updates and reset pagination if necessary.
     */


    public function openDeleteDialog($bank){
        $data=[
            "question" => "dialogs.title.bank",
            "details" =>["bank",$bank['acronym']],
            "actionEvent"=>[
                            "event"=>"delete-bank",
                            "parameters"=>$bank
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

public function mount(){
  $this->local = app()->getLocale();
}
    public function render()
    {

        return view('livewire.core.super-admin.banks-table');
    }
}
