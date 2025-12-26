<?php

namespace App\Livewire\Forms\Core\File;

use App\Models\File;
use App\Traits\Core\Common\ModelFileTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateForm extends Form
{
    use ResponseTrait, ModelFileTrait;

    public $id;
    public $display_name;
    public $use_case;
    public $real_file;
    public $fileable_type;
    public $fileable_id;

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'display_name' => [
                'required', 'string', 'min:3', 'max:255',
                Rule::unique('files', 'display_name')
                    ->ignore($this->id)
                    ->whereNull('deleted_at'),
            ],
            'use_case' => ['required', 'string', 'min:3', 'max:100'],
            'real_file' => 'nullable|file|mimes:pdf|max:10240', // only PDF, max 10MB
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
     * Update an existing File record.
     */
    public function save(?File $file = null): array
    {
        try {
            $data = $this->validate();

            if (!$file || !$file->exists) {
                return $this->response(false, errors: __('forms.common.errors.not_found'));
            }

            // Update text fields
            $file->update([
                'display_name' => $data['display_name'],
                'use_case' => $data['use_case'],
            ]);

            // If a new PDF file is uploaded, replace the old one
            if ($this->real_file) {
                $this->deleteFile($file);

                $newFile = $this->uploadAndUpdateFile(
                    $this->real_file,
                    $data['fileable_id'],
                    $data['fileable_type'],
                    $data['use_case'],
                    $data['display_name'],
                );

                // Sync new data with the same DB record
                $file->update([
                    'real_name' => $newFile->real_name,
                    'path' => $newFile->path,
                    'url' => $newFile->url,
                    'size' => $newFile->size,
                ]);
            }

            return $this->response(true, message: __('forms.file.responses.update_success'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in File UpdateForm save(): ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
