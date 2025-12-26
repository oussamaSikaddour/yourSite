<?php

namespace App\Livewire\Core\SuperAdmin;

use App\Livewire\Forms\Core\Commune\AddForm;
use App\Livewire\Forms\Core\Commune\UpdateForm;
use App\Models\Commune;

use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use App\Traits\Core\Common\TextAndPdfTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class CommuneModal extends Component
{
    use WithPagination, TableTrait, GeneralTrait, ResponseTrait, TextAndPdfTrait, WithFileUploads;

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public Commune $commune;

    public $daira;
    public $communeId;
    public $selectedChoice;
    public $form = "addForm";
    public $locale = "fr";
    public $dairaCode = "";

    public $designation = "";
    public $code = "";

    protected array $filterable = ['designation', 'code'];

    protected array $validationRules = [
        'designation' => ['nullable', 'string', 'max:255'],
        'code'        => ['nullable', 'string', 'max:10'],
    ];

    public function mount()
    {
        $this->locale = app()->getLocale();
        $this->dairaCode = $this->daira['code'] ?? '';
        $this->resetForm();
    }

    /** Reset form for adding */
    public function resetForm()
    {
        $this->form = "addForm";
        $this->selectedChoice = null;
        $this->addForm->reset();
        $this->updateForm->reset();

        $this->addForm->fill([
            'daira_id' => $this->daira['id'] ?? null,
        ]);
    }

    /** Reset filters & pagination */
    public function resetFilters()
    {
        $this->reset(['designation', 'code']);
        $this->resetPage();
    }

    /** Switch to update mode if choice is selected */
    public function updatedSelectedChoice()
    {
        $this->communeId = $this->selectedChoice;
        $this->form = $this->communeId ? 'updateForm' : 'addForm';

        if ($this->communeId) {
            $this->setCommuneForm($this->communeId);
        }
    }

    /** Load commune into update form */
    protected function setCommuneForm($communeId)
    {
        try {
            $this->commune = Commune::where('daira_id', $this->daira['id'])
                ->findOrFail($communeId);

            $this->updateForm->fill([
                'id'             => $this->commune->id,
                'code'           => $this->commune->code,
                'designation_ar' => $this->commune->designation_ar,
                'designation_fr' => $this->commune->designation_fr,
                'designation_en' => $this->commune->designation_en,
                'daira_id'       => $this->commune->daira_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in setCommuneForm: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /** Computed: filtered & paginated communes */
    #[Computed]
    public function communes()
    {
        $local = in_array($this->locale, ['fr', 'en']) ? $this->locale : 'fr';

        $query = Commune::query()->where('daira_id', $this->daira['id']);

        if (!empty($this->designation)) {
            $containsArabic = preg_match('/\p{Arabic}/u', $this->designation);
            $column = $containsArabic ? 'designation_ar' : "designation_{$local}";
            $query->where($column, 'like', "%{$this->designation}%");
        }

        if (!empty($this->code)) {
            $query->where('code', 'like', "%{$this->code}%");
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    /** Submit form: add or update */
    public function handleSubmit()
    {
        $this->dispatch('form-submitted');

        $response = $this->communeId
            ? $this->updateForm->save($this->commune)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-communes-table');

            if ($this->form === 'addForm') {
                $this->resetForm();
            }

            $this->dispatch('open-toast', $response['message']);
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    /** Open confirm delete dialog */
    public function openDeleteDialog($commune)
    {
        $data = [
            'question'    => 'dialogs.title.commune',
            'details'     => ['commune', $commune['code'] ?? ''],
            'actionEvent' => [
                'event'      => 'delete-commune',
                'parameters' => $commune,
            ],
        ];

        $this->dispatch('open-dialog', $data);
    }

    /** Delete commune */
    #[On('delete-commune')]
    public function deleteCommune(Commune $commune)
    {
        try {
            $commune->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteCommune: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /** File upload, validation, filters */
    public function updated(string $property): void
    {
        if ($property === "excelFile") {
            $errorsFileData = $this->whenExcelFileUploaded(
                "communesImport",
                __('tables.communes.excel.upload.success'),
                [$this->daira['id']]
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
    public function downloadCommunesErrorsTextFile($errorsFileData)
    {
        return $this->streamFileDownload($errorsFileData['filePath'], $errorsFileData['fileName']);
    }

    /** Generate empty Excel template */
    public function generateEmptyCommunesExcel()
    {
        return $this->generateEmptyExcelWithHeaders('emptyDaira' . $this->daira['code'] . 'Communes', [
            'Code',
            'Désignation (français)',
            'Désignation (arabe)',
            'Désignation (anglais)',
        ]);
    }

    /** Generate Excel export */
    public function generateCommunesExcel()
    {
        return $this->generateExcel(fn () => $this->communes()->map(fn ($commune) => [
            __("tables.communes.code")             => $commune->code,
            __("tables.communes.designation_fr")   => $commune->designation_fr,
            __("tables.communes.designation_ar")   => $commune->designation_ar,
            __("tables.communes.designation_en")   => $commune->designation_en,
            __("tables.communes.registration_date") => $commune->created_at->format('d-m-Y'),
        ])->toArray(), 'daira' . $this->daira['code'] . 'Communes');
    }

    public function render()
    {
        $this->dispatch('init-table');
        $this->dispatch('init-tooltip');
        return view('livewire.core.super-admin.commune-modal');
    }
}
