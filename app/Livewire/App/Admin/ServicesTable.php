<?php

namespace App\Livewire\App\Admin;

use App\Models\FieldSpecialty;
use App\Models\Service;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use App\Traits\Core\Common\TextAndPdfTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class ServicesTable extends Component
{
    use WithPagination,
        TableTrait,
        GeneralTrait,
        WithFileUploads,
        ResponseTrait,
        TextAndPdfTrait;

    /* ---------- URL-bound filters ---------- */
    #[Url]
    public string $name          = '';
    #[Url]
    public string $type          = '';
    #[Url]
    public string $specialtyId     = '';
    #[Url]
    public string $headOfService = '';

    public string  $local = 'fr';
    public array   $serviceTypesOptions     = [];
    public array   $serviceSpecialtiesOptions = [];

    protected array $filterable      = ['name', 'type', 'headOfService', 'specialty'];
    protected array $validationRules = [
        'name'          => ['nullable', 'string', 'max:255'],
        'headOfService' => ['nullable', 'string', 'max:255'],
        'type'          => ['nullable', 'in:administration,health'],
    ];


        #[Computed]
public function specialties()
{
    return FieldSpecialty::query()
        ->whereHas('field', fn ($q) =>
            $q->where('acronym', 'F_MED')
        )
        ->get(['id', 'designation_' . $this->local]);
}

    /* ---------- Lifecycle ---------- */
    public function mount(): void
    {

        $this->local = app()->getLocale();

        $this->serviceTypesOptions     = config('constants.SERVICE_TYPE.'.$this->local) ?? [];
        $this->serviceSpecialtiesOptions =  $this->populateSelectorOption($this->specialties(),  'id','designation_'.$this->local, __('selectors.default.field_specialties'));
    }

    public function resetFilters(): void
    {
        $this->reset(
            'name',
            'type',
            'headOfService',
            'specialty'
        );
        $this->resetPage();
    }


#[Computed()]
public function services()
{
    $local = in_array($this->local, ['fr', 'ar', 'en']) ? $this->local : 'fr';
    $nameColumn = $local === 'ar' ? 'last_name_ar' : 'last_name_fr';
    $firstNameColumn = $local === 'ar' ? 'first_name_ar' : 'first_name_fr';

    $headServiceColumn = DB::raw("CONCAT(persons.$nameColumn, ' ', persons.$firstNameColumn) AS head_service");
    $specialtyColumn = DB::raw("field_specialties.designation_$local AS specialty");

    return Service::query()
        ->select([
            'services.*',
            $headServiceColumn,
            $specialtyColumn,
        ])
        ->leftJoin('persons', 'services.head_of_service_id', '=', 'persons.id')
        ->leftJoin('field_specialties', 'services.specialty_id', '=', 'field_specialties.id')
        ->when(filled($this->name), fn($q) =>
            $q->where("services.name_$local", 'like', "%{$this->name}%")
        )
        ->when(filled($this->type), fn($q) => $q->where('services.type', $this->type))
        ->when(filled($this->headOfService), fn($q) =>
            $q->where(DB::raw("CONCAT(persons.$nameColumn, ' ', persons.$firstNameColumn)"), 'like', "%{$this->headOfService}%")
        )
        ->when(filled($this->specialtyId), fn($q) => $q->where('services.specialty_id', $this->specialtyId))
        ->orderBy($this->sortBy, $this->sortDirection)
        ->paginate($this->perPage);
}




    /* ---------- CRUD listeners ---------- */
    #[On('delete-service')]
    public function deleteService(Service $service): void
    {
        try {
            $service->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting service: '.$e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    public function openDeleteDialog(array $service): void
    {
        $this->dispatch('open-dialog', [
            'question'  => __('dialogs.title.service'),
            'details'   => ['service', $service['name_'.$this->local]],
            'actionEvent' => [
                'event'      => 'delete-service',
                'parameters' => $service['id'],
            ],
        ]);
    }

    /* ---------- Hooks ---------- */
    public function updated(string $property): void
    {
        if ($property === 'excelFile') {
            $errors = $this->whenExcelFileUploaded('App\servicesImport', __('tables.services.excel.upload.success'));
            if (is_array($errors)) {
                $this->dispatch('errors-file-data', errorsFileData: $errors);
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

    /* ---------- Excel exports ---------- */
    public function generateEmptyServicesExcel()
    {
        return $this->generateEmptyExcelWithHeaders('services_vide', [
            'Nom (français)',
            'Nom (arabe)',
            'Nom (anglais)',
            'Chef de service',
            'Spécialité',
            'Téléphone',
            'Fax',
            'email',
            'nombre de lits',
            'nombre de spécialistes',
            'nombre de médecins',
            'nombre de paramédicaux'

        ]);
    }

    public function generateServicesExcel()
    {
        return $this->generateExcel(
            fn () => $this->services()->map(fn ($service) => [
                __('tables.services.name_fr')    => $service->name_fr,
                __('tables.services.name_ar')    => $service->name_ar,
                __('tables.services.name_en')    => $service->name_en,
                __('tables.services.specialty')  => $this->serviceSpecialtiesOptions[$service->specialty] ?? $service->specialty,
                __('tables.services.tel')        => $service->tel,
                __('tables.services.fax')        => $service->fax,
                __('tables.services.email')       =>$service->email,
                __('tables.services.beds_number') =>$service->beds_number,
                __('tables.services.specialists_number') =>$service->specialists_number,
                __('tables.services.physicians_number') =>$service->physicians_number,
                __('tables.services.paramedics_number') =>$service->paramedics_number,
                __('tables.services.created_at') => $service->created_at->format('d/m/Y'),
            ])->toArray(),
            'services'
        );
    }

    /* ---------- Errors file download ---------- */
    #[On('errors-file-data')]
    public function downloadServicesErrorsTextFile(array $errorsFileData)
    {
        return $this->streamFileDownload($errorsFileData['filePath'], $errorsFileData['fileName']);
    }

    /* ---------- Render ---------- */
    public function render()
    {
        return view('livewire.app.admin.services-table');
    }
}
