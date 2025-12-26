<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Models\Daira;
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

class DairatesTable extends Component
{
    use WithPagination, TableTrait, GeneralTrait, ResponseTrait, TextAndPdfTrait, WithFileUploads;

    #[Url()]
    public $designation = "";
    #[Url()]
    public $code = "";
    public $wilayaCode;
    public $local = "fr";
    public $wilayaId;

    protected array $filterable = ['designation', 'code'];

    protected array $validationRules = [
        'designation' => ['nullable', 'string', 'max:255'],
        'code'        => ['nullable', 'string', 'max:255'],
    ];

    public function mount()
    {

        $this->local = app()->getLocale();
    }

    public function resetFilters()
    {
        $this->reset(['designation', 'code']);
        $this->resetPage();
    }

#[Computed()]
public function dairates()
{
    $local = in_array($this->local, ['fr', 'en']) ? $this->local : 'fr';

    $query = Daira::query();

    if (!empty($this->wilayaId)) {
        $query->where('wilaya_id',$this->wilayaId);
    }
    if (!empty($this->designation)) {
        $containsArabic = preg_match('/\p{Arabic}/u', $this->designation);
        $column = $containsArabic ? 'designation_ar' : "designation_{$local}";
        $query->where($column, 'like', "%{$this->designation}%");
        // or $query->where("dairates.$column", ...) if you prefer explicit
    }

    if (!empty($this->code)) {
        $query->where('code', 'like', "%{$this->code}%");
        // or $query->where('dairates.code', ...) if explicit
    }

    return $query
        ->orderBy($this->sortBy, $this->sortDirection)
        ->paginate($this->perPage);
}


    #[On('delete-daira')]
    public function deleteDaira(Daira $daira)
    {
        try {
            $daira->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting daira: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    public function openDeleteDialog($daira)
    {
        $data = [
            "question" => "dialogs.title.daira",
            "details"  => ["daira", $daira['code'] ?? ''],
            "actionEvent" => [
                "event"      => "delete-daira",
                "parameters" => $daira
            ]
        ];

        $this->dispatch("open-dialog", $data);
    }

    public function updated(string $property): void
    {
        if ($property === "excelFile") {
            $errorsFileData = $this->whenExcelFileUploaded("dairatesImport", __('tables.dairates.excel.upload.success'),[$this->wilayaId]);

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
    public function downloadDairatesErrorsTextFile($errorsFileData)
    {
        return $this->streamFileDownload($errorsFileData['filePath'], $errorsFileData['fileName']);
    }

    public function generateEmptyDairatesExcel()
    {
        return $this->generateEmptyExcelWithHeaders("dairates", [
            "Code",
            "Désignation (français)",
            "Désignation (arabe)",
            "Désignation (anglais)",
        ]);
    }

    public function generateDairatesExcel()
    {
        return $this->generateExcel(fn () => $this->dairates()->map(fn ($daira) => [
            __("tables.dairates.code")            => $daira->code,
            __("tables.dairates.designation_fr")  => $daira->designation_fr,
            __("tables.dairates.designation_ar")  => $daira->designation_ar,
            __("tables.dairates.designation_en")  => $daira->designation_en,
            __("tables.dairates.created_at")      => $daira->created_at->format('d-m-Y'),
        ])->toArray(), "dairates");
    }

    public function render()
    {
        return view('livewire.core.super-admin.dairates-table');
    }
}
