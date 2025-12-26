<?php

namespace App\Livewire\Forms\Core\File;

use App\Models\File;
use App\Traits\Core\Common\ModelFileTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait, ModelFileTrait;

    // Public properties bound to form fields
    public $display_name;
    public $use_case;
    #[Validate('required|file|mimes:pdf|max:10240')] // only PDF, max 10MB
    public $real_file;
    public $fileable_type;
    public $fileable_id;

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'real_file' => 'required|file|mimes:pdf|max:10240',
            'display_name' => [
                'required', 'string', 'min:3', 'max:255',
                Rule::unique('files', 'display_name')->whereNull('deleted_at'),
            ],
            'use_case' => ['required', 'string', 'min:3', 'max:100'],
            'fileable_id' => ['required', 'integer'],
            'fileable_type' => ['required', 'string'],
        ];
    }

    /**
     * Attribute translation mapping.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('file', [
            'real_file',
            'display_name',
            'use_case',
            'fileable_id',
            'fileable_type',
        ]);
    }

    /**
     * Save a new File record and upload the file.
     */
    public function save(): array
    {
        try {
            $data = $this->validate();

            $this->uploadAndCreateFile(
                $this->real_file,
                $data['fileable_id'],
                $data['fileable_type'],
                $data['use_case'],
                $data['display_name']
            );

            return $this->response(true, message: __('forms.file.responses.add_success'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in File AddForm save(): ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
