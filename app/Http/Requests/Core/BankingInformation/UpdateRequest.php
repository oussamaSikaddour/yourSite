<?php

namespace App\Http\Requests\Core\BankingInformation;

use App\Http\Requests\ApiFormRequest;
use App\Models\Person;
use App\Models\User;
use App\Rules\ValidAccountNumber;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Validation\Rule;

class UpdateRequest extends ApiFormRequest
{

    use ResponseTrait;
    // Maps string identifiers to actual model class names for polymorphic relations
    protected array $morphMap = [];

    public function __construct()
    {
        parent::__construct();

        // Define accepted morphable types
        $this->morphMap = [
            'person' => Person::class,
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize input keys (camelCase to snake_case) and resolve model types before validation.
     */
protected function prepareForValidation(): void
{
    $fields = [
        'bank_id'         => 'bankId',
        'agency_ar'       => 'agencyAr',
        'agency_en'       => 'agencyEn',
        'agency_fr'       => 'agencyFr',
        'agency_code'     => 'agencyCode',
        'account_number'  => 'accountNumber',
        'bankable_id'     => 'bankableId',
        'is_active'       =>'isActive'
    ];

    $mergeData = [];

    foreach ($fields as $snake => $camel) {
        if ($this->$camel !== null) {
            $mergeData[$snake] = $this->$camel;
        }
    }

    // Special case for bankable_type with resolver
    if ($this->bankableType !== null) {
        $mergeData['bankable_type'] = $this->resolveBankableClass($this->bankableType);
    }

    $this->merge($mergeData);
}



    /**
     * Define validation rules for updating a banking information record.
     */
    public function rules(): array
    {
        // Retrieve the banking_information route-model for scoped uniqueness
        $bankingInfo = $this->route('banking_information');

        // Reusable rules for multilingual agency names (unique per bankable entity, ignoring soft-deleted records)
        $localizedAgencyRules = [
            'nullable', 'string', 'min:5', 'max:60',
            Rule::unique('banking_information')
                ->ignore($bankingInfo?->id)
                ->where(function ($query) {
                    return $query
                        ->where('bankable_id', $this->bankable_id)
                        ->where('bankable_type', $this->bankable_type)
                        ->whereNull('deleted_at');
                }),
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
                Rule::unique('banking_information', 'account_number')
                    ->ignore($bankingInfo?->id)
                    ->whereNull('deleted_at'),
                new ValidAccountNumber()
            ],
            'bankable_id' => ['required', 'integer'],
            'bankable_type' => ['required', Rule::in(array_values($this->morphMap))],
             'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Convert the provided morph type key into its full model class name.
     */
    private function resolveBankableType(?string $typeKey): ?string
    {
        return $this->morphMap[$typeKey] ?? $typeKey;
    }


        public function messages(): array
    {
        return [
            'bankable_id.required'    => __('forms.banking_information.validation.bankable_id_required'),
            'bankable_type.required'  => __('forms.banking_information.validation.bankable_type_required'),
            'bankable_type.in'        => __('forms.banking_information.validation.bankable_type_invalid'),
        ];
    }


                    public function attributes(): array
    {
        return $this->returnTranslatedResponseAttributes('banking_information', [
            'agency_ar', 'agency_en', 'agency_fr',
            'agency_code', 'bank_id', 'account_number',
            'bankable_id', 'bankable_type',
        ]);
    }
}
