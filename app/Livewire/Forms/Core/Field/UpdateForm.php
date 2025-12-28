<?php

namespace App\Livewire\Forms\Core\Field;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    use ResponseTrait;

    public $id;
    public $acronym;
    public $designation_ar;
    public $designation_fr;
    public $designation_en;

    /**
     * Define validation rules.
     */
    public function rules()
    {

        $localizedDesignationRules = [
            'nullable', 'string', 'min:5', 'max:60',
            Rule::unique('fields')
                    ->whereNull('deleted_at')
                    ->ignore($this->id)
        ];
        return [
            'acronym' => [
                'required',
                'string',
                 "max:10",
                Rule::unique('fields', 'acronym')
                    ->whereNull('deleted_at')
                    ->ignore($this->id),
            ],

            'designation_fr' =>  $localizedDesignationRules,
            'designation_ar' =>  $localizedDesignationRules,
            'designation_en' =>  $localizedDesignationRules,
        ];
    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('field', [
            'acronym','designation_ar','designation_fr','designation_en'
        ]);
    }

    /**
     * Save the fielding information.
     */
    public function save($field)
    {
        try {
            // Validate request data
            $data = $this->validate();
            // Create fielding information record
            $field->update($data);
            return $this->response(true, message: __("forms.field.responses.update_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in field UpdateForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
