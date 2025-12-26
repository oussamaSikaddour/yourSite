<?php

namespace App\Livewire\Forms\Core\Occupation;

use App\Models\Field;
use App\Models\Occupation;
use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Form;

class UpdateForm extends Form
{
    use ResponseTrait;

    public $id = '';
    public $person_id = '';
    public $field_id = '';
    public $field_specialty_id = '';
    public $field_grade_id = '';
    public $experience = ''; // << added back
    public $description_fr = '';
    public $description_ar = '';
    public $description_en = '';

    public function rules(): array
    {
        return [
            'person_id' => 'required|exists:persons,id',
            'field_id' => 'required|exists:fields,id',
            'field_specialty_id' => 'required|exists:field_specialties,id',
            'field_grade_id' => 'required|exists:field_grades,id',
            'experience' => ['required', 'integer', 'between:0,50'], // << reasonable range
            'description_fr' => ['nullable', 'string', 'min:4', 'max:255'],
            'description_ar' => ['nullable', 'string', 'min:4', 'max:255'],
            'description_en' => ['nullable', 'string', 'min:4', 'max:255'],
        ];
    }

    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('occupation', [
            'person_id',
            'field_id',
            'field_specialty_id',
            'field_grade_id',
            'experience', // << added
            'description_fr',
            'description_ar',
            'description_en'
        ]);
    }

    public function save(Occupation $occupation)
    {
        try {
            $data = $this->validate();






            $occupation->update($data);




            return $this->response(true, message: __("forms.occupation.responses.update_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Occupation UpdateForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
