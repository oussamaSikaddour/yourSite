<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Models\Wilaya;
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

class WilayatesTable extends Component
{
    use WithPagination, TableTrait, GeneralTrait, ResponseTrait, TextAndPdfTrait, WithFileUploads;

    #[Url()]
    public $designation = "";

    #[Url()]
    public $code = "";

    public $local = "fr";

    protected array $filterable = ['designation', 'code'];

    protected array $validationRules = [
        'designation' => ['nullable', 'string', 'max:255'],
        'code'        => ['nullable', 'string', 'max:10'],
    ];

    public function mount(): void
    {
        $this->local = app()->getLocale();
    }

    public function resetFilters(): void
    {
        $this->reset(['designation', 'code']);
        $this->resetPage();
    }

    #[Computed]
    public function wilayates()
    {
        $local = in_array($this->local, ['fr', 'en', 'ar']) ? $this->local : 'fr';

        $query = Wilaya::query();

        if (!empty($this->designation)) {
            $containsArabic = preg_match('/\p{Arabic}/u', $this->designation);
            $column = $containsArabic ? 'designation_ar' : "designation_{$local}";
            $query->where("wilayates.$column", 'like', "%{$this->designation}%");
        }

        if (!empty($this->code)) {
            $query->where('wilayates.code', 'like', "%{$this->code}%");
        }

        return $query
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[On("delete-wilaya")]
    public function deleteWilaya(Wilaya $wilaya): void
    {
        try {
            $wilaya->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting wilaya: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    public function openDeleteDialog($wilaya): void
    {
        $data = [
            "question"     => "dialogs.title.wilaya",
            "details"      => ["wilaya", $wilaya['code']],
            "actionEvent"  => [
                "event"      => "delete-wilaya",
                "parameters" => $wilaya
            ],
        ];

        $this->dispatch("open-dialog", $data);
    }

    public function updated(string $property): void
    {
        if ($property === "excelFile") {
            $errorsFileData = $this->whenExcelFileUploaded("wilayatesImport", __('tables.wilayates.excel.upload.success'));

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
    public function downloadWilayatesErrorsTextFile($errorsFileData)
    {
        return $this->streamFileDownload($errorsFileData['filePath'], $errorsFileData['fileName']);
    }

    public function generateEmptyWilayatesExcel()
    {
        return $this->generateEmptyExcelWithHeaders("wilayatesVide", [
            "Code",
            "Désignation (français)",
            "Désignation (arabe)",
            "Désignation (anglais)",
        ]);
    }

    public function generateWilayatesExcel()
    {
        return $this->generateExcel(fn() => $this->wilayates()->map(fn($wilaya) => [
            __("tables.wilayates.code")             => $wilaya->code,
            __("tables.wilayates.designation_fr")   => $wilaya->designation_fr,
            __("tables.wilayates.designation_ar")   => $wilaya->designation_ar,
            __("tables.wilayates.designation_en")   => $wilaya->designation_en,
            __("tables.wilayates.created_at")       => $wilaya->created_at->format('d-m-Y'),
        ])->toArray(), "wilayates");
    }

    public function render()
    {
        return view('livewire.core.super-admin.wilayates-table');
    }
}
