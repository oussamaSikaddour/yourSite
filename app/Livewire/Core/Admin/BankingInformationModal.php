<?php

namespace App\Livewire\Core\Admin;

use App\Livewire\Forms\Core\BankingInformation\AddForm;
use App\Livewire\Forms\Core\BankingInformation\UpdateForm;
use App\Models\Bank;
use App\Models\BankingInformation;
use App\Models\GeneralSetting;
use App\Models\Person;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class BankingInformationModal extends Component
{
    use TableTrait, GeneralTrait;

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public BankingInformation $bankingInformation;
    public $bankingInformation_id;
    public $bankable = [];
    public $bankableType;
    public $selectedChoice;
    public $bankableName = "";
    public $form = "addForm";
    public $activeBankingInformationId;
    public $banksOptions = [];
    public $locale = "fr";

    /**
     * Fetch all banks for the dropdown.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    #[Computed]
    public function banks()
    {
        return Bank::get(['id', 'acronym']);
    }

    /**
     * Reset the form fields.
     */
    public function resetForm()
    {
        $this->form = "addForm";
        $this->bankingInformation_id = null;
        $this->selectedChoice = null;
        $this->addForm->reset();
        $this->updateForm->reset();
        $this->addForm->fill([
            'bankable_id' => $this->bankable['id'],
            'bankable_type' => $this->returnBankableType($this->bankableType)
        ]);
    }

    /**
     * Update the form based on the selected choice.
     */
    public function updatedSelectedChoice()
    {
        $this->bankingInformation_id = $this->selectedChoice;
        $this->form = isset($this->bankingInformation_id) ? 'updateForm' : 'addForm';


        $this->setBankingInformationForm($this->bankingInformation_id);
    }

    /**
     * Update the active banking information.
     */
    public function updatedActiveBankingInformationId()
    {
        try {
            // Deactivate all banking information for the bankable
            BankingInformation::where("bankable_id", $this->bankable['id'])
                ->where('bankable_type', $this->returnBankableType($this->bankableType))
                ->update(['is_active' => false]);

            // Activate the selected banking information
            BankingInformation::where('id', $this->activeBankingInformationId)
                ->update(['is_active' => true]);
        } catch (\Exception $e) {
            Log::error('Error in updatedActiveBankingInformationId: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Fetch banking information for the current bankable.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    #[Computed]
    public function bankingInformations()
    {
        $agencyField = match ($this->locale) {
            'ar' => 'banking_information.agency_ar',
            'fr' => 'banking_information.agency_fr',
            'en' => 'banking_information.agency_en',
            default => 'banking_information.agency_fr', // Fallback to default
        };

        return BankingInformation::query()
            ->with(['bank'])
            ->join('banks', 'banking_information.bank_id', '=', 'banks.id')
            ->where('bankable_id', $this->bankable['id'])
            ->where('bankable_type', $this->returnBankableType($this->bankableType))
            ->select(
                'banking_information.id',
                "$agencyField as agency",
                'banking_information.agency_code',
                'banking_information.account_number',
                'banking_information.is_active',
                'banking_information.created_at',
                'banks.acronym as bank_acronym'
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();
    }

    /**
     * Initialize the component.
     */
    public function mount()
    {

        $this->locale = app()->getLocale();


        $localeName = app()->getLocale() === "en" ? "fr" : app()->getLocale();
        $this->bankableName =
            $this->bankable["name_{$localeName}"]
            ?? $this->bankable['name']
            ?? $this->bankable['app_name']
            ?? '';

        $this->banksOptions = $this->populateSelectorOption($this->banks(),  'id', 'acronym', __('selectors.default.banks'));
        // Fetch the active banking information for the bankable
        $activeBankingInformation = BankingInformation::where('bankable_id', $this->bankable['id'])
            ->where('bankable_type', $this->returnBankableType($this->bankableType))
            ->where('is_active', true)
            ->first();
        $this->activeBankingInformationId = $activeBankingInformation?->id;
        $this->resetForm();
    }



    private function returnBankableType($type)
    {
        return match ($type) {
            'person'   => Person::class,
            'app'    => GeneralSetting::class
        };
    }

    /**
     * Handle form submission.
     */
    public function handleSubmit()
    {
        $this->dispatch('form-submitted');

        $response = isset($this->bankingInformation_id)
            ? $this->updateForm->save($this->bankingInformation)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-banking-information-table');

            if ($this->form == "addForm") {
                $this->resetForm();
            }

            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }


    /**
     * Populate the update form with banking information data.
     *
     * @param int $bankingInformationId
     */
    public function setBankingInformationForm($bankingInformationId)
    {
        try {
            $this->bankingInformation = BankingInformation::where('bankable_id', $this->bankable['id'])
                ->where('bankable_type', $this->returnBankableType($this->bankableType))
                ->findOrFail($bankingInformationId);


            $this->updateForm->fill([
                'bankable_id' => $this->bankable['id'],
                'bankable_type' => $this->returnBankableType($this->bankableType),
                'id' => $bankingInformationId,
                'bank_id' => $this->bankingInformation->bank_id,
                'agency_ar' => $this->bankingInformation->agency_ar,
                'agency_fr' => $this->bankingInformation->agency_fr,
                'agency_en' => $this->bankingInformation->agency_en,
                'agency_code' => $this->bankingInformation->agency_code,
                'account_number' => $this->bankingInformation->account_number,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in setBankingInformationForm: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Open the delete banking information dialog.
     *
     * @param array $bankingInformation
     */
    public function openDeleteBankingInformationDialog($bankingInformation)
    {
        $data = [
            "question" => "dialogs.title.banking_information",
            "details" => ["banking_information", $bankingInformation['account_number']],
            "actionEvent" => [
                "event" => "delete-banking-information",
                "parameters" => $bankingInformation
            ]
        ];

        $this->dispatch("open-dialog", $data);
    }

    /**
     * Delete the banking information.
     *
     * @param BankingInformation $bankingInformation
     */
    #[On("delete-banking-information")]
    public function deleteBankingInformation(BankingInformation $bankingInformation)
    {
        try {
            $bankingInformation->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteBankingInformation: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Render the component.
     */
    public function render()
    {
        $this->dispatch("init-tooltip");
        return view('livewire.core.admin.banking-information-modal');
    }
}
