<?php

namespace App\Livewire\Forms\Core\Slider;

use App\Models\Service;
use App\Models\Slider;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateForm extends Form
{
    use ResponseTrait;

    public $id;
    public $user_id;
    public $name;
    public $position;
    public $sliderable_type;
    public $sliderable_id;

    /**
     * Validation rules
     */
    public function rules(): array
    {
        $commonRules = [
            'name' => [
                'required',
                'string',
                'min:5',
                'max:255',
                Rule::unique('sliders')
                    ->whereNull('deleted_at')
                    ->ignore($this->id),
            ],
            'user_id' => 'required|exists:users,id',
            'sliderable_type' => 'required|string|min:5',
            'position' => [
                'nullable',
                'integer',
                'min:1',
                Rule::unique('sliders')
                    ->where('sliderable_id',   $this->sliderable_id)
                    ->where('sliderable_type', $this->sliderable_type)
                    ->ignore($this->id),   // allow the current record to keep its own value
            ],
        ];

        return match ($this->sliderable_type) {
            Service::class => array_merge($commonRules, [
                'sliderable_id' => 'required|exists:services,id',
            ]),
            default => array_merge($commonRules, [
                'sliderable_id' => 'required|integer'
            ]),
        };
    }

    /**
     * Define attribute names for validation messages
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('slider', [
            'name',
            'user_id',
            'position',
            'sliderable_id',
            'sliderable_type',
        ]);
    }

    /**
     * Update the slider and handle optional position reorder
     */
    public function save(Slider $slider)
    {
        try {
            $data = $this->validate();





            $slider->update($data);



            return $this->response(
                true,
                message: __('forms.slider.responses.update_success'),
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Throwable $e) {
            Log::error('Slider UpdateForm error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'slider_id' => $this->id,
            ]);

            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
