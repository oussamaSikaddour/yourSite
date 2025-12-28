<?php

namespace App\Livewire\Forms\App\Bonus;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    use ResponseTrait;

    public $id;
    public $amount;
    public $titled_ar;
    public $titled_fr;
    public $titled_en;
    /**
     * Define validation rules.
     */
    public function rules(): array
    {
        $localizedTitleRules = [
            'nullable', 'string', 'min:5', 'max:60',
            Rule::unique('bonuses')
                    ->whereNull('deleted_at')
                    ->ignore($this->id)
        ];
        return [
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999.99'],
            'titled_ar' =>$localizedTitleRules,
            'titled_fr' =>$localizedTitleRules,
            'titled_en' =>$localizedTitleRules,
        ];
    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('bonus', [
        'amount','establishment_id','titled_ar','titled_fr','titled_en'
        ]);
    }

    /**
     * Save the banking information.
     */
    public function save($bonus)
    {
        try {


            // Validate request data
            $data = $this->validate();
            $bonus->update($data);
            return $this->response(true, message: __("forms.bonus.responses.update_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Bonus UpdateForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
