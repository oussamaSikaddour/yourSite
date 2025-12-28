<?php

namespace App\Livewire\Forms\Core\ExternalLink;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
  use ResponseTrait;

  public $id;
    public $name_fr = "";
    public $name_ar = "";
    public $name_en = "";
    public $menu_id="";
    public $url = "";

    /**
     * Define validation rules.
     */
    public function rules()
    {

        $localizedNameRules = [
            'required', 'string', 'min:5', 'max:255',
            Rule::unique('external_links')
                    ->whereNull('deleted_at')
                    ->ignore($this->id),
        ];

return [
        'name_ar' => $localizedNameRules,
        'name_fr' => $localizedNameRules,
        'name_en' => $localizedNameRules,
         'url' => [
                    'required',
                    'url',
                    Rule::unique('external_links','url')
                    ->whereNull('deleted_at')
                    ->ignore($this->id) ],
        'menu_id' => 'required|exists:menus,id',
            ];


    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('external_link', [
           'name_fr','name_ar','name_en','url','menu_id'
        ]);
    }

    /**
     * Save the banking information.
     */
    public function save($externalLink)
    {
        try {
            $data = $this->validate();
             $externalLink->update($data);
            return $this->response(true, message: __("forms.external_link.responses.add_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in External Link UpdateForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
