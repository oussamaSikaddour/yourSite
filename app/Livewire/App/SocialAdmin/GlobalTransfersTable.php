<?php

namespace App\Livewire\App\SocialAdmin;

use App\Models\GlobalBankTransfer;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class GlobalTransfersTable extends Component
{
    use WithPagination, TableTrait, GeneralTrait;
    #[Url()]
    public $date_min;
    #[Url()]
    public $date_max;
    #[Url()]
    public $number;
    #[Url()]
    public $motive;
    #[Url()]
    public $total_amount;

    public $initiatorColumn ="name_fr";

    public $locale ="fr";



    protected array $filterable = ['date_min', 'date_max', 'number','totalAmount'];
    protected array $validationRules = [
        'date_min' => ['nullable', 'date'],
        'date_max' => ['nullable', 'date', 'after_or_equal:dateMin'],
        'number' => ['nullable', 'string'],
        'total_amount' => ['nullable', 'numeric', 'min:0.01', 'max:9999999999999.99'],
    ];





    /**
     * Reset all filters.
     */
    public function resetFilters()
    {
        $this->date_min="";
        $this->date_max="";
        $this->total_amount="";
        $this->number="";
        $this->motive="";
        $this->resetPage();
    }

    /**
     * Get paginated list of our qualities with localized designations.
     */
    #[Computed()]
    public function globalTransfers()
    {
        $this->locale = app()->getLocale();


        $motiveColumn = match ($this->locale) {
            'ar' => 'motive_ar',
            'en' => 'motive_en',
            default => 'motive_fr',
        };

        $this->initiatorColumn = match ($this->locale) {
            'ar' => 'name_ar',
            default => 'name_fr',
        };

        $query = GlobalBankTransfer::query()
            ->with('theInitiator')
            ->leftJoin('users', 'global_bank_transfers.user_id', '=', 'users.id');

        if (!empty($this->motive)) {
            $query->where($motiveColumn, 'like', "%{$this->motive}%");
        }

        if (!empty($this->number)) {
            $query->where('number', 'like', "%{$this->number}%");
        }

        if (!empty($this->total_amount)) {
            $query->where('totalAmount', 'like', "%{$this->total_amount}%");
        }


        if (!empty($this->date_min) && !empty($this->date_max)) {
            $query->whereBetween('date', [$this->date_min, $this->date_max]);
        } elseif (!empty($this->date_min)) {
            $query->whereDate('date', '>=', $this->date_min);
        } elseif (!empty($this->date_max)) {
            $query->whereDate('date', '<=', $this->date_max);
        }
        return $query->select('global_bank_transfers.*', "users.{$this->initiatorColumn} as initiator")
                     ->orderBy($this->sortBy, $this->sortDirection)
                     ->paginate($this->perPage);
    }



    /**
     * Delete an OurQuality entity and its associated images.
     */
    #[On("delete-global-transfer")]
    public function deleteGlobalTransfer( GlobalBankTransfer $globalTransfer)
    {
        try {
            $globalTransfer->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteGlobalTransfer method: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Handle property updates and reset pagination if necessary.
     */


    public function openDeleteDialog($globalTransfer){
        $data=[
            "question" => "dialogs.title.global_transfer",
            "details" =>["global_transfer",$globalTransfer['motive_'.$this->locale]],
            "actionEvent"=>[
                            "event"=>"delete-global-transfer",
                            "parameters"=>$globalTransfer
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
        return view('livewire.app.social-admin.global-transfers-table');
    }
}
