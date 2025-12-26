<?php

namespace App\Livewire\Forms\Core\Visit;

use App\Models\Visit;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait;

    public $patient_id;
    public $doctor_id;
    public $report_fr;
    public $report_ar;
    public $report_en;

    /**
     * Validation rules.
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
     * Custom attribute translations.
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

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [
            'patient_id.required' => __('forms.patient_visit.errors.not_found.patient'),
            'doctor_id.required'  => __('forms.patient_visit.errors.not_found.doctor'),
        ];
    }

    /**
     * Save a new visit.
     */
    public function save(): array
    {
        try {
            $data = $this->validate();

            Visit::create($data);

            return $this->response(true, message: __('forms.patient_visit.responses.add_success'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Visit AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: [__('forms.common.errors.default')]);
        }
    }
}
