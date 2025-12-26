<?php

namespace App\Livewire\Forms\Core\SiteParameters;

use App\Traits\Core\Web\ResponseTrait;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LastStepForm extends Form
{
    use ResponseTrait;
    public $maintenance;
    public function rules()
    {
        return [
            'maintenance' => [
                'required',
                'boolean',
            ],
            // Add more attribute names as needed
        ];
    }

    public function validationAttributes()
    {
        return [
            'maintenance' => __('forms.site_parameters.steps.last.state'),
            // Add more attribute names as needed
        ];
    }



    public function save($generalSettings)
    {
        // Validate the form data
        try {
            $data = $this->validate();

            $generalSettings->update($data);
            return $this->response(true,message:__('forms.site_parameters.response.success'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
         }
        catch (\Exception $e) {

            return $this->response(false,errors:[$e->getMessage()]);
        }
    }
}
