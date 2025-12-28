<?php

namespace App\Livewire\Forms\Core\Menu;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
  use ResponseTrait;

    public $id;
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
                    ->ignore($this->id)
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
    public function save($menu)
    {
        try {
            $data = $this->validate();
           $menu->update($data);
            return $this->response(true, message: __("forms.menu.responses.update_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Menu UpdateForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
