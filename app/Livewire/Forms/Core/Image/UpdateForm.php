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

class UpdateForm extends Form
{
    use ResponseTrait, ModelImageTrait;

    public $id;
    public $display_name;
    public $use_case;
    public $real_image;
    public $imageable_type;
    public $imageable_id;

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'display_name' => [
                'required', 'string', 'min:3', 'max:255',
                Rule::unique('images', 'display_name')
                    ->ignore($this->id)
                    ->whereNull('deleted_at'),
            ],
            'use_case' => [
                'required', 'string', 'min:3', 'max:100',
            ],
            'real_image' => 'nullable|file|mimes:jpeg,png,gif,ico,webp|max:10000',
            'imageable_id' => ['required', 'integer'],
            'imageable_type' => ['required', 'string'],
        ];
    }

    /**
     * Attribute translation mapping.
     */
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
     * Update an existing image record.
     */
    public function save(?Image $image = null): array
    {
        try {
            $data = $this->validate();

            if (!$image || !$image->exists) {
                return $this->response(false, errors: __('forms.common.errors.not_found'));
            }


                // Update text fields
                $image->update([
                    'display_name' => $data['display_name'],
                    'use_case' => $data['use_case'],
                ]);

                // If a new image file is uploaded, replace the old one
                if ($this->real_image) {
                    $this->deleteImage($image); // from ModelImageTrait

                    $newImage = $this->uploadAndUpdateImage(
                        $this->real_image,
                        $data['imageable_id'],
                        $data['imageable_type'],
                        $data['use_case'],
                        $data['display_name'],
                    );

                    // Sync the new file data with the existing record
                    $image->update([
                        'real_name' => $newImage->real_name,
                        'path' => $newImage->path,
                        'url' => $newImage->url,
                        'size' => $newImage->size,
                        'width' => $newImage->width,
                        'height' => $newImage->height,
                    ]);
                }

                return $this->response(
                    true,
                    message: __('forms.image.responses.update_success')
                );

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in Image UpdateForm save(): ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
