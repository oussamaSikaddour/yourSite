<?php

namespace App\Livewire\Forms\Core\Menu;

use App\Models\Menu;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddForm extends Form
{
  use ResponseTrait;

    public $title_fr = "";
    public $title_ar = "";
    public $title_en = "";

    /**
     * Define validation rules.
     */
    public function rules()
    {

        $localizedTitleRules = [
            'required', 'string', 'min:5', 'max:255',
            Rule::unique('menus')
                    ->whereNull('deleted_at')
        ];

return [
        'title_fr' => $localizedTitleRules,
        'title_en' => $localizedTitleRules,
        'title_ar' => $localizedTitleRules,
            ];


    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('menu', [
           'title_fr','title_ar','title_en'
        ]);
    }

    /**
     * Save the banking information.
     */
    public function save()
    {
        try {
            $data = $this->validate();
           Menu::create($data);
            return $this->response(true, message: __("forms.menu.responses.add_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Menu AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
