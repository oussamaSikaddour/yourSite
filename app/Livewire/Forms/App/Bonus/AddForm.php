<?php

namespace App\Livewire\Forms\App\Bonus;

use App\Models\Bonus;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait;

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
            'required', 'string', 'min:5', 'max:60',
            Rule::unique('bonuses')
                ->where(fn($query) => $query
                    ->whereNull('deleted_at')
                ),
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
        'amount','titled_ar','titled_fr','titled_en'
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
            Bonus::create($data);

            return $this->response(true, message: __("forms.bonus.responses.add_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Bonus AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
