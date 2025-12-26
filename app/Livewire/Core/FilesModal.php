<?php

namespace App\Livewire\Core;

use App\Livewire\Forms\Core\File\AddForm;
use App\Livewire\Forms\Core\File\UpdateForm;
use App\Models\File;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\ModelFileTrait;
use App\Traits\Core\Common\TableTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FilesModal extends Component
{
    use TableTrait, GeneralTrait, WithFileUploads, ModelFileTrait;

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public ?File $file = null;

    public ?int $fileId = null;
    public ?int $fileableId = null;
    public string $fileableType = '';
    public ?int $selectedChoice = null;
    public string $form = 'addForm';
    public string $locale = 'fr';
    public string $temporaryFileName = '';

    /**
     * Computed property for active form (add or update)
     */
    #[Computed]
    public function formEntity()
    {
        return $this->fileId ? $this->updateForm : $this->addForm;
    }

    /**
     * Reset form fields and prepare for adding.
     */
    public function resetForm(): void
    {
        $this->form = 'addForm';
        $this->fileId = null;
        $this->selectedChoice = null;

        $this->temporaryFileName = '';
        $this->addForm->reset();
        $this->updateForm->reset();

        $this->addForm->fill([
            'fileable_id' => $this->fileableId,
            'fileable_type' => $this->fileableType,
        ]);
    }

    /**
     * Switch between add and update forms when a selection changes.
     */
    public function updatedSelectedChoice(): void
    {
        $this->fileId = $this->selectedChoice;
        $this->form = $this->fileId ? 'updateForm' : 'addForm';

        if ($this->fileId) {
            $this->setFileForm($this->fileId);
        }
    }

    /**
     * Computed: list files for this entity.
     */
    #[Computed]
    public function files()
    {
        return File::where('fileable_id', $this->fileableId)
            ->where('fileable_type', $this->fileableType)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();
    }

    /**
     * Mount component.
     */
    public function mount(): void
    {
        $this->locale = app()->getLocale();
        $this->resetForm();
    }

    /**
     * Set update form fields from File.
     */
    public function setFileForm(int $fileId): void
    {
        try {
            $file = File::where('fileable_id', $this->fileableId)
                ->where('fileable_type', $this->fileableType)
                ->findOrFail($fileId);

            $this->file = $file;
            $this->temporaryFileName = $file->display_name ?? $file->real_name;

            $this->updateForm->fill($this->file->only([
                'id', 'display_name', 'use_case', 'fileable_id', 'fileable_type',
            ]));
        } catch (\Exception $e) {
            Log::error('Error in setFileForm: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Handle add or update submission.
     */
    public function handleSubmit(): void
    {
        $this->dispatch('form-submitted');

        $response = $this->fileId
            ? $this->updateForm->save($this->file)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-files-table');

            if ($this->form === 'addForm') {
                $this->resetForm();
            }

            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    /**
     * Open delete confirmation dialog.
     */
    public function openDeleteFileDialog(array $file): void
    {
        $name = $file['display_name'] ?? '';

        $data = [
            'question' => 'dialogs.title.file',
            'details' => ['file', $name],
            'actionEvent' => [
                'event' => 'delete-file',
                'parameters' => $file,
            ],
        ];

        $this->dispatch('open-dialog', $data);
    }

    /**
     * Delete a file (with confirmation event).
     */
    #[On('delete-file')]
    public function deleteFileAction(File $file): void
    {
        try {
            $this->deleteFile($file);
               $this->temporaryFileName = '';
            $this->dispatch('update-files-table');
            $this->dispatch('open-toast', __('forms.common.deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('Error in deleteFile: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Watch for file upload changes and show temporary filename.
     */
    public function updated($property): void
    {
        if (in_array($property, ['addForm.real_file', 'updateForm.real_file'])) {
            $this->updateTemporaryFileName();
        }
    }

protected function updateTemporaryFileName(): void
{
    try {
        // Check if a file was uploaded
        if ($this->formEntity->real_file instanceof TemporaryUploadedFile) {
            $file = $this->formEntity->real_file;
            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension !== 'pdf') {
                $this->reset(['formEntity.real_file', 'temporaryFileName']);
                $this->dispatch('open-errors', __('forms.file.errors.not_pdf'));
                return;
            }
            $this->temporaryFileName = $file->getClientOriginalName();
        }
    } catch (\Exception $e) {
        $this->dispatch('open-errors', __('forms.common.errors.file.not_file'));
    }
}

    public function render()
    {
        $this->dispatch('init-tooltips');
        return view('livewire.core.files-modal');
    }
}
