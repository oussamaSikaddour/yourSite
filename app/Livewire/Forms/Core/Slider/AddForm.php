<?php

namespace App\Livewire\Forms\Core\Slider;

use App\Models\Service;
use App\Models\Slider;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait;

    public $user_id;
    public $name;
    public $position = 1;
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
                Rule::unique('sliders')->whereNull('deleted_at'),
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
     * Attribute names for validation messages
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
     * Store the new slider and reorder its position
     */
    public function save()
    {
        try {
            $data = $this->validate();

            // Create the new slider
         Slider::create($data);
            return $this->response(
                true,
                message: __('forms.slider.responses.add_success'),
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Throwable $e) {
            Log::error('Slider AddForm save error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
