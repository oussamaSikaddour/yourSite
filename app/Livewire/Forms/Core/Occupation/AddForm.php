<?php

namespace App\Livewire\Forms\Core\Occupation;

use App\Models\Field;
use App\Models\Occupation;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait;

    public $person_id = '';
    public $field_id = '';
    public $field_specialty_id = '';
    public $field_grade_id = '';
    public $description_fr = '';
    public $description_ar = '';
    public $description_en = '';
    public $experience ="";

    public function rules()
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

    public function validationAttributes()
    {
        return $this->returnTranslatedResponseAttributes('occupation', [
            'person_id', 'field_id', 'field_specialty_id', 'field_grade_id',
            'description_fr', 'description_ar', 'description_en'
        ]);
    }

public function save()
{
    try {
        $data = $this->validate();

        $person = Person::findOrFail($data['person_id']);

        // Create occupation first
        Occupation::create($data);


        return $this->response(true, message: __("forms.occupation.responses.add_success"));
    } catch (\Illuminate\Validation\ValidationException $e) {
        return $this->response(false, errors: $e->validator->errors()->all());
    } catch (\Exception $e) {
        Log::error('Error in Occupation AddForm save method: ' . $e->getMessage());
        return $this->response(false, errors: __('forms.common.errors.default'));
    }
}


}
