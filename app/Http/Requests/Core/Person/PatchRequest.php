<?php

namespace App\Http\Requests\Core\Person;

use App\Http\Requests\ApiFormRequest;
use App\Models\User;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class PatchRequest extends ApiFormRequest
{

    use ResponseTrait;

    public function authorize(): bool
    {
        return true;
    }



    protected function prepareForValidation(): void
    {
        $personId = $this->route('person')->id;
        $person = ['id' => $personId];
        $user = ['person_id' => $personId];

        // Direct boolean and employee fields
        if ($this->filled('isPaid')) {
            $person['is_paid'] = $this->boolean('isPaid');
        }
        if ($this->filled('isActive')) {
            $user['is_active'] = $this->boolean('isActive');
        }
        if ($this->filled('email')) {
            $user['email'] = $this->email;
        }


        // Map camelCase input keys to snake_case db keys
        $fieldMap = [
            'employeeNumber' => 'employee_number',
            'lastNameFr' => 'last_name_fr',
            'lastNameAr' => 'last_name_ar',
            'firstNameFr' => 'first_name_fr',
            'firstNameAr' => 'first_name_ar',
            'cardNumber' => 'card_number',
            'birthDate' => 'birth_date',
            'birthPlaceFr' => 'birth_place_fr',
            'birthPlaceAr' => 'birth_place_ar',
            'birthPlaceEn' => 'birth_place_en',
            'addressFr' => 'address_fr',
            'addressAr' => 'address_ar',
            'addressEn' => 'address_en',
        ];

        foreach ($fieldMap as $inputKey => $attributeKey) {
            if ($this->filled($inputKey)) {
                $person[$attributeKey] = $this->input($inputKey);
            }
        }

        // Sanitize and assign phone
        if ($this->filled('phone')) {
            $person['phone'] = $this->cleanPhone($this->input('phone'));
        }

        $this->merge([
            'person' => $person,
            'user' => $user,
        ]);
    }

    public function rules(): array
    {
        $nameRules = 'sometimes|string|min:3|max:100';
        $birthPlaceRules = 'sometimes|string|min:3|max:200';
        $addressRules = 'sometimes|string|min:10|max:400';
        $personId = $this->input('person.id');
        $user = User::where('person_id', $personId)->first();

        // Email rules: adapt depending on user existence
        $emailRules = [
            'sometimes',
            'email',
            'max:255',
            $user
                ? Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($user->id)
                : Rule::unique('users', 'email')->whereNull('deleted_at')
        ];

        return [

            'user.is_active' => ['sometimes', 'boolean'],

            'user.email' => $emailRules,

            'person.is_paid' => ['sometimes', 'boolean'],
            'person.employee_number' => [
                'nullable',
                'string',
                'size:10',
                Rule::unique('persons', 'employee_number')
                    ->whereNull('deleted_at')
                    ->ignore($this->input('person.id')),
            ],
            'image' => ['sometimes', 'file', 'mimes:jpeg,png,gif,ico,webp', 'max:10000'],

            // Personnel Info
            'person.last_name_fr' => $nameRules,
            'person.first_name_fr' => $nameRules,
            'person.last_name_ar' => $nameRules,
            'person.first_name_ar' => $nameRules,

            'person.card_number' => [
                'sometimes',
                'string',
                'min:6',
                'max:255',
                Rule::unique('persons', 'card_number')
                    ->whereNull('deleted_at')
                    ->ignore($this->input('person.id')),
            ],

            'person.birth_place_fr' => $birthPlaceRules,
            'person.birth_place_ar' => $birthPlaceRules,
            'person.birth_place_en' => $birthPlaceRules,

            'person.birth_date' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                'after:1920-01-01',
                'before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
            ],

            'person.address_fr' => $addressRules,
            'person.address_ar' => $addressRules,
            'person.address_en' => $addressRules,
            'person.phone' => [
                'nullable',
                'regex:/^(05|06|07)\d{8}$/',
                Rule::unique('persons', 'phone')->whereNull('deleted_at')->ignore($this->input('person.id')),
            ],
        ];
    }





    /**
     * Clean phone number to remove all non-digit characters.
     */
    protected function cleanPhone(string $value): string
    {
        $clean = preg_replace('/\s+/', '', $value);       // remove all whitespace
        $clean = trim($clean, "\"'");
        // remove surrounding " or '
        $this->responseTest($clean);
        return $clean;
    }

    public function messages(): array
    {
        return [
            'person.phone.regex' => __('forms.common.errors.not_match.phone'),
        ];
    }

        public function attributes(): array
    {

        return $this->returnTranslatedResponseAttributes('person', [
            'image',
            'name',
            'password',
            'email',
            'last_name_fr',
            'last_name_ar',
            'first_name_fr',
            'first_name_ar',
            'employee_number',
            'social_number',
            'card_number',
            'birth_place_fr',
            'birth_place_ar',
            'birth_place_en',
            'birth_date',
            'address_fr',
            'address_ar',
            'address_en',
            'phone'
        ]);
    }
}
