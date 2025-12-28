<?php

namespace App\Livewire\Forms\Core\Visit;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
   use ResponseTrait;
    public $id;
    public $patient_id;
    public $doctor_id;
    public $report_fr;
    public $report_ar;
    public $report_en;



    /**
     * Define validation rules.
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:medical_files,id',
            'doctor_id'  => 'required|exists:users,id',
            'report_fr'  => 'required|string|min:50|max:2000',
            'report_ar'  => 'nullable|string|min:50|max:2000',
            'report_en'  => 'nullable|string|min:50|max:2000',
        ];
    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('patient_visit', [
        'patient_id',
        'doctor_id',
        'report_fr',
        'report_ar',
        'report_en',
        ]);
    }

        public function messages(): array
    {
        return [
            'patient_id.required'      => __("forms.visit.errors.not_found.patient"),
            'doctor.required' => __("forms.visit.errors.not_found.doctor"),
        ];
    }



        public function save($visit)
    {
        try {
            // Validate request data
            $data = $this->validate();

            // Create banking information record
          $visit->update($data);

            return $this->response(true, message: __("forms.visit.responses.update_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Visit UpdateForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }

}
