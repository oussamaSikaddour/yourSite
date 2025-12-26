<?php

namespace App\Livewire\Forms\Core\Bank;

use App\Models\Bank;
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
    public $code = "";

    /**
     * Define validation rules.
     */
    public function rules()
    {

        $localizedDesignationRules = [
            'nullable', 'string', 'min:5', 'max:60',
            Rule::unique('banks')
                    ->whereNull('deleted_at')
        ];
        return [
            'acronym' => [
                'required',
                'string',
                 "max:10",
                Rule::unique('banks', 'acronym')
                    ->whereNull('deleted_at'),
            ],
            'code' => [
                'required',
                 'string',
                 'size:3', // Combines 'min:3' and 'max:3' since the size is fixed
                    Rule::unique('banks', 'code')->whereNull('deleted_at'),
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
        return $this->returnTranslatedResponseAttributes('bank', [
            'acronym','code','designation_ar','designation_fr','designation_en'
        ]);
    }

    /**
     * Save the banking information.
     */
    public function save()
    {
        try {
            // Validate request data
            $data = $this->validate();

            // Create banking information record
            Bank::create($data);

            return $this->response(true, message: __("forms.bank.responses.add_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Bank AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
