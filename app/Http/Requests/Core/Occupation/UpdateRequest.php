<?php

namespace App\Http\Requests\Core\Occupation;

use App\Http\Requests\ApiFormRequest;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Validation\Rule;

class UpdateRequest extends ApiFormRequest
{

    use ResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize camelCase to snake_case for validation.
     */
protected function prepareForValidation(): void
{
    $fields = [
        'description_fr'       => 'descriptionFr',
        'description_ar'       => 'descriptionAr',
        'description_en'       => 'descriptionEn',
        'field_id'             => 'fieldId',
        'field_specialty_id'   => 'fieldSpecialtyId',
        'field_grade_id'       => 'fieldGradeId',
        'is_active'            => 'isActive',
    ];

    $mergeData = [];

    foreach ($fields as $snake => $camel) {
        if ($this->$camel !== null) {
            $mergeData[$snake] = $this->$camel;
        }
    }

    $this->merge($mergeData);
}


    /**
     * Get the validation rules.
     */
    public function rules(): array
    {

        return [
           'description_fr'        => ['nullable', 'string', 'min:2', 'max:255'],
            'description_ar'        => ['nullable', 'string', 'min:2', 'max:255'],
            'description_en'        => ['nullable', 'string', 'min:2', 'max:255'],
            'experience'            => ['nullable', 'numeric', 'between:0,50'],
            'field_id'              => ['nullable', 'exists:fields,id'],
            'field_specialty_id'    => ['nullable', 'exists:field_specialties,id'],
            'field_grade_id'        => ['nullable', 'exists:field_grades,id'],
            // is_active flag
            'is_active' => ['nullable', 'boolean'],
        ];
    }


        public function messages(): array
    {
        return [
            'field_id.required'          => __('forms.occupation.errors.field_required'),
            'field_id.exists'            => __('forms.occupation.errors.field_exists'),
            'field_specialty_id.exists'  => __('forms.occupation.errors.field_specialty_exists'),
            'field_grade_id.exists'      => __('forms.occupation.errors.field_grade_exists'),
        ];
    }


        public function attributes(): array
    {
        return $this->returnTranslatedResponseAttributes('occupation', [
            'field_id',
            'experience',
            'field_specialty_id',
            'field_grade_id',
            'description_fr',
            'description_ar',
            'description_en'
        ]);
    }
}
