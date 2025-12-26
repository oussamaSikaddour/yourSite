<?php

namespace App\Livewire\App\Admin;

use App\Livewire\Forms\App\Service\AddForm;
use App\Livewire\Forms\App\Service\UpdateForm;
use App\Models\FieldSpecialty;
use App\Models\Image;
use App\Models\Person;
use App\Models\Service;
use App\Models\User;
use App\Traits\Core\Common\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ServiceModal extends Component
{
    use GeneralTrait,WithFileUploads;

    public AddForm $addForm;
    public UpdateForm $updateForm;
    public Service $service;

    public $id;
    public $form = 'addForm';

    public $local = 'fr';
    public ?string $temporaryImageUrl = null;

    public $serviceSpecialtiesOptions = [];
    public $headOfServiceOptions = [];





    public function updated($property): void
    {
        try {
            if (in_array($property, ['addForm.icon', 'updateForm.icon'])) {
                $this->temporaryImageUrl = $this->{$this->form}->icon?->temporaryUrl();
            }
        } catch (\Exception $e) {
            $this->dispatch('open-errors', [__('modals.common.img-type-err')]);
        }
    }


    /**
     * Mount the component.
     */
    public function mount(): void
    {



        $this->local = app()->getLocale();
        // Load select options
        $this->loadSelectOptions();

        // If editing, load existing data
        if ($this->id) {
            $this->form = 'updateForm';
            $this->loadServiceData();
        }
    }

    /**
     * Load dropdown/select options.
     */
    protected function loadSelectOptions(): void
    {
        // Service types from config


        // Specialties
        $this->serviceSpecialtiesOptions = $this->populateSelectorOption(
            $this->specialties(),
            'id',
            'designation_' . $this->local,
            __('selectors.default.field_specialties')
        );

        $local = in_array($this->local, ['fr', 'en']) ? $this->local : 'fr';

        $this->headOfServiceOptions = $this->populateSelectorOption(
            $this->staff(),
            'id',
            'head_service',
            __('selectors.default.head_service')
        );
    }

    /**
     * Load service data for update.
     */
    protected function loadServiceData(): void
    {
        try {
            $this->service = Service::findOrFail($this->id);

            $icon = Image::where([
                ['imageable_id', '=', $this->service->id],
                ['imageable_type', '=', Service::class],
                ['use_case', '=', 'icon']
            ])->first();

            $this->temporaryImageUrl = $icon?->url ?? $this->temporaryImageUrl;
            $this->updateForm->fill([
                'id'                  => $this->service->id,
                'name_ar'             => $this->service->name_ar,
                'name_fr'             => $this->service->name_fr,
                'name_en'             => $this->service->name_en,
                'head_of_service_id'  => $this->service->head_of_service_id,
                'tel'                 => $this->service->tel,
                'fax'                 => $this->service->fax,
                'specialty_id'        => $this->service->specialty_id,
                'email'               => $this->service->email,
                'beds_number'         => $this->service->beds_number,
                'specialists_number'  => $this->service->specialists_number,
                'physicians_number'   => $this->service->physicians_number,
                'paramedics_number'   => $this->service->paramedics_number,
                 'introduction_fr'     =>$this->service->introduction_fr,
                 'introduction_ar'     =>$this->service->introduction_en,
                 'introduction_en'     =>$this->service->introduction_en,

            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Error loading service: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    /**
     * Computed: load specialties for the selector.
     */
    #[Computed]
    public function specialties()
    {
        return FieldSpecialty::query()
            ->whereHas(
                'field',
                fn($q) =>
                $q->where('acronym', 'F_MED')
            )
            ->get(['id', 'designation_' . $this->local]);
    }

    /**
     * Computed: load staff for head of service selector.
     */
    /**
     * Computed: load staff for head of service selector.
     */



#[Computed]
public function staff()
{
    $nameColumn      = $this->local === 'ar' ? 'last_name_ar'  : 'last_name_fr';
    $firstNameColumn = $this->local === 'ar' ? 'first_name_ar' : 'first_name_fr';

    return Person::query()
        ->select('id')
        ->selectRaw(
            "CONCAT($nameColumn, ' ', $firstNameColumn) AS head_service"
        )
        ->get(['id', 'head_service']);
}




    /**
     * Handle form submission.
     */
    public function handleSubmit(): void
    {
        $this->dispatch('form-submitted');

        $response = $this->id
            ? $this->updateForm->save($this->service)
            : $this->addForm->save();

        if ($response['status']) {
            $this->dispatch('update-services-table');
            $this->dispatch('open-toast', $response['message']);


            if ($this->form === 'addForm') {
                $this->addForm->reset();
            }
        } else {
            $this->dispatch('open-errors', $response['errors']);
        }
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.app.admin.service-modal');
    }
}
