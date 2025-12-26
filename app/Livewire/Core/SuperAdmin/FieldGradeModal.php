<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Livewire\Forms\Core\FieldGrade\AddForm;
use App\Livewire\Forms\Core\FieldGrade\UpdateForm;
use App\Models\FieldGrade;
use App\Traits\Core\Api\ResponseTrait;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use App\Traits\Core\Common\TextAndPdfTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class FieldGradeModal extends Component
{
    use WithPagination, TableTrait, GeneralTrait, ResponseTrait, TextAndPdfTrait, WithFileUploads;

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public FieldGrade $fieldGrade;

    public $field;
    public $fieldGradeId;
    public $selectedChoice;
    public $form = "addForm";
    public $locale = "fr";
    public $fieldAcronym = "";

    public $designation="";
    public $acronym="";
    protected array $filterable = ['designation', 'acronym'];

    protected array $validationRules = [
        'designation' => ['nullable', 'string', 'max:255'],
        'acronym'     => ['nullable', 'string', 'max:255'],
    ];

    /** Mount component & initialize */
    public function mount()
    {

        $this->locale = app()->getLocale();
        $this->fieldAcronym = $this->field['acronym'] ?? '';
        $this->resetForm();
    }

    /** Reset form & prepare for adding new field grade */
    public function resetForm()
    {
        $this->form = "addForm";
        $this->selectedChoice = null;
        $this->addForm->reset();
        $this->updateForm->reset();

        $this->addForm->fill([
            'field_id' => $this->field['id'] ?? null,
        ]);
    }

    /** Reset filters & pagination */
    public function resetFilters()
    {
        $this->reset(['designation', 'acronym']);
        $this->resetPage();
    }

    /** Switch between add/update form when choice changes */
    public function updatedSelectedChoice()
    {
        $this->fieldGradeId = $this->selectedChoice;
        $this->form = $this->fieldGradeId ? 'updateForm' : 'addForm';

        if ($this->fieldGradeId) {
            $this->setFieldGradeForm($this->fieldGradeId);
        }
    }

    /** Load field grade into update form */
    public function setFieldGradeForm($fieldGradeId)
    {
        try {
            $this->fieldGrade = FieldGrade::where('field_id', $this->field['id'])
                                          ->findOrFail($fieldGradeId);

            $this->updateForm->fill([
                'id'             => $this->fieldGrade->id,
                'acronym'        => $this->fieldGrade->acronym,
                'designation_ar' => $this->fieldGrade->designation_ar,
                'designation_fr' => $this->fieldGrade->designation_fr,
                'designation_en' => $this->fieldGrade->designation_en,
                'field_id'=>$this->fieldGrade->field_id
            ]);
        } catch (\Exception $e) {
            Log::error('Error in setFieldGradeForm: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /** Computed: get filtered & paginated field grades */
    #[Computed()]
    public function fieldGrades()
    {
        $local = in_array($this->locale, ['fr', 'en']) ? $this->locale : 'fr';

        $query = FieldGrade::query()->where('field_id', $this->field['id']);

        if (!empty($this->designation)) {
            // Detect Arabic letters → search in Arabic column
            $containsArabic = preg_match('/\p{Arabic}/u', $this->designation);
            $column = $containsArabic ? 'designation_ar' : "designation_{$local}";
            $query->where($column, 'like', "%{$this->designation}%");
        }

        if (!empty($this->acronym)) {
            $query->where('acronym', 'like', "%{$this->acronym}%");
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)
                     ->paginate($this->perPage);
    }

    /** Handle form submit: add or update */
    public function handleSubmit()
    {
        $this->dispatch('form-submitted');

        $response = $this->fieldGradeId
            ? $this->updateForm->save($this->fieldGrade)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-field-grades-table');

            if ($this->form === 'addForm') {
                $this->resetForm();
                $this->addForm->fill([
               'field_id' => $this->field['id'] ?? null,
                 ]);
            }

            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    /** Confirm deletion */
    public function openDeleteDialog($fieldGrade)
    {
        $data = [
            'question'    => 'dialogs.title.field_grade',
            'details'     => ['field_grade', $fieldGrade['acronym'] ?? ''],
            'actionEvent' => [
                'event'      => 'delete-field-grade',
                'parameters' => $fieldGrade,
            ],
        ];

        $this->dispatch('open-dialog', $data);
    }

    /** Actually delete field grade */
    #[On('delete-field-grade')]
    public function deleteFieldGrade(FieldGrade $fieldGrade)
    {
        try {
            $fieldGrade->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteFieldGrade: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /** Handle file upload & validate */
    public function updated(string $property): void
    {
        if ($property === "excelFile") {
            $errorsFileData = $this->whenExcelFileUploaded(
                "fieldGradesImport",
                __('tables.field_grades.excel.upload.success'),
                [$this->field['id']]
            );

            if (is_array($errorsFileData)) {
                $this->dispatch('errors-file-data', errorsFileData: $errorsFileData);
            }
        }

        if (in_array($property, $this->filterable) || $property === 'perPage') {
            $this->resetPage();
        }

        if (array_key_exists($property, $this->validationRules)) {
            try {
                $this->validateOnly($property, $this->validationRules);
            } catch (ValidationException $e) {
                $this->dispatch('open-errors', $e->validator->errors()->all());
            }
        }
    }

    #[On('errors-file-data')]
    public function downloadFieldsErrorsTextFile($errorsFileData)
    {
        return $this->streamFileDownload($errorsFileData['filePath'], $errorsFileData['fileName']);
    }

    /** Generate empty Excel template */
    public function generateEmptyFieldGradesExcel()
    {
        return $this->generateEmptyExcelWithHeaders('EmptyField'. $this->field['acronym'].'Grades', [
            'Acronym',
            'Désignation (français)',
            'Désignation (arabe)',
            'Désignation (anglais)',
        ]);
    }

    /** Generate Excel export of field grades */
    public function generateFieldGradesExcel()
    {
        return $this->generateExcel(fn () => $this->fieldGrades()->map(fn ($grade) => [
            __("tables.field_grades.acronym")         => $grade->acronym,
            __("tables.field_grades.designation_fr")  => $grade->designation_fr,
            __("tables.field_grades.designation_ar")  => $grade->designation_ar,
            __("tables.field_grades.designation_en")  => $grade->designation_en,
            __("tables.field_grades.registration_date")      => $grade->created_at->format('d-m-Y'),
        ])->toArray(), 'field'. $this->field['acronym'].'Grades');
    }

    public function render()
    {
        $this->dispatch('init-table');
        $this->dispatch('init-tooltips');
        return view('livewire.core.super-admin.field-grade-modal');
    }
}
