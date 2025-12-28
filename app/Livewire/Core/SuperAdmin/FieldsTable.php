<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Models\Field;
use App\Traits\Core\Api\ResponseTrait;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use App\Traits\Core\Common\TextAndPdfTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class FieldsTable extends Component
{

    use WithPagination, TableTrait, GeneralTrait, ResponseTrait, TextAndPdfTrait,WithFileUploads;

    #[Url()]
    public $designation = "";
    #[Url()]
    public $acronym = "";
    public $local = "fr";
    protected array $filterable = ['designation', 'acronym',];

    protected array $validationRules = [
        'designation' => ['nullable', 'string', 'max:255'],
        'acronym' => ['nullable', 'string', 'max:255'],
    ];

    public function mount()
    {
        $this->local = app()->getLocale();


    }

    public function resetFilters()
    {
        $this->reset(['designation', 'acronym',]);
        $this->resetPage();
    }

#[Computed()]
public function fields()
{
    // Validate local
    $local = in_array($this->local, ['fr', 'en']) ? $this->local : 'fr';

    $query = Field::query();

    if (!empty($this->designation)) {
        // Detect if designation contains Arabic letters
        $containsArabic = preg_match('/\p{Arabic}/u', $this->designation);

        $column = $containsArabic ? 'designation_ar' : "designation_{$local}";

        $query->where("fields.$column", 'like', "%{$this->designation}%");
    }

    if (!empty($this->acronym)) {
        $query->where('fields.acronym', 'like', "%{$this->acronym}%");
    }

    return $query
        ->orderBy($this->sortBy, $this->sortDirection)
        ->paginate($this->perPage);
}






    #[On("delete-field")]
    public function deleteField(field $field)
    {
        try {
            $field->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting field: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }


    public function openDeleteDialog($field)
    {
        $data = [
            "question" => "dialogs.title.field",
            "details" => ["field", $field['acronym']],
            "actionEvent" => [
                "event" => "delete-field",
                "parameters" => $field
            ]
        ];

        $this->dispatch("open-dialog", $data);
    }





    public function updated(string $property): void
    {

         if($property ==="excelFile"){
            $errorsFileData= $this->whenExcelFileUploaded("fieldsImport",__('tables.fields.excel.upload.success') );

            if(is_array($errorsFileData) ){
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

    public function generateEmptyFieldsExcel()
    {
        return $this->generateEmptyExcelWithHeaders("fields",
        [
            "Acronym",
            "Désignation (français)",
            "Désignation (arabe)",
            "Désignation (anglais)",
        ]
        );
    }

        public function generateFieldsExcel()
    {

        return $this->generateExcel(fn() => $this->fields()->map(fn($field) => [
            __("tables.fields.acronym") => $field->acronym,
            __("tables.fields.designation_fr") => $field->designation_fr,
            __("tables.fields.designation_ar") => $field->designation_ar,
            __("tables.fields.designation_en") => $field->designation_en,
            __("tables.fields.created_at") => $field->created_at->format('d-m-Y'),

        ])->toArray(), "fields");
    }

    public function render()
    {
        return view('livewire.core.super-admin.fields-table');
    }
}
