<?php

namespace App\Livewire\Forms\Core\BankingInformation;

use App\Models\BankingInformation;
use App\Rules\Core\ValidAccountNumber;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    use ResponseTrait;
    public $id;
    public $agency_ar;
    public $agency_en;
    public $agency_fr;
    public $agency_code;
    public $account_number;
    public $bank_id;
    public $bankable_id;
    public $bankable_type;

    /**
     * Define validation rules.
     */
    public function rules(): array
    {
        $localizedAgencyRules = [
            'nullable',
            'string',
            'min:5',
            'max:60',
            Rule::unique('banking_information')
                ->where('bankable_id', $this->bankable_id)
                ->where('bankable_type', $this->bankable_type)
                ->whereNull('deleted_at')
                ->ignore($this->id),
        ];

        return [
            'bank_id' => ['required', 'exists:banks,id'],
            'agency_ar' => $localizedAgencyRules,
            'agency_en' => $localizedAgencyRules,
            'agency_fr' => $localizedAgencyRules,
            'agency_code' => ['required', 'string', 'min:3', 'max:5'],
            'account_number' => [
                'required',
                'string',
                Rule::unique('banking_information', 'account_number')->whereNull('deleted_at')->ignore($this->id),
                new ValidAccountNumber(),
            ],
            'bankable_id' => ['nullable', 'integer'],
            'bankable_type' => [
                'nullable',
                Rule::in(['App\Models\User', 'App\Models\GeneralSetting']),
            ],
        ];
    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('banking_information', [
            'agency_ar',
            'agency_en',
            'agency_fr',
            'agency_code',
            'bank_id',
            'account_number',
            'bankable_id',
            'bankable_type',
        ]);
    }

    /**
     * Save the banking information.
     */
    public function save($bankingInformation)
    {
        try {
            // Validate request data
            $data = $this->validate();
            // Create banking information record
            $bankingInformation->update($data);
            return $this->response(true, message: __("forms.banking_information.responses.update_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in BankingInformation UpdateForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
