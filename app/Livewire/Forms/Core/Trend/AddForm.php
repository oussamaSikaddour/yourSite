<?php

namespace App\Livewire\Forms\Core\Trend;

use App\Models\Trend;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait;

    public $title_fr;
    public $title_en;
    public $title_ar;
    public $content_fr;
    public $content_en;
    public $content_ar;
    public $start_at;
    public $end_at;
    public $user_id;

    /**
     * Get validation rules.
     */
    public function rules(): array
    {
        return [
            'title_fr'   => $this->titleRules(),
            'title_en'   => $this->titleRules(),
            'title_ar'   => $this->titleRules(),
            'content_fr' => $this->contentRules(),
            'content_en' => $this->contentRules(),
            'content_ar' => $this->contentRules(),
            'start_at'   => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'end_at'     => 'required|date|after_or_equal:' . $this->start_at,
             'user_id' => 'required|exists:users,id',
        ];
    }



    /**
     * Reusable title rules.
     */
    protected function titleRules(): array
    {
        return [
            'required',
            'string',
            'min:5',
            'max:60',
            Rule::unique('trends')->whereNull('deleted_at'),
        ];
    }

    /**
     * Reusable content rules.
     */
    protected function contentRules(): array
    {
        return [
            'required',
            'string',
            'min:100',
            'max:2000',
            Rule::unique('trends')->whereNull('deleted_at'),
        ];
    }

    /**
     * Localized attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('trend', [
            'title_fr', 'title_en', 'title_ar',
            'content_fr', 'content_en', 'content_ar',
            'start_at', 'end_at',
        ]);
    }


       public function messages(): array
    {
        return [
            'user_id' => __("forms.trend.errors.no_creator"),
        ];
    }
    /**
     * Save a new trend.
     */
    public function save()
    {

        try {
              $data = $this->validate();

            Trend::create($data);
            return $this->response(true, message: __('forms.trend.responses.add_success'));
       } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Slide AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
