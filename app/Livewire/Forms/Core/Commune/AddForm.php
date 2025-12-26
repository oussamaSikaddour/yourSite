<?php

namespace App\Livewire\Forms\Core\Commune;

use App\Models\Commune;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait;

    public $code = "";
    public $designation_ar = "";
    public $designation_fr = "";
    public $designation_en = "";
    public $daira_id = "";

    /**
     * Define validation rules.
     */
    public function rules()
    {

        $localizedDesignationRules = [
            'nullable', 'string', 'min:5', 'max:60',
            Rule::unique('communes')
                    ->whereNull('deleted_at')
        ];
        return [
            'code' => [
                'required',
                'string',
                 "max:10",
                Rule::unique('communes', 'code')
                    ->whereNull('deleted_at'),
            ],
            'designation_fr' =>  $localizedDesignationRules,
            'designation_ar' =>  $localizedDesignationRules,
            'designation_en' =>  $localizedDesignationRules,
        'daira_id' => [
            'required',
            'integer',
            Rule::exists('dairates', 'id'),
        ],
        ];
    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('commune', [
            'code','designation_ar','designation_fr','designation_en','daira_id'
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
            Commune::create($data);

            return $this->response(true, message: __("forms.commune.responses.add_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Commune AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
