<?php

namespace App\Http\Requests\Core\BankingInformation;

use App\Http\Requests\ApiFormRequest;
use App\Models\Person;
use App\Models\User;
use App\Rules\ValidAccountNumber;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Validation\Rule;

class CreateRequest extends ApiFormRequest
{

    use ResponseTrait;
    protected array $morphMap = [];

    public function __construct()
    {
        parent::__construct();

        // Types polymorphiques autorisés
        $this->morphMap = [
            'person' => Person::class,
        ];
    }

    /**
     * Détermine si l'utilisateur peut exécuter la requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalise camelCase → snake_case et résout bankable_type.
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
     * Règles de validation.
     */
    public function rules(): array
    {
        // Validation pour les noms d'agence localisés (unique par bankable)
        $localizedAgencyRules = [
            'nullable',
            'string',
            'min:5',
            'max:60',
            Rule::unique('banking_information')->where(fn($query) => $query
                ->where('bankable_id', $this->bankable_id)
                ->where('bankable_type', $this->bankable_type)
                ->whereNull('deleted_at'))
        ];

        return [
            'bank_id'        => ['required', 'exists:banks,id'],
            'agency_ar'      => $localizedAgencyRules,
            'agency_en'      => $localizedAgencyRules,
            'agency_fr'      => $localizedAgencyRules,
            'agency_code'    => ['required', 'string', 'min:3', 'max:5'],
            'account_number' => [
                'required',
                'string',
                Rule::unique('banking_information', 'account_number')->whereNull('deleted_at'),
                new ValidAccountNumber(),
            ],
            'bankable_id'    => ['required', 'integer'],
            'bankable_type'  => ['required', Rule::in(array_values($this->morphMap))],
        ];
    }

    /**
     * Résout le modèle à partir de la clé polymorphique.
     */
    private function resolveBankableClass(?string $key): ?string
    {
        return $this->morphMap[$key] ?? $key;
    }

    /**
     * Messages de validation localisés.
     */
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
