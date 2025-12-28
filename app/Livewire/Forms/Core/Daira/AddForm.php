<?php

namespace App\Livewire\Forms\Core\Daira;

use App\Models\Daira;
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
    public $wilaya_id = "";

    /**
     * Define validation rules.
     */
    public function rules()
    {

        $localizedDesignationRules = [
            'nullable', 'string', 'min:5', 'max:60',
            Rule::unique('dairates')
                    ->whereNull('deleted_at')
        ];
        return [
            'code' => [
                'required',
                'string',
                 "max:10",
                Rule::unique('dairates', 'code')
                    ->whereNull('deleted_at'),
            ],
            'designation_fr' =>  $localizedDesignationRules,
            'designation_ar' =>  $localizedDesignationRules,
            'designation_en' =>  $localizedDesignationRules,
        'wilaya_id' => [
            'required',
            'integer',
            Rule::exists('wilayates', 'id'),
        ],
        ];
    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('daira', [
            'code','designation_ar','designation_fr','designation_en','wilaya_id'
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
            Daira::create($data);

            return $this->response(true, message: __("forms.commune.responses.add_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Daira AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
