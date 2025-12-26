<?php

namespace App\Livewire\Forms\Core\Image;

use App\Models\Image;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait, ModelImageTrait;

    // Public properties bound to form fields
    public $display_name;
    public $use_case;
    #[Validate('required|file|mimes:jpeg,png,gif,ico,webp|max:10000')]
    public $real_image;
    public $imageable_type;
    public $imageable_id;

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'real_image' => 'required|file|mimes:jpeg,png,gif,ico,webp|max:10000',
            'display_name' => [
                'required', 'string', 'min:3', 'max:255',
                Rule::unique('images', 'display_name')->whereNull('deleted_at'),
            ],
            'use_case' => [
                'required', 'string', 'min:3', 'max:100',
            ],
            'imageable_id' => ['required', 'integer'],
            'imageable_type' => ['required', 'string'],
        ];
    }



        public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('image', [
            'real_image',
            'display_name',
            'use_case' ,
            'imageable_id' ,
            'imageable_type',
        ]);
    }
    /**
     * Save a new Image record and upload the file.
     */
    public function save(): array
    {
        try {
            $data = $this->validate();


                $this->uploadAndCreateImage(
                    $this->real_image,
                    $data['imageable_id'],
                    $data['imageable_type'],
                    $data['use_case'],
                    $data['display_name']
                );
          return $this->response(true, message: __("forms.image.responses.add_success"));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Image AddForm save(): ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
