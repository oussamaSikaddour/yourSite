<?php

namespace App\Livewire\Forms\Core\FieldSpecialty;

use App\Models\FieldSpecialty;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait;

    public $acronym = "";
    public $designation_ar = "";
    public $designation_fr = "";
    public $designation_en = "";
    public $field_id = "";

    /**
     * Define validation rules.
     */
    public function rules()
    {

        $localizedDesignationRules = [
            'nullable', 'string', 'min:5', 'max:60',
            Rule::unique('field_specialties')
                    ->whereNull('deleted_at')
        ];
        return [
            'acronym' => [
                'required',
                'string',
                 "max:10",
                Rule::unique('field_specialties', 'acronym')
                    ->whereNull('deleted_at'),
            ],
            'designation_fr' =>  $localizedDesignationRules,
            'designation_ar' =>  $localizedDesignationRules,
            'designation_en' =>  $localizedDesignationRules,
        'field_id' => [
            'required',
            'integer',
            Rule::exists('field_specialties', 'id'),
        ],
        ];
    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('field', [
            'acronym','designation_ar','designation_fr','designation_en','field_id'
        ]);
    }

    /**
     * Save the fielding information.
     */
    public function save()
    {
        try {
            // Validate request data
            $data = $this->validate();

            // Create fielding information record
            FieldSpecialty::create($data);

            return $this->response(true, message: __("forms.field_specialty.responses.add_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in FieldSpecialty AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
