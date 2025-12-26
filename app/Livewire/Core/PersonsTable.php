<?php

namespace App\Livewire\Core;

use App\Models\Image;
use App\Models\Person;
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

class PersonsTable extends Component
{
    use WithPagination, TableTrait, WithFileUploads, TextAndPdfTrait, ResponseTrait;

    #[Url]
    public string $fullName = '';

    #[Url]
    public ?string $email = null;

    #[Url]
    public ?string $employeeNumber = null;

    public ?string $isPaid = null;

    public string $local = 'fr';

    protected array $filterable = ['fullName', 'email', 'employeeNumber'];

    protected array $validationRules = [
        'fullName' => ['nullable', 'string', 'max:255'],
        'email' => ['nullable', 'string', 'email', 'max:255'],
        'employeeNumber' => ['nullable', 'string', 'max:255'],
    ];

    public function mount(): void
    {
        $this->local = app()->getLocale();
    }

    public function resetFilters(): void
    {
        $this->reset(['fullName', 'email', 'employeeNumber']);
        $this->resetPage();
    }

    #[Computed]
    public function persons()
    {
        $local = in_array($this->local, ['fr', 'en']) ? $this->local : 'fr';

            $isArabic = $this->fullName && preg_match('/\p{Arabic}/u', $this->fullName);

        /** Choose the correct columns */
        $lastNameColumn  = $isArabic ? 'last_name_ar'  : "last_name_{$local}";
        $firstNameColumn = $isArabic ? 'first_name_ar' : "first_name_{$local}";
               $fullNameConcat = DB::raw("CONCAT($lastNameColumn, ' ', $firstNameColumn)");
        return Person::query()
            ->with('user')
            ->leftJoin('users', 'persons.id', '=', 'users.person_id')
            ->when(!empty($this->fullName), function ($query) use ($fullNameConcat) {

                $query->where($fullNameConcat, 'like', "%{$this->fullName}%");
            })
            ->when(
                !empty($this->email),
                fn($query) =>
                $query->where('users.email', 'like', "%{$this->email}%")
            )
            ->when(
                !empty($this->employeeNumber),
                fn($query) =>
                $query->where('persons.employee_number', 'like', "%{$this->employeeNumber}%")
            )
            ->select([
                'persons.*',
                'users.email as user_email',
            ])
            ->orderBy($this->sortBy ?? 'persons.created_at', $this->sortDirection ?? 'desc')
            ->paginate($this->perPage ?? 10);
    }


    public function updated(string $property): void
    {
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

        if ($property === 'excelFile') {
            $errorsFileData = $this->whenExcelFileUploaded("Core\PersonsImport", __('tables.persons.excel.upload.success'));


            if (is_array($errorsFileData)) {
                $this->dispatch('errors-file-data', errorsFileData: $errorsFileData);
            }
        }
    }

    public function openDeletePersonDialog($person): void
    {



        $data = [
            "question" => "dialogs.title.person",
            "details" => ["person", $person['full_name']],
            "actionEvent" => [
                "event" => "delete-person",
                "parameters" => $person
            ],
        ];

        $this->dispatch("open-dialog", $data);
    }

    #[On("delete-person")]
    public function deletePerson(Person $person): void
    {
        try {
            $images = Image::where([
                ['imageable_id', $person->id],
                ['imageable_type', Person::class],
            ])->get();

            if ($images->isNotEmpty()) {
                $this->deleteImages($images);
            }

            $person->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting person: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }


    #[On('errors-file-data')]
    public function downloadUsersErrorsTextFile($errorsFileData)
    {

        return $this->streamFileDownload($errorsFileData['filePath'], $errorsFileData['fileName']);
    }

    public function generateEmptyPersonsExcel()
    {
        return $this->generateEmptyExcelWithHeaders("personsVide", [
            'Nom (français)',
            'Prénom (français)',
            'Nom (Arabic)',
            'Prénom (Arabic)',
            'E-mail',
            'Banque',
            'Compte bancaire',
        ]);
    }

    public function generatePersonsExcel()
    {
        return $this->generateExcel(fn() => $this->persons()->map(fn($person) => [
            __("tables.persons.employee_number")   => $person->employee_number,
            __("tables.persons.full_name_fr")      => $person->full_name_fr,
            __("tables.persons.full_name_ar")      => $person->full_name_ar,
            __("tables.persons.email")             => $person->email,
            __("tables.persons.registration_date") => $person->created_at->format('d-m-Y'),
            __("tables.persons.phone")             => $person->tel,
            __("tables.persons.card_number")       => $person->card_number,
            __("tables.persons.birth_date")        => $person->birth_date,
            __("tables.persons.birth_place_fr")    => $person->birth_place_fr,
            __("tables.persons.birth_place_ar")    => $person->birth_place_ar,
            __("tables.persons.birth_place_en")    => $person->birth_place_en,
        ])->toArray(), "persons");
    }

    public function render()
    {
        return view('livewire.core.persons-table');
    }
}
